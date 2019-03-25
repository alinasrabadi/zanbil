<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Sharing Email Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-sharing-tab',
    'id'     => 'woocommerce-sharing-email-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'text' => sprintf( __( 'You Can Use this Plguin for SMTP option: <a href="%s" target="_blank">WP Email SMTP</a>', TCW_TEXTDOMAIN ), 'https://wordpress.org/plugins/wp-email-smtp/' ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Sharing Email Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_sharing_email',
    'type' => 'checkbox',
) );


thf_build_plugin_option( array(
    'name'        => esc_html__( 'Email From', TCW_TEXTDOMAIN ),
    'placeholder' => '<no.reply@@example.com>',
    'id'          => 'wc_sharing_email_from',
    'type'        => 'text',
) );

$default = __( 'From your friend', TCW_TEXTDOMAIN );
thf_build_plugin_option( array(
    'name'    => esc_html__( 'Email Subject', TCW_TEXTDOMAIN ),
    'id'      => 'wc_sharing_email_subject',
    'type'    => 'text',
    'default' => $default,
) );

$default = __( 'Hello
Your friend will offer you a purchase and view of this product from {site_title}.
<a href="{product_url}" target="_blank">{product_title}</a>', TCW_TEXTDOMAIN );
thf_build_plugin_option( array(
    'name'    => esc_html__( 'Email Content', TCW_TEXTDOMAIN ),
    'id'      => 'wc_sharing_email_content',
    'type'    => 'textarea',
    'default' => $default,
    'hint'    => '<div dir="ltr"><code>{site_title}</code> <code>{site_url}</code> <code>{product_id}</code> <code>{product_url}</code> <code>{product_title}</code> <code>{product_image}</code> <code>{regular_price}</code> <code>{onsale_price}</code> <code>{onsale_from}</code> <code>{onsale_to}</code> <code>{sku}</code> <code>{stock}</code></div>',
) );