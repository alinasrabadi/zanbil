<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_SHORTCODES' ) ) {

    /**
     * Main TCW_SHORTCODES class
     * @since       1.0.0
     */
    class TCW_SHORTCODES {

        /**
         * The single instance of the class.
         *
         * @var TCW_SHORTCODES
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
         * Get active instance
         *
         * @param   TCW $TCW
         *
         * @since   1.0.0
         * @static
         * @see     tcw_shortcodes()
         * @return  TCW_SHORTCODES - Main instance
         */
        public static function instance( TCW $TCW ) {
            if ( is_null( self::$_instance ) ) {
                self::$TCW       = $TCW;
                self::$_instance = new self();
            }
            return self::$_instance;
        }


        /**
         * TCW_SHORTCODES constructor.
         */
        public function __construct() {
            $this->setup();
        }


        /**
         * Register All Shortcodes
         *
         * @since   1.0.0
         */
        public function setup() {

            //-> [tcw_sharing_button]
            add_shortcode( 'tcw_sharing_button', [ $this, 'tcw_sharing_button' ] );

            //-> [tcw_notifier_subscribe_button]
            add_shortcode( 'tcw_notifier_subscribe_button', [ $this, 'tcw_notifier_subscribe_button' ] );

            //-> [tcw_realtime_offer]
            add_shortcode( 'tcw_realtime_offer', [ $this, 'tcw_realtime_offer' ] );

            //-> [tcw_woocompare_table]
            add_shortcode( 'tcw_woocompare_table', [ $this, 'tcw_woocompare_table' ] );

        }


        /**
         * [tcw_sharing_button product_id=""]
         *
         * @param   array  $atts Shortcode attributes
         * @param   string $content
         *
         * @since   1.0.0
         */
        public function tcw_sharing_button( $atts, $content = null ) {
            /** @var integer $product_id */
            extract( shortcode_atts( array(
                'product_id' => null,
            ), $atts ) );

            self::$TCW->WOOCOMMERCE->SHARING->get_shortcode_content( $product_id );
        }


        /**
         * [tcw_woocompare_table]
         *
         * @param   array  $atts Shortcode attributes
         * @param   string $content
         *
         * @since   1.0.0
         */
        public function tcw_woocompare_table( $atts, $content = null ) {
            self::$TCW->WOOCOMMERCE->COMPARE_WISHLIST->get_shortcode_content();
        }


        /**
         * [tcw_realtime_offer]
         *
         * @param   array  $atts Shortcode attributes
         * @param   string $content
         *
         * @since   1.0.0
         */
        public function tcw_realtime_offer( $atts, $content = null ) {
            self::$TCW->WOOCOMMERCE->REALTIME_OFFER->get_shortcode_content();
        }


        /**
         * [tcw_notifier_subscribe_button]
         *
         * @param   array  $atts Shortcode attributes
         * @param   string $content
         *
         * @since   1.0.0
         */
        public function tcw_notifier_subscribe_button( $atts, $content = null ) {
            self::$TCW->WOOCOMMERCE->NOTIFIER->get_shortcode_content();
        }

    }
} // End if class_exists check

/**
 * Main instance of TCW_SHORTCODES.
 * Returns the main instance of TCW_SHORTCODES to prevent the need to use globals.
 *
 * @param   TCW $TCW
 *
 * @since   1.0.0
 * @return  TCW_SHORTCODES - Main instance
 */
function tcw_shortcodes( TCW $TCW ) {
    return TCW_SHORTCODES::instance( $TCW );
}