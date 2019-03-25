<?php

thf_build_plugin_option( array(
    'title' => esc_html__( 'WooCommerce Settings', TCW_TEXTDOMAIN ),
    'id'    => 'woocommerce-tab',
    'type'  => 'tab-title',
) );


thf_build_plugin_option( array(
    'title' => esc_html__( 'Order Products By Stock Status', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_order_by_stock_status',
    'type' => 'checkbox',
) );


// thf_build_plugin_option( array(
//     'title' => esc_html__( 'WooCommerce Settings', TCW_TEXTDOMAIN ),
//     'type'  => 'header',
// ) );
// thf_build_plugin_option( array(
//     'name' => esc_html__( 'Number of products per page', TCW_TEXTDOMAIN ),
//     'id'   => 'products_pre_page',
//     'type' => 'number',
// ) );
//
// thf_build_plugin_option( array(
//     'name' => esc_html__( 'Number of the related products', TCW_TEXTDOMAIN ),
//     'id'   => 'related_products_number',
//     'type' => 'number',
// ) );

// thf_build_plugin_option( array(
//     'title' => esc_html__( 'WooCommerce Sidebar Position', TCW_TEXTDOMAIN ),
//     'type'  => 'header',
// ) );
//
// thf_build_plugin_option( array(
//     'id'      => 'woo_sidebar_pos',
//     'type'    => 'visual',
//     'options' => array(
//         ''      => array( esc_html__( 'Default', TCW_TEXTDOMAIN ) => 'default.png' ),
//         'right' => array( esc_html__( 'Sidebar Right', TCW_TEXTDOMAIN ) => 'sidebars/sidebar-right.png' ),
//         'left'  => array( esc_html__( 'Sidebar Left', TCW_TEXTDOMAIN ) => 'sidebars/sidebar-left.png' ),
//         'full'  => array( esc_html__( 'Without Sidebar', TCW_TEXTDOMAIN ) => 'sidebars/sidebar-full-width.png' ),
//     ),
// ) );
//
// thf_build_plugin_option( array(
//     'title' => esc_html__( 'WooCommerce Product Page Sidebar Position', TCW_TEXTDOMAIN ),
//     'type'  => 'header',
// ) );
//
// thf_build_plugin_option( array(
//     'id'      => 'woo_product_sidebar_pos',
//     'type'    => 'visual',
//     'options' => array(
//         ''      => array( esc_html__( 'Default', TCW_TEXTDOMAIN ) => 'default.png' ),
//         'right' => array( esc_html__( 'Sidebar Right', TCW_TEXTDOMAIN ) => 'sidebars/sidebar-right.png' ),
//         'left'  => array( esc_html__( 'Sidebar Left', TCW_TEXTDOMAIN ) => 'sidebars/sidebar-left.png' ),
//         'full'  => array( esc_html__( 'Without Sidebar', TCW_TEXTDOMAIN ) => 'sidebars/sidebar-full-width.png' ),
//     ),
// ) );

?>
