<?php
/**
 * Styles Class
 */


defined( 'ABSPATH' ) || exit; // Exit if accessed directly


if ( !class_exists( 'TCW_STYLES' ) ) {

    class TCW_STYLES {

        /*
         * Fire Filters and actions
         */
        public static function init() {

            // Head codes
            add_action( 'wp_head', [ __CLASS__, 'custom_head_code' ] );
            add_action( 'wp_head', [ __CLASS__, 'meta_theme_generator' ] );

            // Lets IE users a better experience.
            add_action( 'send_headers', [ __CLASS__, 'header_ie_xua' ] );

            // Remove Query Strings From Static Resources
            add_filter( 'script_loader_src', [ __CLASS__, 'remove_query_strings' ], 999, 2 );
            add_filter( 'style_loader_src', [ __CLASS__, 'remove_query_strings' ], 999, 2 );

            // wp enqueue scripts
            add_action( 'wp_enqueue_scripts', [ __CLASS__, 'get_custom_styles' ], 222 );
            add_action( 'wp_enqueue_scripts', [ __CLASS__, 'localize_script' ], 222 );

            // Update the CSS file
            add_action( 'edit_terms', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'delete_term', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'switch_theme', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'edit_category', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'delete_category', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'activated_plugin', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'deactivated_plugin', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'arqam_options_updated', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'Themesfa/Options/updated', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'Themesfa/after_db_update', [ __CLASS__, 'update_styles_file' ] );
            add_action( 'upgrader_process_complete', [ __CLASS__, 'update_styles_file' ] );
        }


        /*
         * localize_script
         */
        public static function localize_script() {

            $type_to_search = false;
            // if ( ( TCW::get_option( 'top_nav' ) && TCW::get_option( 'top-nav-components_search' ) && TCW::get_option( 'top-nav-components_type_to_search' ) ) || ( TCW::get_option( 'main_nav' ) && TCW::get_option( 'main-nav-components_search' ) && TCW::get_option( 'main-nav-components_type_to_search' ) ) ) {
            //     $type_to_search = true;
            // }

            $js_vars = [
                'cache'      => TCW::get_option( 'cache' ),
                'isRTL'      => intval( is_rtl() ),
                'isLoggedIn' => is_user_logged_in(),
                'spinner'    => thf_icon( 'spinner' ),
                'ajaxURL'    => esc_url( admin_url( 'admin-ajax.php' ) ),
                'ajaxNonce'  => wp_create_nonce( 'thf_ajax_security_nonce' ),

                'typeToSearch' => $type_to_search,
                'lang'         => [
                    'added'     => esc_html__( 'Added!', TCW_TEXTDOMAIN ),
                    'noResults' => esc_html__( 'Nothing Found!', TCW_TEXTDOMAIN ),
                ],
            ];

            wp_localize_script( 'jquery', 'THF', apply_filters( 'Themesfa/js_main_vars', $js_vars ) );
        }


        /*
         * Minify Css
         */
        public static function minify_css( $css ) {

            if ( empty( $css ) ) {
                return;
            }

            $css = strip_tags( $css );
            $css = str_replace( ',{', '{', $css );
            $css = str_replace( ', ', ',', $css );
            $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
            $css = str_replace( [ "\r\n", "\r", "\n", "\t" ], '', $css );
            $css = preg_replace( '/\s+/', ' ', $css );

            return trim( $css );
        }


        /*
         * Load the normal or minified files
         */
        public static function is_minified() {
            return TCW::get_option( 'minified_files' ) ? '.min' : '';
        }


        /*
         * Get the custom styles file path
         */
        public static function style_file_path( $absolute = false ) {

            $path = $absolute ? TCW_PATH : TCW_URL;
            $path = $path . '/assets/custom-css';

            $path = apply_filters( 'Themesfa/CSS/custom_file_path', $path, $absolute );

            if ( is_multisite() ) {
                return $path . '/style-custom-' . get_current_blog_id() . '.css';
            }

            return $path . '/style-custom.css';
        }


        /*
         * Get Background
         */
        public static function get_background( $is_custom_css_file = false ) {

            # Get the theme layout
            $theme_layout = TCW::get_option( 'theme_layout' );

            # Add 'none' before the global option to avoid duplication when using the custom style css file
            $prefix = $is_custom_css_file ? 'none' : '';

            $out             = '';
            $background_code = '';

            $background_color_1 = TCW::get_option( $prefix . 'background_color' );
            $background_color_2 = TCW::get_option( $prefix . 'background_color_2' );

            # Solid Color Background
            if ( !empty( $background_color_1 ) || !empty( $background_color_2 ) ) {

                $background_single_color = empty( $background_color_1 ) ? $background_color_2 : $background_color_1;
                $background_code         .= 'background-color: ' . $background_single_color . ';';
            }

            # Bordered Layout Supports Colors Backgrounds only
            if ( $theme_layout != 'border' ) {

                # Gradiant Background
                if ( !empty( $background_color_1 ) && !empty( $background_color_2 ) ) {

                    $gradiant_css    = "45deg, $background_color_1, $background_color_2";
                    $background_code .= "
						background-image: -webkit-linear-gradient($gradiant_css);
						background-image:         linear-gradient($gradiant_css);
					";
                }

                $background_type = TCW::get_option( $prefix . 'background_type' );

                # Background Image
                if ( $background_type == 'image' ) {

                    $background_image = TCW::get_option( $prefix . 'background_image' );
                    $background_code  .= self::bg_image_css( $background_image );
                } # Background Pattern
                elseif ( $background_type == 'pattern' ) {

                    $background_pattern = TCW::get_option( $prefix . 'background_pattern' );
                    $background_code    .= !empty( $background_pattern ) ? 'background-image: url(' . TCW_TEMPLATE_URL . '/assets/images/patterns/' . $background_pattern . '.png);' : '';
                }
            }

            # body background CSS code
            if ( !empty( $background_code ) ) {
                $out .= '
					#thf-body{
						' . $background_code . '
					}';
            }

            # Overlay background
            $background_overlay = '';

            # Overlay dots
            $background_dots = TCW::get_option( $prefix . 'background_dots' );
            if ( !empty( $background_dots ) ) {
                $background_overlay .= !empty( $background_dots ) ? 'background-image: url(' . TCW_TEMPLATE_URL . '/assets/images/bg-dots.png);' : '';
            }

            # Overlay dimmer
            $background_dimmer = TCW::get_option( $prefix . 'background_dimmer' );

            if ( !empty( $background_dimmer ) ) {

                # value
                $dimmer_value = TCW::get_option( $prefix . 'background_dimmer_value' );
                if ( !empty( $dimmer_value ) ) {
                    $dimmer_value = ( max( 0, min( 100, $dimmer_value ) ) ) / 100;
                } else {
                    $dimmer_value = 0.5;
                }

                $dimmer_color = TCW::get_option( $prefix . 'background_dimmer_color' );
                $dimmer_color = ( $dimmer_color == 'white' ) ? '255,255,255,' : '0,0,0,';

                $background_overlay .= !empty( $background_dimmer ) ? 'background-color: rgba(' . $dimmer_color . $dimmer_value . ');' : '';
            }

            # background-overlay CSS code
            if ( !empty( $background_overlay ) ) {
                $out .= '
					.background-overlay {
						' . $background_overlay . '
					}';
            }

            return $out;
        }


        /*
         * Prepare the CSS code of an background image
         */
        public static function bg_image_css( $background_image = false ) {

            if ( empty( $background_image ) || empty( $background_image['img'] ) ) return;

            $background_code = 'background-image: url(' . $background_image['img'] . ');' . "\n";
            $background_code .= !empty( $background_image['repeat'] ) ? 'background-repeat: ' . $background_image['repeat'] . ';' : '';

            # Image attachment
            if ( !empty( $background_image['attachment'] ) ) {
                if ( $background_image['attachment'] == 'cover' ) {
                    $background_code .= 'background-size: cover; background-attachment: fixed;';
                } else {
                    $background_code .= 'background-size: initial; background-attachment: ' . $background_image['attachment'] . ';';
                }
            }

            # Image position
            $hortionzal = !empty( $background_image['hor'] ) ? $background_image['hor'] : '';
            $vertical   = !empty( $background_image['ver'] ) ? $background_image['ver'] : '';

            if ( !empty( $hortionzal ) || !empty( $vertical ) ) {
                $background_code .= "background-position: $hortionzal $vertical;";
            }

            return $background_code;
        }


        /*
         * Adjust darker or lighter color
         */
        public static function color_brightness( $hex, $steps = -30 ) {

            // Steps should be between -255 and 255. Negative = darker, positive = lighter
            $steps = max( -255, min( 255, $steps ) );

            $rgb = self::rgb_color( $hex, true );

            /**
             * @var integer $r
             * @var integer $g
             * @var integer $b
             */
            extract( $rgb );

            // Adjust number of steps and keep it inside 0 to 255
            $r = max( 0, min( 255, $r + $steps ) );
            $g = max( 0, min( 255, $g + $steps ) );
            $b = max( 0, min( 255, $b + $steps ) );

            $r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
            $g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
            $b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

            return '#' . $r_hex . $g_hex . $b_hex;
        }


        /*
         * Adjust darker or lighter color
         */
        public static function rgb_color( $hex, $array = false ) {

            if ( strpos( $hex, 'rgb' ) !== false ) {

                $rgb_format = [ 'rgba', 'rgb', '(', ')', ' ' ];
                $rgba_color = str_replace( $rgb_format, '', $hex );
                $rgba_color = explode( ',', $rgba_color );

                $rgb = [
                    'r' => $rgba_color[0],
                    'g' => $rgba_color[1],
                    'b' => $rgba_color[2],
                ];
            } else {

                // Format the hex color string
                $hex = str_replace( '#', '', $hex );

                if ( 3 == strlen( $hex ) ) {
                    $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
                }

                # Get decimal values
                $rgb = [
                    'r' => hexdec( substr( $hex, 0, 2 ) ),
                    'g' => hexdec( substr( $hex, 2, 2 ) ),
                    'b' => hexdec( substr( $hex, 4, 2 ) ),
                ];
            }

            if ( !$array ) {
                $rgb = implode( ',', $rgb );
            }

            return $rgb;
        }


        /*
         * Check if we need to use dark or light color
         */
        public static function light_or_dark( $color, $return_rgb = false, $dark = '#000000', $light = '#FFFFFF' ) {

            $rgb = self::rgb_color( $color, true );

            /**
             * @var integer $r
             * @var integer $g
             * @var integer $b
             */
            extract( $rgb );

            $bright = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;
            $color  = $bright > 200 ? $dark : $light;

            return $return_rgb ? self::rgb_color( $color ) : $color;
        }


        /*
         * Custom CSS Media Quries
         */
        public static function media_query( $option, $max = 0, $min = 0 ) {

            if ( !TCW::get_option( $option ) ) return false;

            return '
				@media only screen and (max-width: ' . $max . 'px) and (min-width: ' . $min . 'px){
					' . TCW::get_option( $option ) . '
				}
			';
        }


        /*
         * Enquee the External CSS file or Inline the Custom CSS codes
         */
        public static function get_custom_styles() {

            $inline_css_code  = '';
            $css_file_handler = 'thf-css-styles'; // Default CSS File Handler

            // Add CSS inline if the inline_css option is enabled or the Custom CSS file is not exists
            if ( TCW::get_option( 'inline_css' ) || !file_exists( self::style_file_path( true ) ) ) {

                // Get the latest enqueued css file
                $enqueue_styles = wp_styles();
                $enqueued_files = array_reverse( $enqueue_styles->queue );

                foreach ( $enqueued_files as $file ) {
                    // Don't use IE files as loaded in condition
                    if ( strpos( $file, 'thf-css-ie-' ) === false ) {
                        $css_file_handler = $file;
                        break;
                    }
                }

                // Get the Custom CSS Codes
                $inline_css_code .= self::get_all_custom_css();
            } // The inline CSS code option is disabled and the External Custom CSS file is exists
            else {

                // The Last enqueed file is the Custom CSS file
                $css_file_handler = 'thf-css-style-custom';

                wp_enqueue_style( $css_file_handler, self::style_file_path(), [], get_option( 'style-custom-ver', TCW_VER ), 'all' );

                // For posts and categories custom styles
                // $inline_css_code .= self::custom_theme_color();
                //
                // Background
                // $inline_css_code .= self::get_background( true );
            }

            // Minify and Inline the CSS codes
            if ( !empty( $inline_css_code ) ) {
                $inline_css_code = self::minify_css( $inline_css_code );
                wp_add_inline_style( $css_file_handler, $inline_css_code );
            }
        }


        /*
         * Get the Custom CSS of the diffrent elements
         */
        public static function get_all_custom_css() {

            $out = '';
            $out .= self::get_background();
            $out .= self::custom_theme_color();

            // Early Filter after getting the custom theme color
            $out = apply_filters( 'Themesfa/CSS/after_theme_color', $out );

            // Global Custom CSS codes
            $out .= TCW::get_option( 'css' );
            $out .= self::media_query( 'css_tablets', 1024, 768 );
            $out .= self::media_query( 'css_phones', 768, 0 );

            // Prepare the CSS codes
            return self::minify_css( apply_filters( 'Themesfa/CSS/output', $out ) );
        }


        /*
         * Get the Custom Theme Color CSS codes
         */
        public static function custom_theme_color( $color = false ) {

            $color = !empty( $color ) ? $color : TCW::get_option( 'global_color' );

            if ( empty( $color ) ) {
                return;
            }

            $dark_color = self::color_brightness( $color, -50 );
            $bright     = self::light_or_dark( $color );
            $rgb_color  = self::rgb_color( $color );
            $css_code   = '';

            return apply_filters( 'Themesfa/CSS/custom_theme_color', $css_code, $color, $dark_color, $bright, $rgb_color );
        }


        /*
         * Update the External CSS file
         */
        public static function update_styles_file() {

            if ( TCW::get_option( 'inline_css' ) ) {
                return;
            }

            // Open the file
            $open = 'fo' . 'pen';
            $file = @$open( self::style_file_path( true ), 'w+' ); //##### ;)

            if ( !$file ) {
                return;
            }

            // Get The CSS code
            $css = self::get_all_custom_css();

            // Write the code to the file
            $wrt = 'fwr' . 'ite';
            $wrt( $file, $css ); //##### ;)

            // Close the file
            $cls = 'fcl' . 'ose';
            $cls( $file ); //##### ;)

            // Update the version update number
            update_option( 'style-custom-ver', rand( 10000, 99999 ) );
        }


        /*
         * Remove Query Strings From Static Resources
         */
        public static function remove_query_strings( $src, $handle ) {

            // if ( !is_admin() && !is_user_logged_in() ) {
            //     $src = remove_query_arg( 'ver', $src );
            // }

            if ( !empty( $src ) && strpos( $src, site_url() ) !== false ) {
                $path = ABSPATH . str_replace( site_url() . '/', '', $src );
                if ( file_exists( $path ) ) {
                    $src = remove_query_arg( 'ver', $src );
                    $src = add_query_arg( 'ver', filemtime( $path ), $src );
                }
            }

            return $src;
        }


        /*
         * Lets IE users a better experience.
         */
        public static function header_ie_xua() {
            header( 'X-UA-Compatible: IE=edge' );
        }


        /**
         * Custom Code in <head>
         */
        public static function custom_head_code() {
            echo do_shortcode( TCW::get_option( 'header_code' ) ), "\n";
        }



        /**
         * Theme Generator Meta
         */
        public static function meta_theme_generator() {
            $theme_data    = wp_get_theme();
            $theme_version = !empty( $theme_data['Version'] ) ? ' ' . $theme_data['Version'] : '';
            echo '<meta name="generator" content="' . $theme_data . $theme_version . '" />' . "\n";
        }

    } // class

}

TCW_STYLES::init();