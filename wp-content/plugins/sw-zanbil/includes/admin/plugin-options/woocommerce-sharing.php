<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Sharing Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-tab',
    'id'     => 'woocommerce-sharing-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'text' => __( 'Use <code>[tcw_sharing_button product_id=""]</code> shortcode <strong>OR</strong> <code>tcw_show_sharing_button( $product_id )</code> function to show Sharing button.', TCW_TEXTDOMAIN ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Sharing Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_sharing',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name'        => esc_html__( 'Sharing button text', TCW_TEXTDOMAIN ),
    'placeholder' => esc_html__( 'Share', TCW_TEXTDOMAIN ),
    'id'          => 'wc_sharing_button_text',
    'type'        => 'text',
) );


thf_build_plugin_option( array(
    'title' => esc_html__( 'Sharing Icons', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Twitter', TCW_TEXTDOMAIN ),
    'id'   => 'wc_sharing_twitter',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'FaceBook', TCW_TEXTDOMAIN ),
    'id'   => 'wc_sharing_facebook',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Google+', TCW_TEXTDOMAIN ),
    'id'   => 'wc_sharing_google_plus',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Telegram', TCW_TEXTDOMAIN ),
    'id'   => 'wc_sharing_telegram',
    'type' => 'checkbox',
) );