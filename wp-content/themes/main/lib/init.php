<?php
/**
 * zanbil initial setup and constants
 */
 
if (!isset($content_width)) { $content_width = 940; }

function zanbil_setup() {
	// Make theme available for translation
	load_theme_textdomain('zanbil', get_template_directory() . '/lang');

	// Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
	register_nav_menus(array(
		'leftmenu' => esc_html__('Vertical Menu', 'zanbil'),
		'primary_menu' => esc_html__('Primary Menu', 'zanbil'),
	));
	
	add_theme_support('bootstrap-gallery');     // Enable Bootstrap's thumbnails component on [gallery]

	
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	if( zanbil_options()->getCpanelValue( 'product_zoom' ) ) :
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );
	endif;
		
	add_theme_support( "title-tag" );
	
	// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
	add_theme_support('post-thumbnails');

	// Add post formats (http://codex.wordpress.org/Post_Formats)
	add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
	
	// Custom image header
	$zanbil_header_arr = array(
		'default-image' => get_template_directory_uri().'/assets/img/logo-default.png',
		'uploads'       => true
	);
	add_theme_support( 'custom-header', $zanbil_header_arr );
	
	// Custom Background 
	$zanbil_bgarr = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);
	add_theme_support( 'custom-background', $zanbil_bgarr );
	
	// Tell the TinyMCE editor to use a custom stylesheet
	add_editor_style('/assets/css/editor-style.css');
		
	new ZANBIL_Menu();
}
add_action('after_setup_theme', 'zanbil_setup');
// Remove query string from static files
function remove_cssjs_ver( $src ) {
if( strpos( $src, '?ver=' ) )
$src = remove_query_arg( 'ver', $src );
return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
