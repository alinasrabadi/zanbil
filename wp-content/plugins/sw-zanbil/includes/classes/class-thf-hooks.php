<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class TCW_HOOKS {

    /**
     * The single instance of the class.
     *
     * @var TCW_HOOKS
     * @since 1.0.0
     */
    private static $_instance;

    /**
     * Runs on class initialization. Adds filters and actions.
     */
    function __construct() {

        // Add Support for Shortcodes in the terms descriptions
        add_filter( 'term_description', 'do_shortcode' );

        add_action( 'wp_footer', array( $this, 'footer_inline_scripts' ), 999 );
        add_action( 'wp_footer', array( $this, 'footer_misc' ) );

        add_filter( 'pre_get_posts', array( $this, 'search_filters' ) );
        add_filter( 'Themesfa/cache_key', array( $this, 'cache_key' ) );
        add_filter( 'login_headerurl', array( $this, 'dashboard_login_logo_url' ), 999 );
        add_filter( 'login_head', array( $this, 'dashboard_login_logo' ) );
        add_filter( 'get_the_archive_title', array( $this, 'archive_title' ), 15 );

        add_filter( 'wp_get_attachment_image_src', array( $this, 'gif_image' ), 10, 4 );
    }

    /**
     * Get active instance
     *
     * @since   1.0.0
     * @static
     * @see     tcw_hooks()
     * @return  TCW_HOOKS - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /*
     * Add the inline scripts to the Footer
     */

    function footer_inline_scripts() {

        // Print the inline scripts if the BWP is active
        if ( !empty( $GLOBALS['tie_inline_scripts'] ) && TCW_HELPER::is_js_minified() ) {
            echo '<script type="text/javascript">' . $GLOBALS['tie_inline_scripts'] . '</script>';
        }
    }


    /*
     * Add Misc Footer Conetnt
     */
    function footer_misc() {

        // Custom Footer Code
        if ( TCW::get_option( 'footer_code' ) ) {
            echo do_shortcode( TCW::get_option( 'footer_code' ) );
        }

        // Facebook buttons
        echo '<div id="fb-root"></div>';
    }


    /**
     * Exclude post types and categories From Search results
     */
    function search_filters( $query ) {

        if ( is_admin() || isset( $_GET['post_type'] ) )
            return $query;

        if ( is_search() && $query->is_main_query() ) {

            // Exclude Post types from search
            if ( ( $exclude_post_types = TCW::get_option( 'search_exclude_post_types' ) ) && is_array( $exclude_post_types ) ) {

                $args = array(
                    'public'              => true,
                    'exclude_from_search' => false,
                );

                $post_types = get_post_types( $args );

                foreach ( $exclude_post_types as $post_type ) {
                    unset( $post_types[$post_type] );
                }

                $query->set( 'post_type', $post_types );
            }

            // Exclude specific categoies from search
            if ( TCW::get_option( 'search_cats' ) ) {
                $query->set( 'cat', TCW::get_option( 'search_cats' ) );
            }
        }

        return $query;
    }


    /**
     * Gif images
     */
    function gif_image( $image, $attachment_id, $size, $icon ) {

        if ( !TCW::get_option( 'disable_featured_gif' ) ) {

            $file_type = wp_check_filetype( $image[0] );

            if ( !empty( $file_type ) && $file_type['ext'] == 'gif' && $size != 'full' ) {

                $full_image = wp_get_attachment_image_src( $attachment_id, $size = 'full', $icon );

                # For the avatars we need to keep the original width and height
                if ( !empty( $full_image ) && in_array( 'get_avatar', $GLOBALS['wp_current_filter'] ) ) {
                    $full_image[1] = $image[1];
                    $full_image[2] = $image[2];
                }

                return $full_image;
            }
        }

        return $image;
    }


    /**
     * Global Cache Key
     */
    function cache_key( $key ) {
        return 'tie-cache-' . TCW_HELPER::get_locale() . $key;
    }


    /**
     * Custom Dashboard login URL
     */
    function dashboard_login_logo_url() {

        if ( TCW::get_option( 'dashboard_logo_url' ) ) {
            return TCW::get_option( 'dashboard_logo_url' );
        }
    }


    /*
     * Custom Dashboard login page logo
     */

    function dashboard_login_logo() {

        if ( TCW::get_option( 'dashboard_logo' ) ) {
            echo '<style type="text/css"> .login h1 a {  background-image:url(' . TCW::get_option( 'dashboard_logo' ) . ')  !important; background-size: contain; width: 320px; height: 85px; } </style>';
        }
    }


    /*
     * Remove anything that looks like an archive title prefix ("Archive:", "Foo:", "Bar:").
     */
    function archive_title( $title ) {

        if ( is_category() ) {
            return single_cat_title( '', false );
        } elseif ( is_tag() ) {
            return single_tag_title( '', false );
        } elseif ( is_author() ) {
            return '<span class="vcard">' . get_the_author() . '</span>';
        } elseif ( is_post_type_archive() ) {
            return post_type_archive_title( '', false );
        } elseif ( is_tax() ) {
            return single_term_title( '', false );
        }

        return $title;
    }

}

/**
 * Main instance of TCW_HOOKS.
 * Returns the main instance of TCW_HOOKS to prevent the need to use globals.
 *
 * @since   1.0.0
 * @return  TCW_HOOKS - Main instance
 */
function tcw_hooks() {
    return TCW_HOOKS::instance();
}
