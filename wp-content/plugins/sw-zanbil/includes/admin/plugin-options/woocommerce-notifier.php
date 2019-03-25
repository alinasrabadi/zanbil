<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Notifier Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-tab',
    'id'     => 'woocommerce-notifier-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'text' => __( 'Use <code>[tcw_notifier_subscribe_button]</code> shortcode <strong>OR</strong> <code>tcw_show_notifier_subscribe_button()</code> function to show subscribe button.', TCW_TEXTDOMAIN ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Notifier Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_notifier',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name'        => esc_html__( 'Subscribe button text', TCW_TEXTDOMAIN ),
    'placeholder' => esc_html__( 'Notify Me', TCW_TEXTDOMAIN ),
    'id'          => 'wc_notifier_button_text',
    'type'        => 'text',
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Show Subscribe button', TCW_TEXTDOMAIN ),
    'id'      => 'wc_notifier_subscribe_btn',
    'type'    => 'radio',
    'options' => [
        'body'      => esc_html__( 'Auto display in product body', TCW_TEXTDOMAIN ),
        'thumbnail' => esc_html__( 'Auto display under the product thumbnails', TCW_TEXTDOMAIN ),
        'manually'  => esc_html__( 'Display manually by hooks or shortcode', TCW_TEXTDOMAIN ),
    ],
) );


thf_build_plugin_option( array(
    'name'    => esc_html__( 'Delete/Un-CheckSubscribed User after Successful notification', TCW_TEXTDOMAIN ),
    'id'      => 'wc_notifier_unsubscribe_after_send',
    'type'    => 'select',
    'options' => [
        'on'  => esc_html__( 'Delete', TCW_TEXTDOMAIN ),
        'off' => esc_html__( 'Uncheck', TCW_TEXTDOMAIN ),
    ],
) );