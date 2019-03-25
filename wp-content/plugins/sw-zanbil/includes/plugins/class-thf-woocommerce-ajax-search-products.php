<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_Ajax_Search_Products' ) ) {

    /**
     * Main TCW_Ajax_Search_Products class
     * @since       1.0.0
     */
    class TCW_Ajax_Search_Products {

        /**
         * The single instance of the class.
         *
         * @var TCW_Ajax_Search_Products
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
        public $template_path = 'ajax-search/';


        /**
         * Get active instance
         *
         * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
         *
         * @since   1.0.0
         * @static
         * @see     tcw_ajax_search_products()
         * @return  TCW_Ajax_Search_Products - Main instance
         */
        public static function instance( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
            if ( is_null( self::$_instance ) ) {
                self::$WOOCOMMERCE = $WOOCOMMERCE;
                self::$_instance       = new self();
            }
            return self::$_instance;
        }


        /**
         * TCW_Ajax_Search_Products constructor.
         *
         * @since 1.0.0
         */
        public function __construct() {
            $this->template_path = self::$WOOCOMMERCE->template_path . $this->template_path;

            $this->hooks();
            $this->setup();
        }


        /**
         * Run action and filter hooks
         *
         * @access  private
         * @since   1.0.0
         */
        private function hooks() {

            //-> Ajax Search Product
            add_action( 'wp_ajax_thf_woocommerce_search_product', [ $this, 'process_ajax_request' ] );
            add_action( 'wp_ajax_nopriv_thf_woocommerce_search_product', [ $this, 'process_ajax_request' ] );

        }


        /**
         * Setup main functions
         *
         * @since   1.0.0
         */
        public function setup() {

        }

        /**
         * Process Ajax Request Query
         *
         * @since 1.0.0
         */
        public function process_ajax_request() {
            TCW_HELPER::check_ajax_referer();

            $json = array();

            $filters               = [];
            $filters['search']     = $_POST['search'] ? : '';
            $filters['categories'] = $_POST['category'] ? : '';

            $loop = self::$WOOCOMMERCE->product_search_query( $filters );

            //-> Check query has error
            if ( is_wp_error( $loop ) ) {
                $json['msg'] = __( 'An error occurred while processing request!', TCW_TEXTDOMAIN );
                $json['msg'] .= PHP_EOL . $loop->get_error_message();
                wp_send_json_error( $json );
            }

            //-> Loop Query
            ob_start();
            TCW_HELPER::get_template_part( $this->template_path . 'search-loop.php', compact( 'loop', 'filters' ) );
            $json['html'] = ob_get_clean();

            wp_send_json_success( $json );
        }


    }
} // End if class_exists check

/**
 * Main instance of TCW_Ajax_Search_Products.
 * Returns the main instance of TCW_Ajax_Search_Products to prevent the need to use globals.
 *
 * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
 *
 * @since   1.0.0
 * @return  TCW_Ajax_Search_Products - Main instance
 */
function tcw_ajax_search_products( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
    return TCW_Ajax_Search_Products::instance( $WOOCOMMERCE );
}