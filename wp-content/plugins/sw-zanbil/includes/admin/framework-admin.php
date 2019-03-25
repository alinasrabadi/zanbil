<?php
/**
 * Dashboard main file
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


/*-----------------------------------------------------------------------------------
	WordPress uses the menu parent name in the sub menu pages class names example:
	{menu-slug}_page_{sub-menu-slug} and use it in the main page hook name example:
	load-{menu-slug}_page_{sub-menu-slug} and that craetes a lot of issues as you can't
	use these hooks or classes if the parent menu name is translatable

	What we do here is change the Parent menu page from the English static name to a
	translatable text
/*-----------------------------------------------------------------------------------*/
add_filter( 'parent_file', 'thf_change_plugin_parent_menu', 1 );
function thf_change_plugin_parent_menu( $name ) {

    global $menu;

    foreach ( $menu as $key => $value ) {
        if ( !empty( $value[0] ) && $value[0] == 'thfplugin' ) {
            $menu[$key][0] = apply_filters( 'Themesfa/plugin_name', 'Themesfa' );
            break;
        }
    }

    return $name;
}


/**
 * Theme Options tabs
 */
add_filter( 'Themesfa/options_tab_title', 'thf_build_plugin_options_tabs', 9 );
function thf_build_plugin_options_tabs() {

    $settings_tabs = [

        // 'general' => [
        //     'icon'  => 'admin-generic',
        //     'title' => esc_html__( 'General', TCW_TEXTDOMAIN ),
        // ],

        // 'styling' => [
        //     'icon'  => 'admin-appearance',
        //     'title' => esc_html__( 'Styling', TCW_TEXTDOMAIN ),
        // ],

    ];

    /**
     * Woocommerce
     */
    if ( THF_WOOCOMMERCE_IS_ACTIVE ) {
        $settings_tabs['woocommerce'] = [
            'icon'  => 'woocommerce',
            'title' => esc_html__( 'WooCommerce', TCW_TEXTDOMAIN ),
        ];


        //-> Sharing
        $settings_tabs['woocommerce']['submenu']['sharing'] = [
            'title' => esc_html__( 'Sharing', TCW_TEXTDOMAIN ),
        ];

        // Sharing Email
        $settings_tabs['woocommerce']['submenu']['sharing']['submenu']['email'] = [
            'title' => esc_html__( 'Email', TCW_TEXTDOMAIN ),
        ];

        // Sharing SMS
        $settings_tabs['woocommerce']['submenu']['sharing']['submenu']['sms'] = [
            'title' => esc_html__( 'SMS', TCW_TEXTDOMAIN ),
        ];

        //-> Notifier
        $settings_tabs['woocommerce']['submenu']['notifier'] = [
            'title' => esc_html__( 'Notifier', TCW_TEXTDOMAIN ),
        ];

        // Notifier Email
        $settings_tabs['woocommerce']['submenu']['notifier']['submenu']['email'] = [
            'title' => esc_html__( 'Email', TCW_TEXTDOMAIN ),
        ];

        // Notifier SMS
        $settings_tabs['woocommerce']['submenu']['notifier']['submenu']['sms'] = [
            'title' => esc_html__( 'SMS', TCW_TEXTDOMAIN ),
        ];

        //-> Notifier
        $settings_tabs['woocommerce']['submenu']['price-table'] = [
            'title' => esc_html__( 'Price Table', TCW_TEXTDOMAIN ),
        ];

        //-> Real-time Offer
        $settings_tabs['woocommerce']['submenu']['realtime-offer'] = [
            'title' => esc_html__( 'Real-time Offer', TCW_TEXTDOMAIN ),
        ];

        //-> Compare Wishlist
        $settings_tabs['woocommerce']['submenu']['compare-whislist'] = [
            'title' => esc_html__( 'Compare-Wishlist', TCW_TEXTDOMAIN ),
        ];

        //-> Comments Like Dislike
        $settings_tabs['woocommerce']['submenu']['comments-like-dislike'] = [
            'title' => esc_html__( 'Comment Like Dislike', TCW_TEXTDOMAIN ),
        ];

    }

    $settings_tabs['advanced'] = [
        'icon'  => 'admin-tools',
        'title' => esc_html__( 'Advanced', TCW_TEXTDOMAIN ),
    ];

    $settings_tabs['backup'] = [
        'icon'  => 'migrate',
        'title' => esc_html__( 'Export/Import', TCW_TEXTDOMAIN ),
    ];

    return $settings_tabs;
}


