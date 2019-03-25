<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Comment Like Dislike Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-tab',
    'id'     => 'woocommerce-like-dislike-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Comment Like Dislike Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_comments_like_dislike',
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable Admin Statistics', TCW_TEXTDOMAIN ),
    'id'   => 'wc_comments_like_dislike_columns',
    'hint' => sprintf( __( 'If Enabled, new columns added in Admin <a href="%s" target="_blank" >Comments Page</a>.', TCW_TEXTDOMAIN ), admin_url( 'edit-comments.php' ) ),
    'type' => 'checkbox',
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Like Dislike Position', TCW_TEXTDOMAIN ),
    'id'      => 'wc_comments_like_dislike_position',
    'type'    => 'select',
    'options' => [
        'after'  => esc_html__( 'After Comment', TCW_TEXTDOMAIN ),
        'before' => esc_html__( 'Before Comment', TCW_TEXTDOMAIN ),
    ],
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Like Dislike Display', TCW_TEXTDOMAIN ),
    'id'      => 'wc_comments_like_dislike_display',
    'type'    => 'select',
    'options' => [
        'both'         => esc_html__( 'Display Both', TCW_TEXTDOMAIN ),
        'like_only'    => esc_html__( 'Display Like Only', TCW_TEXTDOMAIN ),
        'dislike_only' => esc_html__( 'Display Dislike Only', TCW_TEXTDOMAIN ),
    ],
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Like Dislike Restriction', TCW_TEXTDOMAIN ),
    'id'      => 'wc_comments_like_dislike_restriction',
    'type'    => 'select',
    'options' => [
        'cookie'         => esc_html__( 'Cookie Restriction', TCW_TEXTDOMAIN ),
        'ip'             => esc_html__( 'IP Restriction', TCW_TEXTDOMAIN ),
        'login'          => esc_html__( 'Login Restriction', TCW_TEXTDOMAIN ),
        'IP Restriction' => esc_html__( 'No Restriction', TCW_TEXTDOMAIN ),
    ],
) );

thf_build_plugin_option( array(
    'name'    => esc_html__( 'Like Dislike Display Order', TCW_TEXTDOMAIN ),
    'id'      => 'wc_comments_like_dislike_display_order',
    'type'    => 'select',
    'options' => [
        'like-dislike' => esc_html__( 'Like Dislike', TCW_TEXTDOMAIN ),
        'dislike-like' => esc_html__( 'Dislike Like', TCW_TEXTDOMAIN ),
    ],
) );

thf_build_plugin_option( array(
    'name'        => esc_html__( 'Hint', TCW_TEXTDOMAIN ),
    'placeholder' => esc_html__( 'Was this comment helpful?', TCW_TEXTDOMAIN ),
    'id'          => 'wc_comments_hint_text',
    'type'        => 'text',
) );

thf_build_plugin_option( array(
    'name'        => esc_html__( 'Like hover text', TCW_TEXTDOMAIN ),
    'placeholder' => esc_html__( 'Like', TCW_TEXTDOMAIN ),
    'id'          => 'wc_comments_like_hover_text',
    'type'        => 'text',
) );

thf_build_plugin_option( array(
    'name'        => esc_html__( 'Dislike hover text', TCW_TEXTDOMAIN ),
    'placeholder' => esc_html__( 'Dislike', TCW_TEXTDOMAIN ),
    'id'          => 'wc_comments_dislike_hover_text',
    'type'        => 'text',
) );


thf_build_plugin_option( array(
    'title' => esc_html__( 'Design Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Count color', TCW_TEXTDOMAIN ),
    'id'   => 'wc_comments_count_color',
    'type' => 'color',
) );

thf_build_plugin_option( array(
    'name'   => esc_html__( 'Enable icons', TCW_TEXTDOMAIN ),
    'id'     => 'wc_comments_icons',
    'type'   => 'checkbox',
    'toggle' => '#wc_comments_like_icon-item,
                 #wc_comments_dislike_icon-item',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Like icon', TCW_TEXTDOMAIN ),
    'id'   => 'wc_comments_like_icon',
    'type' => 'icon',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Like icon color', TCW_TEXTDOMAIN ),
    'id'   => 'wc_comments_like_color',
    'type' => 'color',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Dislike icon', TCW_TEXTDOMAIN ),
    'id'   => 'wc_comments_dislike_icon',
    'type' => 'icon',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Dislike icon color', TCW_TEXTDOMAIN ),
    'id'   => 'wc_comments_dislike_color',
    'type' => 'color',
) );


?>
