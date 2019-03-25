<?php

thf_build_plugin_option( array(
    'title'  => esc_html__( 'WooCommerce Notifier Email Settings', TCW_TEXTDOMAIN ),
    'parent' => 'woocommerce-notifier-tab',
    'id'     => 'woocommerce-notifier-email-tab',
    'type'   => 'tab-title',
) );

thf_build_plugin_option( array(
    'text' => sprintf( __( 'You Can Use this Plguin for SMTP option: <a href="%s" target="_blank">WP Email SMTP</a>', TCW_TEXTDOMAIN ), 'https://wordpress.org/plugins/wp-email-smtp/' ),
    'type' => 'message',
) );

thf_build_plugin_option( array(
    'title' => esc_html__( 'Notifier Email Settings', TCW_TEXTDOMAIN ),
    'type'  => 'header',
) );

thf_build_plugin_option( array(
    'name' => esc_html__( 'Enable', TCW_TEXTDOMAIN ),
    'id'   => 'wc_notifier_email',
    'type' => 'checkbox',
) );


thf_build_plugin_option( array(
    'name'        => esc_html__( 'Email From', TCW_TEXTDOMAIN ),
    'placeholder' => 'My Name <myname@example.com>',
    'id'          => 'wc_notifier_email_from',
    'type'        => 'text',
) );

$default = __( 'Back In Stock Notification for {product_title} from {site_title}', TCW_TEXTDOMAIN );
thf_build_plugin_option( array(
    'name'    => esc_html__( 'Email Subject', TCW_TEXTDOMAIN ),
    'id'      => 'wc_notifier_email_subject',
    'type'    => 'text',
    'default' => $default,
) );

$default = __( 'Hello
Product {product_title} is now available and can be purchased.', TCW_TEXTDOMAIN );
thf_build_plugin_option( array(
    'name'    => esc_html__( 'Email Content', TCW_TEXTDOMAIN ),
    'id'      => 'wc_notifier_email_content',
    'type'    => 'textarea',
    'default' => $default,
    'hint'    => '<div dir="ltr"><code>{site_title}</code> <code>{site_url}</code> <code>{product_id}</code> <code>{product_url}</code> <code>{product_title}</code> <code>{product_image}</code> <code>{regular_price}</code> <code>{onsale_price}</code> <code>{onsale_from}</code> <code>{onsale_to}</code> <code>{sku}</code> <code>{stock}</code></div>',
) );