/**
 * Custom Admin Bar Menus
 */
add_action( 'admin_bar_menu', 'thf_modify_admin_bar', 40 );
function thf_modify_admin_bar( $wp_admin_bar ) {

    if ( !current_user_can( 'switch_themes' ) || !TCW::get_option( 'theme_toolbar' ) || thf_is_plugin_options_page() ) {
        return;
    }

    // Add the main menu item
    $wp_admin_bar->add_menu( [
        'id'    => 'thf-adminbar-panel',
        'title' => '<span class="ab-icon"><img src="' . TCW_ADMIN_URL . '/assets/images/thf.png" alt=""></span>' . apply_filters( 'Themesfa/plugin_name', 'Themesfa' ),
        'href'  => add_query_arg( [ 'page' => 'thf-plugin-options' ], admin_url( 'admin.php' ) ),
    ] );

    $wp_admin_bar->add_menu( [
        'parent' => 'thf-adminbar-panel',
        'id'     => 'thf-adminbar-options',
        'title'  => '<span class="dashicons-before dashicons-admin-generic"></span> ' . esc_html__( 'Theme Options', TCW_TEXTDOMAIN ),
        'href'   => add_query_arg( [ 'page' => 'thf-plugin-options' ], admin_url( 'admin.php' ) ),
    ] );

    // Sub Menu
    $settings_tabs = apply_filters( 'Themesfa/options_tab_title', '' );

    foreach ( $settings_tabs as $tab_id => $tab_data ) {

        $wp_admin_bar->add_menu( [
            'parent' => 'thf-adminbar-options',
            'id'     => $tab_id,
            'title'  => '<span class="dashicons-before dashicons-' . $tab_data['icon'] . '"></span> ' . $tab_data['title'],
            'href'   => add_query_arg( [ 'page' => 'thf-plugin-options#thf-options-tab-' . $tab_id . '-target' ], admin_url( 'admin.php' ) ),
        ] );
    }


    // Style the icons
    echo '
		<style>
            #wp-admin-bar-thf-adminbar-panel-default li > a {
                display: flex!important;
                align-items: center;
            }
		
		    #wp-admin-bar-thf-adminbar-panel-default li > a > .dashicons-before{
		        margin-left: 0;
		        margin-right: 5px;
		    }
		    
		    .rtl #wp-admin-bar-thf-adminbar-panel-default li > a > .dashicons-before{
		        margin-left: 5px;
		        margin-right: 0;
		    }
		
			#wp-admin-bar-thf-adminbar-panel .ab-icon img{
				max-width: 17px;
				height: auto;
			}

			#wp-admin-bar-thf-adminbar-panel .dashicons-before:before {
				font-size: 14px;
				vertical-align: middle;
			}
			
			#wp-admin-bar-thf-adminbar-panel .dashicons-woocommerce:before {
                font-family: WooCommerce !important;
                content: "\e03d";                
            }
            
			#wpadminbar ul li#wp-admin-bar-thf-adminbar-activate-theme a{
				color: #f44336 !important;
			}					
		</style>
	';

}


/**
 * Rturn if current page is not ADMIN
 */
if ( !is_admin() ) {
    return;
}


/*-----------------------------------------------------------------------------------*/
# Include the requried files
/*-----------------------------------------------------------------------------------*/
require_once TCW_ADMIN_PATH . '/plugin-options.php';
require_once TCW_ADMIN_PATH . '/classes/class-thf-system-status.php';
require_once TCW_ADMIN_PATH . '/framework-options.php';


/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_enqueue_scripts', 'thf_admin_enqueue_scripts' );
function thf_admin_enqueue_scripts() {

    # Enqueue dashboard scripts and styles
    wp_enqueue_script( 'thf-admin-scripts', TCW_ADMIN_URL . '/assets/thf.js', [
        'jquery',
        'jquery-ui-sortable',
        'jquery-ui-draggable',
        'wp-color-picker',
    ], TCW_VER, false );
    wp_enqueue_style( 'thf-admin-style', TCW_ADMIN_URL . '/assets/style.css', [], TCW_VER, 'all' );
    wp_enqueue_style( 'thf-fontawesome', TCW_URL . '/assets/fonts/fontawesome/font-awesome.min.css', [], TCW_VER, 'all' );
    wp_enqueue_style( 'wp-color-picker' );

    $thf_lang = [
        'update' => esc_html__( 'Update', TCW_TEXTDOMAIN ),
        'search' => esc_html__( 'Search', TCW_TEXTDOMAIN ),
    ];
    wp_localize_script( 'thf-admin-scripts', 'thfLang', $thf_lang );


    # The Insert Post Ads plugin uses Chart.js library which causes conflict with the Iris color picker used by WordPress
    # More info here https://github.com/chartjs/Chart.js/issues/3168
    if ( class_exists( 'InsertPostAds' ) ) {
        wp_dequeue_script( 'insert-post-adschart-admin' );
    }

}


