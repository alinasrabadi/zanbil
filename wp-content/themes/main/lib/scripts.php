<?php
/**
 * Enqueue scripts and stylesheets
 *
 */

function zanbil_scripts() {	

	wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), null);
	wp_register_style('zanbil_css', get_template_directory_uri() . '/css/app-default.css', array(), null);
	 wp_register_style('respslider_css', get_template_directory_uri() . '/css/slick.css', array(), null);
	wp_register_style('lightbox_css', get_template_directory_uri() . '/css/jquery.fancybox.css', array(), null);
	wp_register_style('animate_css', get_template_directory_uri() . '/css/animate.css', array(), null);
	
	/* register script */

	wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr-2.6.2.min.js', false, null, false);
	wp_register_script('bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
	wp_register_script('gallery_load_js', get_template_directory_uri() . '/js/load-image.min.js', array('bootstrap_js'), null, true);
	wp_register_script('bootstrap_gallery_js', get_template_directory_uri() . '/js/bootstrap-image-gallery.min.js', array('gallery_load_js'), null, true);
	wp_register_script('plugins_js', get_template_directory_uri() . '/js/plugins.js', array('jquery'), null, true);	
	wp_register_script('lightbox_js', get_template_directory_uri() . '/js/jquery.fancybox.pack.js', array('jquery'), null, true);
	wp_register_script('slick_slider_js',get_template_directory_uri().'/js/slick.min.js',array(),null,true);
	wp_register_script('isotope_script', get_template_directory_uri() . '/js/isotope.js', array(), null, true);
	wp_register_script('megamenu_js', get_template_directory_uri() . '/js/megamenu.js', array(), null, true);
	
	wp_register_script('zanbil_js', get_template_directory_uri() . '/js/main.js', array('bootstrap_js', 'plugins_js'), null, true);

	
	
	/* enqueue script & style */
	if ( !is_admin() ){			
		wp_dequeue_style('yith-wcwl-font-awesome');
		wp_dequeue_style('tabcontent_styles');
		wp_enqueue_style('bootstrap');	
		wp_enqueue_script('lightbox_js');
		wp_enqueue_style('slick_css');
		wp_enqueue_style('lightbox_css');
		wp_enqueue_style('zanbil_css');	
		wp_enqueue_style('animate_css');		
		wp_enqueue_script('slick_slider_js');
		wp_enqueue_script('isotope_script');
		wp_enqueue_script('cloud-zoom');
		wp_enqueue_script('number_js');
		wp_enqueue_script('quantity_js');
	    wp_enqueue_style('zanbil_responsive_css');
	
		/* Load style.css from child theme */
		if (is_child_theme()) {
			wp_enqueue_style('zanbil_child_css', get_stylesheet_uri(), false, null);
		}
	}
	if (is_single() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}		
	
	$is_category = is_category() && !is_category('blog');
	if ( !is_admin() ){
		wp_enqueue_script('modernizr');
		wp_enqueue_script('zanbil_js');
	}
	if( zanbil_options()-> getCpanelValue( 'effect_active' ) == 1 ){ 
		if( is_home() || is_front_page() ){
			wp_enqueue_script('scroll_js');
		}
	}
	if( zanbil_options()-> getCpanelValue( 'menu_type' ) == 'mega' ){
		wp_enqueue_script('megamenu_js');	
	}
	$preload_page = zanbil_options()->getCpanelValue( 'preload_active_page' );
	$page_id = get_the_ID();
	if( 1 == zanbil_options()->getCpanelValue( 'preload_active' ) &&( is_array( $preload_page ) && in_array( $page_id, $preload_page ) ) ){
		wp_enqueue_script('preload_script', get_template_directory_uri() . '/js/pathLoader.js', array(), null, true);
	}
}
add_action('wp_enqueue_scripts', 'zanbil_scripts', 100);
