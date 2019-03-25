<?php
/**
 * Plugin Options
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


/*-----------------------------------------------------------------------------------*/
# Save Theme Settings
/*-----------------------------------------------------------------------------------*/
function thf_save_plugin_options( $data, $refresh = 0 ) {

    if ( !empty( $data['thf_options'] ) ) {

        // Prepare the data
        do_action( 'Themesfa/Options/before_update', $data['thf_options'] );

        // Remove all empty keys
        $data['thf_options'] = thf_array_filter_recursive( $data['thf_options'] );

        // Remove the Logo Text if it is the same as the site title
        if ( !empty( $data['thf_options']['logo_text'] ) && $data['thf_options']['logo_text'] == get_bloginfo() ) {
            unset( $data['thf_options']['logo_text'] );
        }

        // Save the settings
        update_option( apply_filters( 'Themesfa/plugin_options', '' ), $data['thf_options'] );

        // WPML
        if ( !empty( $data['thf_options']['breaking_custom'] ) && is_array( $data['thf_options']['breaking_custom'] ) ) {
            $count = 0;

            foreach ( $data['thf_options']['breaking_custom'] as $custom_text ) {
                $count++;

                if ( !empty( $custom_text['text'] ) ) {
                    do_action( 'wpml_register_single_string', TCW_PLUGIN_SLUG, 'Breaking News Custom Text #' . $count, $custom_text['text'] );
                }

                if ( !empty( $custom_text['link'] ) ) {
                    do_action( 'wpml_register_single_string', TCW_PLUGIN_SLUG, 'Breaking News Custom Link #' . $count, $custom_text['link'] );
                }
            }
        }
    }

    # After updating the plugin options action
    do_action( 'Themesfa/Options/updated' );

    # Refresh the page?
    $refresh = apply_filters( 'Themesfa/options_refresh', $refresh );

    if ( !empty( $refresh ) ) {
        echo esc_html( $refresh );
        die();
    }
}


/*-----------------------------------------------------------------------------------*/
# Save Options
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_thf_plugin_data_save', 'thf_save_plugin_options_ajax' );
function thf_save_plugin_options_ajax() {

    check_ajax_referer( 'thf-plugin-data', 'thf-security' );

    $data    = stripslashes_deep( $_POST );
    $refresh = 1;

    if ( !empty( $data['thf_import'] ) ) {
        $refresh = 2;
        $data    = maybe_unserialize( $data['thf_import'] );
    }

    thf_save_plugin_options( $data, $refresh );
}


