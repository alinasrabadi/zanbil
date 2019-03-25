<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_Realtime_Offer' ) ) {

    /**
     * Main TCW_Realtime_Offer class
     * @since       1.0.0
     */
    class TCW_Realtime_Offer {
        /**
         * The single instance of the class.
         *
         * @var TCW_Realtime_Offer
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
        public $template_path = 'realtime-offer/';


        /**
         * Get active instance
         *
         * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
         *
         * @since   1.0.0
         * @static
         * @see     tcw_realtime_offer()
         * @return  TCW_Realtime_Offer - Main instance
         */
        public static function instance( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
            if ( is_null( self::$_instance ) ) {
                self::$WOOCOMMERCE = $WOOCOMMERCE;
                self::$_instance   = new self();
            }
            return self::$_instance;
        }

        /**
         * TCW_Realtime_Offer constructor.
         */
        public function __construct() {
            if ( !self::is_active() )
                return;

            $this->template_path = self::$WOOCOMMERCE->template_path . $this->template_path;

        }

        /**
         * Check is active
         *
         * @since   1.0.0
         * @static
         * @return  boolean
         */
        public static function is_active() {
            return TCW::get_option( 'wc_realtime_offer' );
        }

        /**
         * WooCommerce Get Offered Products
         *
         * @since   1.0.0
         * @return  array
         */
        public function get_offered_products() {
            if ( !self::is_active() )
                return [];

            $products = [];
            $per_page = TCW::get_option( 'wc_realtime_offer_products_per_page', 10 );

            $args = [
                'post_type'      => 'product',
                'no_found_rows'  => true,
                'posts_per_page' => $per_page,
                'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_sale_price',
                        'value'   => 0,
                        'compare' => '>=',
                    ),
                    array(
                        'key'     => '_stock_status',
                        'compare' => '=',
                        'value'   => 'instock',
                    ),
                ),
                'fields'         => 'ids',
                'order'          => 'desc',
                'orderby'        => 'rand',
            ];

            // $query = self::$WOOCOMMERCE->product_query( $args );
            $query = get_posts( $args );

            if ( !is_wp_error( $query ) && is_array( $query ) ) {
                foreach ( $query as $product_id ) {
                    $product               = new WC_Product( $product_id );
                    $thumbnail             = has_post_thumbnail( $product_id ) ? get_the_post_thumbnail_url( $product_id, 'shop_catalog' ) : wc_placeholder_img_src();
                    $products[$product_id] = array(
                        'id'            => $product_id,
                        'link'          => get_the_permalink( $product_id ),
                        'title'         => get_the_title( $product_id ),
                        'thumbnail'     => $thumbnail,
                        'sale_price'    => strip_tags( wc_price( $product->get_sale_price() ) ),
                        'regular_price' => strip_tags( wc_price( $product->get_regular_price() ) ),
                    );
                }
            }

            return $products;
        }

        /**
         * @param string $title
         *
         */
        public function get_shortcode_content( $title = "" ) {
            if ( !self::is_active() )
                return;

            if ( empty( $title ) )
                $title = TCW::get_option( 'wc_realtime_offer_title' );

            $products = $this->get_offered_products();

            wp_enqueue_script( 'swiper' );
            wp_enqueue_style( 'swiper' );

            wp_enqueue_script( 'thf-customize-wc-realtime-offer' );
            wp_enqueue_style( 'thf-customize-wc-realtime-offer' );

            TCW_HELPER::get_template_part( $this->template_path . 'realtime-offer.php', compact( 'title', 'products' ) );
        }
    }
} // End if class_exists check

/**
 * Main instance of TCW_Realtime_Offer.
 * Returns the main instance of TCW_Realtime_Offer to prevent the need to use globals.
 *
 * @param   TCW_PLUGIN_WooCommerce $WOOCOMMERCE
 *
 * @since   1.0.0
 * @return  TCW_Realtime_Offer - Main instance
 */
function tcw_realtime_offer( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
    return TCW_Realtime_Offer::instance( $WOOCOMMERCE );
}