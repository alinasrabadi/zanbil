<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_Compare_Wishlist' ) ) {

    /**
     * Main TCW_Compare_Wishlist class
     * @since       1.0.0
     */
    class TCW_Compare_Wishlist {

        /**
         * The single instance of the class.
         *
         * @var TCW_Compare_Wishlist
         * @since 1.0.0
         */
        private static $_instance;


        /**
         * instance of the TCW_PLUGIN_WooCommerce.
         *
         * @var     TCW_PLUGIN_WooCommerce
         * @since   1.0.0
         */
        private static $WOOCOMMERCE;


        /**
         * Path to template folder
         *
         * @since 1.0.0
         */
        public $template_path = 'tm-woocompare/';


        /**
         * Get active instance
         *
         * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
         *
         * @since   1.0.0
         * @static
         * @see     tcw_compare_wishlist()
         * @return  TCW_Compare_Wishlist - Main instance
         */
        public static function instance( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
            if ( is_null( self::$_instance ) ) {
                self::$WOOCOMMERCE = $WOOCOMMERCE;
                self::$_instance   = new self();
            }
            return self::$_instance;
        }


        /**
         * TCW_Compare_Wishlist constructor.
         *
         * @since 1.0.0
         */
        public function __construct() {
            if ( !self::is_active() )
                return;

            $this->template_path = self::$WOOCOMMERCE->template_path . $this->template_path;

            $this->hooks();
        }


        /**
         * Check is active
         *
         * @since   1.0.0
         * @static
         * @return  boolean
         */
        public static function is_active() {
            return TCW::get_option( 'wc_compare_wishlist' ) && THF_TM_WC_COMPARE_WISHLIST_IS_ACTIVE;
        }


        /**
         * Run action and filter hooks
         *
         * @access  private
         * @since   1.0.0
         */
        private function hooks() {

            //-> Add new product to compare list
            add_action( 'wp_ajax_thf_woocompare_add_product', [ $this, 'woocompare_process_ajax_add_product' ] );
            add_action( 'wp_ajax_nopriv_thf_woocompare_add_product', [ $this, 'woocompare_process_ajax_add_product' ] );

            //-> Remove product form compare list
            add_action( 'wp_ajax_thf_woocompare_remove_product', [ $this, 'woocompare_process_ajax_remove_product' ] );
            add_action( 'wp_ajax_nopriv_thf_woocompare_remove_product', [ $this, 'woocompare_process_ajax_remove_product' ] );

            //-> Ajax update compare list
            add_action( 'wp_ajax_thf_woocompare_update_compare_list', [ $this, 'woocompare_page_process_ajax_update_compare_list', ] );
            add_action( 'wp_ajax_nopriv_thf_woocompare_update_compare_list', [ $this, 'woocompare_page_process_ajax_update_compare_list', ] );

            //-> Ajax search product in compare list modal
            add_action( 'wp_ajax_thf_woocompare_update_products_list', [ $this, 'woocompare_modal_process_ajax_search_products', ] );
            add_action( 'wp_ajax_nopriv_thf_woocompare_update_products_list', [ $this, 'woocompare_modal_process_ajax_search_products', ] );
        }


        /**
         * Ajax Remove product action
         *
         * @since   1.0.0
         */
        public function woocompare_process_ajax_add_product() {
            TCW_HELPER::check_ajax_referer();

            if ( self::is_active() ) {
                $product_id = filter_input( INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT );

                // Remove product by id
                tm_woocompare_add( $product_id );

                // Respond
                $this->woocompare_page_process_ajax_update_compare_list();
            }

            wp_send_json_error();
        }


        /**
         * Ajax Remove product action
         *
         * @since   1.0.0
         */
        public function woocompare_process_ajax_remove_product() {
            TCW_HELPER::check_ajax_referer();

            if ( self::is_active() ) {
                $json       = array();
                $product_id = filter_input( INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT );

                // Remove product by id
                tm_woocompare_remove( $product_id );

                // Respond
                wp_send_json_success( $json );
            }

            wp_send_json_error();
        }


        /**
         * Ajax Update compare list
         *
         * @since   1.0.0
         */
        public function woocompare_page_process_ajax_update_compare_list() {
            TCW_HELPER::check_ajax_referer();

            if ( self::is_active() ) {
                $json = array();

                ob_start();
                $this->render_woocompare_page();
                $result = ob_get_clean();

                // Respond
                if ( !is_wp_error( $result ) ) {
                    $json['html'] = $result;
                    wp_send_json_success( $json );
                }
            }

            wp_send_json_error();
        }


        /**
         * Ajax search products in modal
         *
         * @since   1.0.0
         */
        public function woocompare_modal_process_ajax_search_products() {
            TCW_HELPER::check_ajax_referer();

            if ( self::is_active() ) {
                $json    = array();
                $options = [
                    'is_ajax'    => true,
                    'page'       => $_POST['page'] ? : 1,
                    'search'     => $_POST['search'] ? : null,
                    'brands'     => $_POST['brands'] !== '-1' ? explode( ',', $_POST['brands'] ) : null,
                    'categories' => $_POST['categories'] !== '-1' ? explode( ',', $_POST['categories'] ) : null,
                ];

                ob_start();
                $this->render_woocompare_modal( $options );
                $result = ob_get_clean();

                // Respond
                if ( !is_wp_error( $result ) ) {
                    $json['html'] = $result;
                    wp_send_json_success( $json );
                }
            }

            wp_send_json_error();
        }


        /**
         * Run action and filter hooks for get_compare_list_data()
         *
         * @access  static
         * @since   1.0.0
         */
        public function compare_list_data_hooks() {
            add_filter( 'tm_wc_compare_wishlist_templater_callbacks', [ $this, 'get_replace_data' ], 10, 1 );
            add_filter( 'tm_woocommerce_list_product_image', [ $this, 'get_product_image' ], 10, 3 );
            add_filter( 'tm_woocommerce_list_product_title', [ $this, 'get_product_title' ], 10, 4 );
        }


        /**
         * Parse Compare list data
         *
         * @since 1.0.0
         * @return array|string|boolean
         */
        public function get_compare_list_data() {
            if ( !self::is_active() )
                return false;

            $this->compare_list_data_hooks();

            $list = tm_woocompare_get_list();

            if ( empty( $list ) ) {
                return tm_woocompare_empty_message();
            }

            $templater        = tm_wc_compare_wishlist_templater();
            $products         = tm_woocompare_get_products( $list );
            $products_content = array();
            $template         = get_option( 'tm_woocompare_page_template' );
            $template         = $templater->get_template_by_name( $template, 'tm-woocompare' );

            if ( !$template ) {
                $template = $templater->get_template_by_name( 'page.tmpl', 'tm-woocompare' );
            }

            $replace_data = $this->get_replace_data( $templater->get_replace_data() );

            while ( $products->have_posts() ) {

                $products->the_post();

                global $product;

                if ( empty( $product ) ) {
                    continue;
                }
                $pid = method_exists( $product, 'get_id' ) ? $product->get_id() : get_the_id();
                $pid = tm_wc_compare_wishlist()->get_original_product_id( $pid );
                preg_match_all( $templater->macros_regex(), $template, $matches );

                $products_content[$pid]['id'] = $pid;
                $matches[1]                   = array_merge( [ 'permalink' ], $matches[1] );

                if ( !empty( $matches[1] ) ) {

                    foreach ( $matches[1] as $match ) {

                        $macros   = array_filter( explode( ' ', $match, 2 ) );
                        $callback = strtolower( $macros[0] );
                        $attr     = isset( $macros[1] ) ? shortcode_parse_atts( $macros[1] ) : array();

                        if ( !isset( $replace_data[$callback] ) ) {
                            continue;
                        }
                        $callback_func = $replace_data[$callback];

                        if ( !is_callable( $callback_func ) ) {
                            continue;
                        }

                        $content                           = call_user_func( $callback_func, $attr );
                        $products_content[$pid][$callback] = $content;
                    }
                }
            }
            wp_reset_query();

            $parsed_products = tm_woocompare_parse_products( $products_content );

            return $parsed_products;
        }


        /**
         * Get product replace data.
         *
         * @since 1.0.0
         * @return array
         */
        public function get_replace_data( $data ) {
            $data['permalink'] = array( $this, 'get_product_permalink' );
            $data['price']     = array( $this, 'get_product_price' );

            return $data;
        }


        /**
         * Get product title.
         *
         * @since 1.0.0
         * @return string
         */
        public function get_product_title( $html, $link, $title, $product ) {
            return $title;
        }


        /**
         * Get product permalink.
         *
         * @since 1.0.0
         * @return string
         */
        public function get_product_permalink() {
            global $product;

            if ( !empty( $product ) ) {
                return $product->get_permalink();
            }

            return false;
        }


        /**
         * Get product price.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function get_product_price() {
            global $product;

            $price = false;

            if ( !empty( $product ) ) {
                if ( !$product->is_on_sale() ) {
                    $price = $product->get_price_html();
                } else {
                    $sale_price = wc_get_price_to_display( $product );
                    $price = ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . $product->get_price_suffix();
                }

                $price = trim( strip_tags( $price ) );
            }

            return $price;
        }


        /**
         * Get product image.
         *
         * @since 1.0.0
         * @return string
         */
        public function get_product_image( $html, $link, $image ) {
            return $image;
        }


        /**
         * Get product categories ID
         *
         * @param $product_id integer
         *
         * @since 1.0.0
         * @return mixed
         */
        public function get_woocompare_category_ids( $product_id = null ) {
            if ( !self::is_active() )
                return false;

            if ( empty( $product_id ) ) {
                $data = $this->get_compare_list_data();

                if ( !empty( $data ) && is_array( $data ) ) {
                    $product_id = reset( $data['id'] );
                } else {
                    return false;
                }

                unset( $data );
            }

            if ( !empty( $product_id ) && is_numeric( $product_id ) ) {

                $category_ids = wp_get_post_terms( $product_id, 'product_cat', [
                    'fields' => 'ids',
                ] );

                if ( !is_wp_error( $category_ids ) ) {
                    return $category_ids;
                }
            }

            return false;
        }


        /**
         * Render woocpmpare shortcode
         *
         * @since 1.0.0
         */
        public function get_shortcode_content() {
            if ( !self::is_active() )
                return;

            $data = $this->get_compare_list_data();

            // wp_enqueue_style( 'fselect' );
            // wp_enqueue_script( 'fselect' );

            wp_enqueue_style( 'thf-customize-wc-compare-page' );
            wp_enqueue_script( 'thf-customize-wc-compare-page' );

            $this->render_woocompare_page( $data );
            $this->render_woocompare_modal( $data );
        }


        /**
         * Render woocpmpare page
         *
         * @param array $data
         *
         * @since 1.0.0
         */
        public function render_woocompare_page( $data = array() ) {
            if ( !self::is_active() )
                return;

            $template_path = $this->template_path . 'page.php';
            $data          = $data ? : $this->get_compare_list_data();
            $loop          = array();
            $attrs         = array();

            // Check if products exists
            if ( !empty( $data ) && is_array( $data ) ) {
                $product_ids = $data['id'];

                // Compare list products
                $attrs = $data;
                foreach ( $product_ids as $index => $id ) {
                    foreach ( [ 'id', 'title', 'permalink', 'image', 'price', 'addtocart' ] as $attr ) {
                        $loop[$id][$attr] = $data[$attr][$id];
                        unset( $attrs[$attr] );
                    }
                }
            }

            // Set Template args
            $args = [
                'products' => $loop,
                'attrs'    => $attrs,
            ];

            TCW_HELPER::get_template_part( $template_path, $args );
        }


        /**
         * Render woocpmpare modal
         *
         * @param array $options
         *
         * @since 1.0.0
         */
        public function render_woocompare_modal( $options = array() ) {
            if ( !self::is_active() )
                return;

            $template_name = 'page-modal.php';
            $template_path = $this->template_path;
            $page          = 1;
            $search        = null;
            $brands        = array();
            $categories    = array();
            $exclude       = array();
            $is_ajax       = false;

            if ( is_array( $options ) && isset( $options['is_ajax'] ) ) {
                extract( $options );
            } else {
                $data = $options;
            }

            if ( $is_ajax ) {
                $template_name = 'page-modal-loop.php';
            }

            $template = $template_path . $template_name;
            $data     = $data ? : $this->get_compare_list_data();

            // Check if products exists
            if ( !empty( $data ) && is_array( $data ) ) {
                $product_id = $data['id'];
                $exclude    = array_values( $product_id );
                $categories = $categories ? : $this->get_woocompare_category_ids( reset( $product_id ) );
            }

            $filters = array(
                'page'       => $page,
                'search'     => $search,
                'brands'     => $brands,
                'categories' => $categories,
                'exclude'    => $exclude,
            );

            // Get modal products
            $query = $this->woocompare_modal_query( $filters );

            // Brands
            ob_start();
            self::$WOOCOMMERCE->get_brand_terms( true );
            $brand_options_html = ob_get_clean();

            // Set Template args
            $args = [
                'loop'               => $query,
                'categories'         => $categories,
                'brand_options_html' => $brand_options_html,
            ];

            TCW_HELPER::get_template_part( $template, $args );
        }


        /**
         * Modal Search Product Query
         *
         * @param array $filters
         *
         * @return WP_Query
         */
        public function woocompare_modal_query( $filters = array() ) {
            return self::$WOOCOMMERCE->product_search_query( $filters );
        }

    }
} // End if class_exists check

/**
 * Main instance of TCW_Compare_Wishlist.
 * Returns the main instance of TCW_Compare_Wishlist to prevent the need to use globals.
 *
 * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
 *
 * @since   1.0.0
 * @return  TCW_Compare_Wishlist - Main instance
 */
function tcw_compare_wishlist( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
    return TCW_Compare_Wishlist::instance( $WOOCOMMERCE );
}