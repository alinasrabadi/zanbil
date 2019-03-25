<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Compare-Wishlist Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-tab',
    'id'     => 'woocommerce-compare-wishlist-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'text' => __( 'Use <code>[tcw_woocompare_table]</code> shortcode <strong>OR</strong> <code>tcw_show_woocompare_table()</code> function to show compare table.', TCW_TEXTDOMAIN ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Compare-Wishlist Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_compare_wishlist',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Select Brand attribute', TCW_TEXTDOMAIN ),
    'id'      => 'wc_brand_taxonomy',
    'type'    => 'select',
    'options' => wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name' ),
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Select Compare page', TCW_TEXTDOMAIN ),
    'id'      => 'wc_compare_page',
    'type'    => 'select',
    'options' => wp_list_pluck( get_pages(), 'post_title', 'ID' ),
) );
