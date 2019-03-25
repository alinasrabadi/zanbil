<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_PLUGIN_WooCommerce' ) ) {

    /**
     * Main TCW_PLUGIN_WooCommerce class
     * @since       1.0.0
     */
    class TCW_PLUGIN_WooCommerce {

        /**
         * The single instance of the class.
         *
         * @var TCW_PLUGIN_WooCommerce
         * @since 1.0.0
         */
        private static $_instance;


        /**
         * instance of the TCW.
         *
         * @var TCW
         * @since 1.0.0
         */
        private static $TCW;


        /**
         * Path to template folder
         *
         * @since 1.0.0
         */
        public $template_path = 'woocommerce/';


        /**
         * @var TCW_Sharing
         * @since 1.0.0
         */
        public $SHARING;


        /**
         * @var TCW_Notifier
         * @since 1.0.0
         */
        public $NOTIFIER;


        /**
         * @var TCW_Realtime_Offer
         * @since 1.0.0
         */
        public $REALTIME_OFFER;


        /**
         * @var TCW_Compare_Wishlist
         * @since 1.0.0
         */
        public $COMPARE_WISHLIST;


        /**
         * @var TCW_Ajax_Search_Products
         * @since 1.0.0
         */
        public $AJAX_SEARCH_PRODUCTS;


        /**
         * @var TCW_Comments_Like_Dislike
         * @since 1.0.0
         */
        public $COMMENTS_LIKE_DISLIKE;


        /**
         * @var TCW_Price_Table
         * @since 1.0.0
         */
        public $PRICE_TABLE;


        /**
         * Get active instance
         *
         * @param TCW $TCW
         *
         * @since   1.0.0
         * @static
         * @see     tcw_plugin_woocommerce()
         * @return  TCW_PLUGIN_WooCommerce - Main instance
         */
        public static function instance( TCW $TCW ) {
            if ( is_null( self::$_instance ) ) {
                self::$TCW       = $TCW;
                self::$_instance = new self();
            }
            return self::$_instance;
        }


        /**
         * TCW_PLUGIN_WooCommerce constructor.
         */
        public function __construct() {
            if ( !self::is_active() ) return;

            $this->template_path = self::$TCW->template_path . $this->template_path;

            $this->includes();
            $this->setup();
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
            return THF_WOOCOMMERCE_IS_ACTIVE;
        }


        /**
         * @access  private
         * @since   1.0.0
         */
        private function includes() {
            $DC     = DIRECTORY_SEPARATOR;
            $prefix = 'class-thf-woocommerce-';
            $path   = TCW_PATH . $DC . 'includes' . $DC . 'plugins' . $DC . $prefix;

            require_once( $path . 'sharing.php' );
            require_once( $path . 'notifier.php' );
            require_once( $path . 'price-table.php' );
            require_once( $path . 'realtime-offer.php' );
            require_once( $path . 'compare-wishlist.php' );
            require_once( $path . 'ajax-search-products.php' );
            require_once( $path . 'comments-like-dislike.php' );
        }


        /**
         * @access  private
         * @since   1.0.0
         */
        private function setup() {
            //-> Class
            $this->SHARING               = tcw_sharing( $this );
            $this->NOTIFIER              = tcw_notifier( $this );
            $this->PRICE_TABLE           = tcw_price_table( $this );
            $this->REALTIME_OFFER        = tcw_realtime_offer( $this );
            $this->COMPARE_WISHLIST      = tcw_compare_wishlist( $this );
            $this->AJAX_SEARCH_PRODUCTS  = tcw_ajax_search_products( $this );
            $this->COMMENTS_LIKE_DISLIKE = tcw_comments_like_dislike( $this );
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         */
        private function hooks() {

            if ( TCW::get_option( 'wc_order_by_stock_status' ) ) add_filter( 'posts_clauses', [ $this, 'order_by_stock_status' ], 999, 2 );
        }


        /**
         * Order product collections by stock status, instock products first.
         *
         * @param   array  $pieces
         * @param   object $query
         *
         * @filter  posts_clauses
         * @since   1.0.0
         * @return  mixed
         */
        public function order_by_stock_status( $pieces, $query ) {
            global $wpdb;

            // Check for main query and if orderby is specified
			// is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())
            if ( !is_admin() && $this->is_shop( $query ) && $query->is_main_query() ) {
                // Get the order query variable - ASC or DESC
                $order = strtoupper( $query->get( 'order' ) );

                // Make sure the order setting qualifies. If not, set default as ASC
                if ( !in_array( $order, array( 'ASC', 'DESC' ) ) ) $order = 'ASC';

                // Join postmeta to include stock_status and stock info
                // $pieces['join'] .= " LEFT JOIN $wpdb->postmeta {$wpdb->prefix}stock_status ON {$wpdb->prefix}stock_status.post_id = {$wpdb->posts}.ID AND {$wpdb->prefix}stock_status.meta_key = '_stock_status' LEFT JOIN $wpdb->postmeta {$wpdb->prefix}stock ON {$wpdb->prefix}stock.post_id = {$wpdb->posts}.ID AND {$wpdb->prefix}stock.meta_key = '_stock'";
				$pieces['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
               
			   //Set reverse order in a variable
                if ( $order == 'ASC' ) {
                    $in_stock_order = 'DESC';
                } else {
                    $in_stock_order = 'ASC';
                }

                //Specify orderby. Orderby stock status first in reverse order, then stock amount.
                // $pieces['orderby'] = "wp_stock_status.meta_value $in_stock_order, wp_stock.meta_value $order, " . $pieces['orderby'];
				$pieces['orderby'] = " istockstatus.meta_value ASC, ".$pieces['orderby'];

				$pieces['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' ".$pieces['where'];
            }

            return $pieces;
        }

        /**
         * Returns whether or not the product can be purchased.
         * This returns true for 'instock' and 'onbackorder' stock statuses.
         *
         * @since 1.0.0
         * @return bool
         */
        public function is_in_stock() {
            global $product;

            if ( empty( $product ) ) return false;

            return $product->is_in_stock();
        }


        /**
         * WooCommerce Products query
         *
         * @param   $args array
         *
         * @since   1.0.0
         * @static
         * @return  WP_Query|boolean
         */
        public function product_query( $args ) {
            if ( !self::is_active() ) return false;

            $args = wp_parse_args( $args, array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'paged'               => 1,
                'posts_per_page'      => TCW::get_option( 'products_pre_page', 12 ),
            ) );

            return thf_run_the_query( $args, WP_Query::class );
        }


        /**
         * WooCommerce Search Query
         *
         * @param   array $filters [search, brands, categories, exclude, page, args]
         *
         * @return  mixed
         */
        public function product_search_query( $filters = [] ) {
            if ( !self::is_active() || empty( $filters ) || !is_array( $filters ) ) return false;

            $filters = wp_parse_args( $filters, array(
                'page'       => 1,
                'search'     => null,
                'brands'     => array(),
                'categories' => array(),
                'exclude'    => array(),
                'args'       => array(),
            ) );

            extract( $filters );

            // Page number
            if ( !empty( $page ) && is_numeric( $page ) ) {
                $args['paged'] = $page;
            }

            // Search product
            if ( !empty( $search ) ) {
                $args['s'] = $search;
            }

            // excluded products
            if ( !empty( $exclude ) ) {
                $args['post__not_in'] = $exclude;
            }

            // Category filter
            if ( !empty( $categories ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id', //This is optional, as it defaults to 'term_id'
                    'terms'    => $categories,
                    'operator' => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                );
            }

            // Brand filter
            $brand_taxonomy = TCW::get_option( 'wc_brand_taxonomy' );
            if ( !empty( $brand_taxonomy ) && !empty( $brands ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'pa_' . $brand_taxonomy,
                    'field'    => 'id',
                    'terms'    => $brands,
                    'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
                );
            }

            // Set Relation
            if ( !empty( $args['tax_query'] ) ) $args['tax_query']['relation'] = 'AND';

            return $this->product_query( $args );
        }


        /**
         * Get WooCommerce products by category ids
         *
         * @param   $cat_ids integer
         * @param   $args    array
         *
         * @since   1.0.0
         * @static
         * @return  WP_Query|boolean
         */
        public function get_cat_products( $cat_ids, $args = [] ) {
            if ( !self::is_active() ) return false;

            $default = array();

            if ( !empty( $cat_ids ) ) {
                $default['tax_query'] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id', //This is optional, as it defaults to 'term_id'
                    'terms'    => $cat_ids,
                    'operator' => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                );
            }

            $args = wp_parse_args( $args, $default );

            return $this->product_query( $args );
        }


        /**
         * Get product main category by product ID
         *
         * @param   $product_id integer
         *
         * @since   1.0.0
         * @static
         * @return  object|boolean
         */
        public function get_product_main_category( $product_id ) {
            if ( !self::is_active() ) return false;

            $term_ids = wc_get_product_terms( $product_id, 'product_cat', array(
                'fields' => 'ids',
                'parent' => '0',
            ) );

            if ( !empty( $term_ids ) ) {
                return get_term( reset( $term_ids ), 'product_cat' );
            }

            return false;
        }


        /**
         * @param bool $html
         *
         * @return array|bool|int|WP_Error
         */
        public function get_brand_terms( $html = false ) {
            if ( !self::is_active() ) return false;

            $taxonomy_name = TCW::get_option( 'wc_brand_taxonomy' );
            $terms         = get_terms( array(
                'taxonomy'   => 'pa_' . $taxonomy_name,
                'hide_empty' => false,
            ) );

            if ( !is_wp_error( $terms ) && !empty( $terms ) ) {
                if ( !$html ) {
                    return $terms;
                } else {
                    foreach ( $terms as $term ) {
                        echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                    }
                }
            }

            return false;
        }

        /**
         * @param object $query
         *
         * @return bool
         */
        public function is_shop( $query = null ) {
            if ( empty( $query ) ) {
                return is_shop();
            }

            $front_page_id        = get_option( 'page_on_front' );
            $current_page_id      = $query->get( 'page_id' );
            $shop_page_id         = apply_filters( 'woocommerce_get_shop_page_id', get_option( 'woocommerce_shop_page_id' ) );
            $is_static_front_page = 'page' == get_option( 'show_on_front' );

            // Detect if it's a static front page and the current page is the front page, then use our work around
            // Otherwise, just use is_shop since it works fine on other pages
            if ( $is_static_front_page && $front_page_id == $current_page_id ) {
                // is static front page and current page is front page
                $is_shop_page = ( $current_page_id == $shop_page_id ) ? true : false;
            } else {
                // is not static front page, can use is_shop instead
                $is_shop_page = is_shop();
            }

            return $is_shop_page;
        }
    }
} // End if class_exists check

/**
 * Main instance of TCW_PLUGIN_WooCommerce.
 * Returns the main instance of TCW_PLUGIN_WooCommerce to prevent the need to use globals.
 *
 * @param   TCW $TCW
 *
 * @since   1.0.0
 * @return  TCW_PLUGIN_WooCommerce - Main instance
 */
function tcw_plugin_woocommerce( TCW $TCW ) {
    return TCW_PLUGIN_WooCommerce::instance( $TCW );
}