/*-----------------------------------------------------------------------------------*/
# Code Editor
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_enqueue_scripts', 'thf_admin_code_editor' );
function thf_admin_code_editor() {

    # Check if WP > 4.9 && syntax highlighting is enabled
    if ( !function_exists( 'wp_enqueue_code_editor' ) || ( is_user_logged_in() && 'false' === wp_get_current_user()->syntax_highlighting ) ) {

        return;
    }


    # Theme Options Page
    if ( thf_is_plugin_options_page() ) {

        $elements = [
            'header_code' => 'text/html',
            'footer_code' => 'text/html',

            'css'         => 'text/css',
            'css_tablets' => 'text/css',
            'css_phones'  => 'text/css',
        ];
    }


    # Check if there is elements
    if ( empty( $elements ) ) {

        return;
    }


    # Prepare the output
    $out = '
		jQuery( function() {';

    foreach ( $elements as $ele => $type ) {

        $settings = wp_enqueue_code_editor( [ 'type' => $type, 'codemirror' => [ 'indentUnit' => 2, 'tabSize' => 2 ] ] );

        $out .= '
					if( jQuery("#' . $ele . '").length ){
						var ' . $ele . ' = wp.codeEditor.initialize( "' . $ele . '", ' . wp_json_encode( $settings ) . ' );
						';

        # Only in the theme options page
        if ( thf_is_plugin_options_page() ) {
            $out .= $ele . '.codemirror.on("change",function(cMirror){
								jQuery("#' . $ele . '").val( cMirror.getValue() );
							});';
        }

        $out .= '
					}
				';
    }

    $out .= '
		});
	';

    //  jQuery("#$_'.$ele.'").value = cMirror.getValue();

    # Add the inline code
    wp_add_inline_script( 'code-editor', $out );
}


/*-----------------------------------------------------------------------------------*/
# Install the default theme settings
/*-----------------------------------------------------------------------------------*/
function thf_install_plugin() {

    # Save the default settings
    if ( !get_option( 'thf_ver_' . TCW_PLUGIN_ID ) && !get_option( apply_filters( 'Themesfa/plugin_options', '' ) ) ) {

        # Store the default settings
        $default_data = thf_default_plugin_settings();

        thf_save_plugin_options( $default_data );

        # Store the DB theme's version
        update_option( 'thf_ver_' . TCW_PLUGIN_ID, TCW_VER );

        # Store the data of installing the theme temporarily
        update_option( 'thf_install_date_' . TCW_PLUGIN_ID, time() );
    }


    # Store the total number of puplished posts before Installing our theme
    $count_posts     = wp_count_posts();
    $published_posts = !empty( $count_posts->publish ) ? $count_posts->publish : 0;
    update_option( 'thf_published_posts_' . TCW_PLUGIN_ID, $published_posts, false );

    # Redirect to the Welcome page
    // wp_safe_redirect( add_query_arg( [ 'page' => 'thf-plugin-welcome' ], admin_url( 'admin.php' ) ) );
}


/*-----------------------------------------------------------------------------------*/
# Default theme settings
/*-----------------------------------------------------------------------------------*/
function thf_default_plugin_settings() {

    $default_settings = [
        'thf_options' => [

            'site_width'            => '1200px',

            # General Settings
            'time_format'           => 'modern',
            'time_type'             => 'published',
            'breadcrumbs'           => 'true',
            'breadcrumbs_delimiter' => '&#47;',

            'structure_data'   => 'true',
            'schema_type'      => 'Article',

            # Layout
            'theme_layout'     => 'full',
            'boxes_style'      => 1,
            'loader-icon'      => 1,

            # Advanced
            'thf_post_views'   => 'theme',
            'views_meta_field' => 'thf_views',
            'minified_files'   => 'true',
        ],
    ];

    return $default_settings;
}


