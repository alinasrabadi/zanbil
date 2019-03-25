<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Price Table Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-tab',
    'id'     => 'woocommerce-price-table-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'text' => __( 'Use <code>Visual Composer</code> to show price table.', TCW_TEXTDOMAIN ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Price Table Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_price_table',
    'type' => 'checkbox',
) );