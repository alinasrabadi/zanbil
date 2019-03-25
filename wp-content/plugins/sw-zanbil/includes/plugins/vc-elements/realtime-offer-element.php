<?php
/*
Element Description: VC Real-time Offer Box
*/

// Element Class
class vcRealtimeOffer extends WPBakeryShortCode {

    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_realtime_offer_mapping' ) );
        add_shortcode( 'vc_realtime_offer', array( $this, 'vc_realtime_offer_html' ) );
    }

    // Element Mapping
    public function vc_realtime_offer_mapping() {

        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        // Map the block with vc_map()
        vc_map( array(
            'name'     => 'پیشنهاد لحظه ای',
            'base'     => 'vc_realtime_offer',
            'category' => 'زنبیل',
            'icon'     => get_template_directory_uri().'/assets/img/vc-icon.png',
            'params'   => array(

                array(
                    'type'        => 'textfield',
                    'holder'      => 'h3',
                    'param_name'  => 'title',
                    'class'       => 'title-class',
                    'heading'     => __( 'Title', TCW_TEXTDOMAIN ),
                    'weight'      => 0,
                    'admin_label' => 0,
                    'value'       => TCW::get_option( 'wc_realtime_offer_title' ),
                ),
            ),
        ) );

    }


    // Element HTML
    public function vc_realtime_offer_html( $atts ) {
        /** @var string $title */

        // Params extraction
        extract( shortcode_atts( array(
            'title' => TCW::get_option( 'wc_realtime_offer_title' ),
        ), $atts ) );

        ob_start();
        tcw()->WOOCOMMERCE->REALTIME_OFFER->get_shortcode_content( $title );
        return ob_get_clean();
    }

} // End Element Class