/*-----------------------------------------------------------------------------------*/
# Get all categories as array of ID and name
/*-----------------------------------------------------------------------------------*/
function thf_get_all_categories( $label = false ) {

    $categories = [];

    if ( !empty( $label ) ) {
        $categories[] = esc_html__( '- Select a category -', TCW_TEXTDOMAIN );
    }

    $get_categories = get_categories( 'hide_empty=0' );

    if ( !empty( $get_categories ) && is_array( $get_categories ) ) {
        foreach ( $get_categories as $category ) {
            $categories[$category->cat_ID] = $category->cat_name;
        }
    }

    return $categories;
}


/*-----------------------------------------------------------------------------------*/
# Get all menus as array of ID and name
/*-----------------------------------------------------------------------------------*/
function thf_get_all_menus( $label = false ) {

    $menus = [];

    if ( !empty( $label ) ) {
        $menus[] = esc_html__( '- Select a menu -', TCW_TEXTDOMAIN );
    }

    $get_menus = get_terms( [ 'taxonomy' => 'nav_menu', 'hide_empty' => false ] );

    if ( !empty( $get_menus ) ) {
        foreach ( $get_menus as $menu ) {
            $menus[$menu->term_id] = $menu->name;
        }
    }

    return $menus;
}


/*-----------------------------------------------------------------------------------*/
# Get all WooCommerce categories as array of ID and name
/*-----------------------------------------------------------------------------------*/
function thf_get_all_products_categories( $label = false ) {

    if ( !THF_WOOCOMMERCE_IS_ACTIVE ) {
        return;
    }

    $categories = [];

    if ( !empty( $label ) ) {
        $categories = [ '' => esc_html__( '- Select a category -', TCW_TEXTDOMAIN ) ];
    }

    $get_categories = get_categories( [ 'hide_empty' => 0, 'taxonomy' => 'product_cat' ] );

    if ( !empty( $get_categories ) && is_array( $get_categories ) ) {
        foreach ( $get_categories as $category ) {
            $categories[$category->cat_ID] = $category->cat_name;
        }
    }

    return $categories;
}


/*-----------------------------------------------------------------------------------*/
# Get Current Theme version
/*-----------------------------------------------------------------------------------*/
function thf_get_current_version() {

    return ( TCW_VER ) ? TCW_VER : false;
}


/*-----------------------------------------------------------------------------------*/
# Remove Empty values from the Multi Dim Arrays
/*-----------------------------------------------------------------------------------*/
function thf_array_filter_recursive( $input ) {

    foreach ( $input as &$value ) {

        if ( is_array( $value ) ) {
            $value = thf_array_filter_recursive( $value );
        }
    }

    return array_filter( $input );
}


/*-----------------------------------------------------------------------------------*/
# Prune the WP Super Cache.
/*-----------------------------------------------------------------------------------*/
add_action( 'Themesfa/Options/updated', 'thf_clear_super_cache' );
function thf_clear_super_cache() {

    if ( function_exists( 'prune_super_cache' ) ) {
        global $cache_path;

        prune_super_cache( $cache_path . 'supercache/', true );
        prune_super_cache( $cache_path, true );
    }
}


/*-----------------------------------------------------------------------------------*/
# Changing the base and the ID of the theme options page to Widgets!!!!
# We use this method to force the plugins to load their JS files so we be able
# to store them every time the user access the theme options page to be used later
# in the Page builder.
#
# Added: thfbase: so we can check if this is the theme options page.
/*-----------------------------------------------------------------------------------*/
add_action( 'load-toplevel_page_thf-plugin-options', 'thf_plugin_pages_screen_data', 99 );
add_action( 'load-thfplugin_page_thf-system-status', 'thf_plugin_pages_screen_data', 99 );
function thf_plugin_pages_screen_data() {
    global $current_screen;

    $current_screen->base    = 'widgets';
    $current_screen->id      = 'widgets';
    $current_screen->thfbase = str_replace( 'load-', '', current_filter() );
}


/*-----------------------------------------------------------------------------------*/
# Check if the current page is the theme options
/*-----------------------------------------------------------------------------------*/
function thf_is_plugin_options_page() {

    $current_page = !empty( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
    return $current_page == 'thf-plugin-options';
}