/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_menu', 'thf_admin_menus' );
function thf_admin_menus() {

    # Add the main theme settings page
    $icon = TCW_ADMIN_URL . '/assets/images/thf.png';

    add_menu_page( $page_title = apply_filters( 'Themesfa/plugin_name', 'Themesfa' ), $menu_title = 'thfplugin', $capability = 'switch_themes', $menu_slug = 'thf-plugin-options', $function = 'thf_show_plugin_options', $icon_url = $icon, $position = 100 );

    # Add Sub menus
    $theme_submenus = array(
        array(
            'page_title' => esc_html__( 'Plugin Options', TCW_TEXTDOMAIN ),
            'menu_title' => esc_html__( 'Plugin Options', TCW_TEXTDOMAIN ),
            'menu_slug'  => 'thf-plugin-options',
            'function'   => 'thf_show_plugin_options',
        ),
    );

    $theme_submenus = apply_filters( 'Themesfa/panel_submenus', $theme_submenus );

    foreach ( $theme_submenus as $submenu ) {
        add_submenu_page( $parent_slug = 'thf-plugin-options', $page_title = $submenu['page_title'], $menu_title = $submenu['menu_title'], $capability = 'switch_themes', $menu_slug = $submenu['menu_slug'], $function = $submenu['function'] );
    }


    if ( thf_is_plugin_options_page() ) {

        # Reset settings
        if ( isset( $_REQUEST['reset-settings'] ) && check_admin_referer( 'reset-plugin-settings', 'reset_nonce' ) ) {

            $default_data = thf_default_theme_settings();
            thf_save_plugin_options( $default_data );

            # Redirect to the plugin options page
            wp_safe_redirect( add_query_arg( array( 'page' => 'thf-plugin-options', 'reset' => 'true' ), admin_url( 'admin.php' ) ) );
            exit;
        } # Export Settings
        elseif ( isset( $_REQUEST['export-settings'] ) && check_admin_referer( 'export-plugin-settings', 'export_nonce' ) ) {

            global $wpdb;

            $stored_options = $wpdb->get_results( $wpdb->prepare( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name = '%s'", apply_filters( 'Themesfa/plugin_options', '' ) ) );

            header( 'Cache-Control: public, must-revalidate' );
            header( 'Pragma: hack' );
            header( 'Content-Type: text/plain' );
            header( 'Content-Disposition: attachment; filename="' . TCW_PLUGIN_SLUG . '-options-' . date( "dMy" ) . '.dat"' );
            echo serialize( $stored_options );
            die();
        } # Import the settings
        elseif ( isset( $_FILES['thf_import_file'] ) && check_admin_referer( 'thf-plugin-data', 'thf-security' ) ) {
            if ( $_FILES['thf_import_file']['error'] > 0 ) {
                // error
            } else {
                $options = unserialize( file_get_contents( $_FILES['thf_import_file']['tmp_name'] ) );

                if ( !empty( $options ) && is_array( $options ) ) {
                    foreach ( $options as $option ) {
                        update_option( $option->option_name, unserialize( $option->option_value ) );
                    }
                }
            }

            wp_safe_redirect( add_query_arg( array( 'page' => 'thf-plugin-options', 'import' => 'true' ), admin_url( 'admin.php' ) ) );
            exit;
        }


    }
}


/*-----------------------------------------------------------------------------------*/
# Get The Panel Options
/*-----------------------------------------------------------------------------------*/
function thf_build_plugin_option( $value ) {
    $data = false;

    if ( empty( $value['id'] ) ) {
        $value['id'] = ' ';
    }

    if ( TCW::get_option( $value['id'] ) ) {
        $data = TCW::get_option( $value['id'] );
    }

    thf_build_option( $value, 'thf_options[' . $value["id"] . ']', $data );
}


/*-----------------------------------------------------------------------------------*/
# Save button
/*-----------------------------------------------------------------------------------*/
add_action( 'Themesfa/save_button', 'thf_save_options_button' );
function thf_save_options_button() { ?>

    <div class="thf-panel-submit">
        <button name="save" class="thf-save-button thf-primary-button button button-primary button-hero" type="submit"><?php esc_html_e( 'Save Changes', TCW_TEXTDOMAIN ) ?></button>
    </div>
    <?php
}


/*-----------------------------------------------------------------------------------*/
# The Panel UI
/*-----------------------------------------------------------------------------------*/
function thf_show_plugin_options() {

    wp_enqueue_media();

    $settings_tabs = apply_filters( 'Themesfa/options_tab_title', '' );

    ?>

    <div id="thf-page-overlay"></div>

    <div id="thf-saving-settings">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            <path class="checkmark__error_1" d="M38 38 L16 16 Z"/>
            <path class="checkmark__error_2" d="M16 38 38 16 Z"/>
        </svg>
    </div>

    <?php do_action( 'Themesfa/before_theme_panel' ); ?>

    <div class="thf-panel">

        <div class="thf-panel-tabs">
            <a href="http://avin-tarh.ir/" target="_blank" class="thf-logo">
                <img class="thf-logo-normal" src="<?php echo TCW_ADMIN_URL . '/assets/images/thf-logo.png' ?>" alt="<?php esc_html_e( 'Themesfa', TCW_TEXTDOMAIN ) ?>"/>
                <img class="thf-logo-mini" src="<?php echo TCW_ADMIN_URL . '/assets/images/thf-logo-mini.png' ?>" alt="<?php esc_html_e( 'Themesfa', TCW_TEXTDOMAIN ) ?>"/>
            </a>

            <?= thf_plugin_options_loop_settings_tabs( $settings_tabs ); ?>

            <div class="clear"></div>
        </div> <!-- .thf-panel-tabs -->

        <div class="thf-panel-content">

            <div id="plugin-options-search-wrap">
                <input id="theme-panel-search" type="text" placeholder="<?php esc_html_e( 'Search', TCW_TEXTDOMAIN ) ?>">
                <div id="theme-search-list-wrap" class="has-custom-scroll">
                    <ul id="theme-search-list"></ul>
                </div>
            </div>


            <form method="post" name="thf_form" id="thf_form" enctype="multipart/form-data">
                <?= thf_plugin_options_loop_settings_tabs_wrap( $settings_tabs ); ?>

                <?php wp_nonce_field( 'thf-plugin-data', 'thf-security' ); ?>
                <input type="hidden" name="action" value="thf_plugin_data_save"/>

                <div class="thf-footer">
                    <?php do_action( 'Themesfa/save_button' ); ?>
                </div>

            </form>

        </div><!-- .thf-panel-content -->
        <div class="clear"></div>

    </div><!-- .thf-panel -->
    <?php

}

function thf_plugin_options_loop_settings_tabs( $settings_tabs, $parent = "", $depth = 0 ) {
    $parentClass = $depth !== 0 ? ' class="submenu"' : '';

    $output = "<ul$parentClass>";

    foreach ( $settings_tabs as $tab => $settings ) {
        $tab   = $parent ? $parent . '-' . $tab : $tab;
        $class = $submenu = "";
        $icon  = $settings['icon'];
        $title = $settings['title'];

        // if ( isset( $settings['submenu'] ) ) {
        //     $class   = "has-children";
        //     $submenu = call_user_func( __FUNCTION__, $settings['submenu'], $tab, $depth + 1 );
        // }

        $output .= "<li class=\"thf-tabs thf-options-tab-$tab $class\">";
        $output .= "    <a href=\"#thf-options-tab-$tab\">";
        $output .= "        <span class=\"dashicons-before dashicons-$icon thf-icon-menu\"></span>";
        $output .= "        " . $title;
        $output .= "    </a>";
        $output .= "    " . $submenu;
        $output .= "</li>";

    }

    $output .= "</ul>";

    return $output;
}

function thf_plugin_options_loop_settings_tabs_wrap( $settings_tabs, $parent = "", $depth = 0 ) {
    $output = "";

    foreach ( $settings_tabs as $tab => $settings ) {
        $tab     = $parent ? $parent . '-' . $tab : $tab;
        $path    = TCW_ADMIN_PATH . '/plugin-options/' . $tab . '.php';
        $content = $submenu = "";

        if ( isset( $settings['submenu'] ) ) {
            $submenu = call_user_func( __FUNCTION__, $settings['submenu'], $tab, $depth + 1 );
        }

        ob_start();
        if ( file_exists( $path ) ) {
            require_once $path;
        }
        do_action( 'thf_plugin_options_tab_' . $tab );
        $content = ob_get_clean();

        $output .= "<!-- $tab Settings -->";
        $output .= "<div id=\"thf-options-tab-$tab\" class=\"tabs-wrap\">";
        $output .= "    " . $content;
        $output .= "</div>";
        $output .= $submenu;

    }

    return $output;
}


/*-----------------------------------------------------------------------------------*/
# Share buttons
/*-----------------------------------------------------------------------------------*/
function thf_get_share_buttons_options( $share_position = '' ) {

    $position = !empty( $share_position ) ? '_' . $share_position : '';

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Twitter', TCW_TEXTDOMAIN ),
        'id'   => 'share_twitter' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Facebook', TCW_TEXTDOMAIN ),
        'id'   => 'share_facebook' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Google+', TCW_TEXTDOMAIN ),
        'id'   => 'share_google' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'LinkedIn', TCW_TEXTDOMAIN ),
        'id'   => 'share_linkedin' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'StumbleUpon', TCW_TEXTDOMAIN ),
        'id'   => 'share_stumbleupon' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Pinterest', TCW_TEXTDOMAIN ),
        'id'   => 'share_pinterest' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Reddit', TCW_TEXTDOMAIN ),
        'id'   => 'share_reddit' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Tumblr', TCW_TEXTDOMAIN ),
        'id'   => 'share_tumblr' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'VKontakte', TCW_TEXTDOMAIN ),
        'id'   => 'share_vk' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Odnoklassniki', TCW_TEXTDOMAIN ),
        'id'   => 'share_odnoklassniki' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Pocket', TCW_TEXTDOMAIN ),
        'id'   => 'share_pocket' . $position,
        'type' => 'checkbox',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'WhatsApp', TCW_TEXTDOMAIN ),
        'id'   => 'share_whatsapp' . $position,
        'type' => 'checkbox',
        'hint' => ( $share_position != 'mobile' ) ? esc_html__( 'For Mobiles Only', TCW_TEXTDOMAIN ) : '',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Telegram', TCW_TEXTDOMAIN ),
        'id'   => 'share_telegram' . $position,
        'type' => 'checkbox',
        'hint' => ( $share_position != 'mobile' ) ? esc_html__( 'For Mobiles Only', TCW_TEXTDOMAIN ) : '',
    ) );

    thf_build_plugin_option( array(
        'name' => esc_html__( 'Viber', TCW_TEXTDOMAIN ),
        'id'   => 'share_viber' . $position,
        'type' => 'checkbox',
        'hint' => ( $share_position != 'mobile' ) ? esc_html__( 'For Mobiles Only', TCW_TEXTDOMAIN ) : '',
    ) );

    if ( $share_position != 'mobile' ) {
        thf_build_plugin_option( array(
            'name' => esc_html__( 'Email', TCW_TEXTDOMAIN ),
            'id'   => 'share_email' . $position,
            'type' => 'checkbox',
        ) );

        thf_build_plugin_option( array(
            'name' => esc_html__( 'Print', TCW_TEXTDOMAIN ),
            'id'   => 'share_print' . $position,
            'type' => 'checkbox',
        ) );
    }

}


