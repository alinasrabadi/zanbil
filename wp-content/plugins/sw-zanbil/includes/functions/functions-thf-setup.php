<?php
/**
 * Plugin Custom Styles
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


/*
 * Register Main Scripts and Styles
 */
add_action( 'wp_enqueue_scripts', 'tcw_enqueue_scripts', 221 );
function tcw_enqueue_scripts() {
    $min = TCW_STYLES::is_minified();

    //-> Main
    wp_register_script( 'thf-customize-wc', TCW_URL . '/assets/js/thf-customize-wc' . $min . '.js', array( 'jquery' ), TCW_VER, false );
    wp_register_style( 'thf-customize-wc', TCW_URL . '/assets/css/thf-customize-wc' . $min . '.css', array(), TCW_VER );


    /**
     * Vendors
     */

    //-> Fontawesome: https://github.com/FortAwesome/Font-Awesome
    wp_deregister_style( 'fontawesome' );
    wp_enqueue_style( 'fontawesome', TCW_URL . '/assets/vendors/fontawesome/css/all' . $min . '.css', array(), TCW_VER );
    wp_enqueue_style( 'fontawesome-v4-shims', TCW_URL . '/assets/vendors/fontawesome/css/v4-shims' . $min . '.css', array(), TCW_VER );

    //-> swiper: https://github.com/nolimits4web/swiper
    wp_register_script( 'swiper', TCW_URL . '/assets/vendors/swiper/js/swiper' . $min . '.js', array( 'jquery' ), '4.4.0', false );
    wp_register_style( 'swiper', TCW_URL . '/assets/vendors/swiper/css/swiper' . $min . '.css', array(), '4.4.0' );

    //-> Bootstrap: Customize modal
    wp_register_style( 'thf-customize-wc-modal', TCW_URL . '/assets/css/thf-customize-wc-modal' . $min . '.css', array(), TCW_VER );


    /**
     * Plugin Components
     */

    //-> Ajax Search
    wp_register_script( 'thf-customize-wc-ajax-search', TCW_URL . '/assets/js/thf-customize-wc-ajax-search' . $min . '.js', array( 'jquery' ), TCW_VER, true );
    wp_register_style( 'thf-customize-wc-ajax-search', TCW_URL . '/assets/css/thf-customize-wc-ajax-search' . $min . '.css', array( 'thf-customize-wc' ), TCW_VER );

    //-> Sharing
    wp_register_script( 'thf-customize-wc-sharing', TCW_URL . '/assets/js/thf-customize-wc-sharing' . $min . '.js', array( 'jquery' ), TCW_VER, false );
    wp_register_style( 'thf-customize-wc-sharing', TCW_URL . '/assets/css/thf-customize-wc-sharing' . $min . '.css', array( 'thf-customize-wc' ), TCW_VER );

    //-> Notifier
    wp_register_script( 'thf-customize-wc-notifier', TCW_URL . '/assets/js/thf-customize-wc-notifier' . $min . '.js', array( 'jquery' ), TCW_VER, false );
    wp_register_style( 'thf-customize-wc-notifier', TCW_URL . '/assets/css/thf-customize-wc-notifier' . $min . '.css', array( 'thf-customize-wc' ), TCW_VER );

    //-> Real-time Offer
    wp_register_script( 'thf-customize-wc-realtime-offer', TCW_URL . '/assets/js/thf-customize-wc-realtime-offer' . $min . '.js', array( 'jquery', 'slick_slider_js' ), TCW_VER, false );
    wp_register_style( 'thf-customize-wc-realtime-offer', TCW_URL . '/assets/css/thf-customize-wc-realtime-offer' . $min . '.css', array( 'thf-customize-wc' ), TCW_VER );//-> Real-time Offer

    //-> Price Table
    wp_register_script( 'thf-customize-wc-price-table', TCW_URL . '/assets/js/thf-customize-wc-price-table' . $min . '.js', array( 'jquery' ), TCW_VER, false );
    wp_register_style( 'thf-customize-wc-price-table', TCW_URL . '/assets/css/thf-customize-wc-price-table' . $min . '.css', array( 'thf-customize-wc' ), TCW_VER );

    //-> Comment Like Dislike
    wp_register_script( 'thf-customize-wc-comments-like-dislike', TCW_URL . '/assets/js/thf-customize-wc-comments-like-dislike' . $min . '.js', array( 'jquery' ), TCW_VER, true );
    wp_register_style( 'thf-customize-wc-comments-like-dislike', TCW_URL . '/assets/css/thf-customize-wc-comments-like-dislike' . $min . '.css', array(), TCW_VER );

    //-> Compare page
    wp_register_script( 'thf-customize-wc-compare-page', TCW_URL . '/assets/js/thf-customize-wc-compare-page' . $min . '.js', array( 'jquery' ), TCW_VER, true );
    wp_register_style( 'thf-customize-wc-compare-page', TCW_URL . '/assets/css/thf-customize-wc-compare-page' . $min . '.css', array( 'thf-customize-wc' ), TCW_VER );


    /**
     * Enqueue
     */

    //-> Main
    wp_enqueue_script( 'thf-customize-wc' );
    wp_enqueue_style( 'thf-customize-wc' );

    //-> Ajax Search
    wp_enqueue_script( 'thf-customize-wc-ajax-search' );
    wp_enqueue_style( 'thf-customize-wc-ajax-search' );

    //-> Bootstrap: Customize Modal
    wp_enqueue_style( 'thf-customize-wc-modal' );

    //-> Notifier
    if ( TCW_Notifier::is_active() && is_product() ) {
        wp_enqueue_script( 'thf-customize-wc-notifier' );
        wp_enqueue_style( 'thf-customize-wc-notifier' );
    }

    //-> Real-time Offer
    if ( TCW_Realtime_Offer::is_active() ) {
        wp_enqueue_script( 'swiper' );
        wp_enqueue_style( 'swiper' );
        wp_enqueue_script( 'thf-customize-wc-realtime-offer' );
        wp_enqueue_style( 'thf-customize-wc-realtime-offer' );
    }

    //-> Comment Like Dislike
    if ( TCW_Comments_Like_Dislike::is_active() && is_product() ) {
        wp_enqueue_script( 'thf-customize-wc-comments-like-dislike' );
        wp_enqueue_style( 'thf-customize-wc-comments-like-dislike' );
    }

    //-> Compare page
    if ( TCW_Compare_Wishlist::is_active() && is_compare_page() ) {
        wp_enqueue_script( 'thf-customize-wc-compare-page' );
        wp_enqueue_style( 'thf-customize-wc-compare-page' );
    }
}
