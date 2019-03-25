<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Real-time Offer Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-tab',
    'id'     => 'woocommerce-realtime-offer-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'text' => __( 'Use <code>[tcw_realtime_offer]</code> shortcode <strong>OR</strong> <code>tcw_show_realtime_offer()</code> function <strong>OR</strong> <code>Visual Composer</code> to show offers.', TCW_TEXTDOMAIN ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Real-time Offer Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_realtime_offer',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Title', TCW_TEXTDOMAIN ),
    'id'      => 'wc_realtime_offer_title',
    'type'    => 'text',
    'default' => "پیشنهادهای لحظه‌ای برای شما",
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Number of Products to Show', TCW_TEXTDOMAIN ),
    'id'   => 'wc_realtime_offer_products_per_page',
    'type' => 'number',
) );