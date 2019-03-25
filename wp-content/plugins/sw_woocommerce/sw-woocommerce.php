<?php
/**
 * Plugin Name: افزونه زنبیل (ووکامرس)
 * Plugin URI: http://avin-tarh.ir
 * Description: این افزونه المان های اضافی به ویژوال کامپوزر اضافه میکند تا محصولات را بهتر نمایش دهید.
 * Version: 5.0
 * Author: گروه طراحی آوین
 * Author URI: http://avin-tarh.ir
 * Domain Path: /languages/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// define plugin path
if ( ! defined( 'WCPATH' ) ) {
	define( 'WCPATH', plugin_dir_path( __FILE__ ) );
}

// define plugin URL
if ( ! defined( 'WCURL' ) ) {
	define( 'WCURL', plugins_url(). '/sw_woocommerce' );
}

// define plugin theme path
if ( ! defined( 'WCTHEME' ) ) {
	define( 'WCTHEME', plugin_dir_path( __FILE__ ). 'includes/themes' );
}

if( !function_exists( 'is_plugin_active' ) ){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
/* Load text domain */

add_action( 'plugins_loaded', 'sw_load_textdomain' );
function sw_load_textdomain() {
	load_plugin_textdomain( 'sw_woocommerce', false, WCPATH . '/languages' ); 
}

/* Include Widget File */
require_once( WCPATH . '/includes/sw-widgets.php' );

if ( class_exists( 'Vc_Manager' ) ) {
	add_action( 'vc_frontend_editor_render', 'Sw_EnqueueJsFrontend' );
	function Sw_EnqueueJsFrontend(){
		wp_register_script( 'slick_slider_js', plugins_url( 'js/slick.min.js', __FILE__ ),array(), null, true );	
		wp_register_script( 'custom_js', plugins_url( 'js/custom_js.js', __FILE__ ),array( 'slick_slider_js' ), null, true );	
		wp_enqueue_script('custom_js');
	}
}

function sw_enqueue_script(){		
	wp_register_script( 'slick_slider_js', plugins_url( 'js/slick.min.js', __FILE__ ),array(), null, true );		
	if (!wp_script_is('slick_slider_js')) {
		wp_enqueue_script('slick_slider_js');
	}
	wp_register_script( 'countdown_slider_js', plugins_url( 'js/jquery.countdown.min.js', __FILE__ ),array(), null, true );		
	if (!wp_script_is('countdown_slider_js')) {
		wp_enqueue_script('countdown_slider_js');
	}	
}
add_action( 'wp_enqueue_scripts', 'sw_enqueue_script', 99 );

