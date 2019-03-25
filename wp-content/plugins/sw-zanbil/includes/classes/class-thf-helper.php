<?php
/**
 * This file contains a bunch of helper functions
 */


defined( 'ABSPATH' ) || exit; // Exit if accessed directly


if ( !class_exists( 'TCW_HELPER' ) ) {

    class TCW_HELPER {


        /**
         * Cehck Ajax Security Nonce
         */
        public static function check_ajax_referer() {
            $check_nonce = check_ajax_referer( 'thf_ajax_security_nonce', 'security', false );

            if ( !$check_nonce || self::is_bot() ) {
                if ( wp_doing_ajax() ) {
                    wp_die( -1, 403 );
                } else {
                    die( '-1' );
                }
            }
        }


        /**
         * WordPress Minifing plugins don't support wp_add_inline_script :(
         */
        public static function inline_script( $handle, $data, $position = 'after' ) {

            if ( empty( $data ) )
                return;

            // Check if there is a Js minification plugin installed
            if ( !self::is_js_minified() ) {
                wp_add_inline_script( $handle, $data, $position );
                return;
            }


            // Make sure the vriable is exists
            if ( empty( $GLOBALS['tie_inline_scripts'] ) ) {
                $GLOBALS['tie_inline_scripts'] = '';
            }

            // Append the new js codes
            $GLOBALS['tie_inline_scripts'] .= $data;
        }


        /**
         * Add support for $args to the template part
         */
        public static function get_template_part( $located, $args = array() ) {

            if ( $args && is_array( $args ) ) {
                extract( $args );
            }

            if ( !empty( $located ) && file_exists( $located ) ) {
                include( $located );
            }
        }


        /**
         * Check if the current request made by a known bot?
         */
        public static function is_bot( $ua = null ) {

            if ( empty( $ua ) ) {
                $ua = $_SERVER['HTTP_USER_AGENT'];
            }

            $bot_agents = array(
                'alexa',
                'altavista',
                'ask jeeves',
                'attentio',
                'baiduspider',
                'bingbot',
                'chtml generic',
                'crawler',
                'fastmobilecrawl',
                'feedfetcher-google',
                'firefly',
                'froogle',
                'gigabot',
                'googlebot',
                'googlebot-mobile',
                'heritrix',
                'httrack',
                'ia_archiver',
                'irlbot',
                'iescholar',
                'infoseek',
                'jumpbot',
                'linkcheck',
                'lycos',
                'mediapartners',
                'mediobot',
                'motionbot',
                'msnbot',
                'mshots',
                'openbot',
                'pss-webkit-request',
                'pythumbnail',
                'scooter',
                'slurp',
                'Speed Insights',
                'snapbot',
                'spider',
                'taptubot',
                'technoratisnoop',
                'teoma',
                'twiceler',
                'yahooseeker',
                'yahooysmcm',
                'yammybot',
                'ahrefsbot',
                'Pingdom',
                'GTmetrix',
                'PageSpeed',
                'Google Page Speed',
                'kraken',
                'yandexbot',
                'twitterbot',
                'tweetmemebot',
                'openhosebot',
                'queryseekerspider',
                'linkdexbot',
                'grokkit-crawler',
                'livelapbot',
                'germcrawler',
                'domaintunocrawler',
                'grapeshotcrawler',
                'cloudflare-alwaysonline',
            );

            foreach ( $bot_agents as $bot_agent ) {
                if ( false !== stripos( $ua, $bot_agent ) ) {
                    return true;
                }
            }

            return false;
        }


        /**
         * Check if there is a Js minification plugin installed
         */
        public static function is_js_minified() {

            /*
             Supported Plugins List:
              ----
               * Better WordPress Minify
               * Fast Velocity Minify
               * JS & CSS Script Optimizer
            */

            if ( THF_BWPMINIFY_IS_ACTIVE || function_exists( 'fvm_download_and_cache' ) || class_exists( 'evScriptOptimizer' ) ) {
                return true;
            }

            return false;
        }


        /**
         * Change Page Title
         */
        public static function set_page_title( $new_title ) {
            add_filter( 'the_title', function ( $title, $page_id ) use ( $new_title ) {
                return $new_title ? : $title;
            }, 10, 2 );
        }

        /**
         * Check SSL
         */
        public static function is_ssl() {

            if ( is_ssl() || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) || ( stripos( get_option( 'siteurl' ), 'https://' ) === 0 ) ) {
                return true;
            }

            return false;
        }

        /**
         * @param $value
         *
         * @return bool
         */
        public static function maybe_bool( $value ) {

            if ( empty( $value ) ) {
                return false;
            }

            if ( is_string( $value ) ) {

                if ( in_array( $value, array( 'on', 'true', 'yes' ) ) ) {
                    return true;
                }

                if ( in_array( $value, array( 'off', 'false', 'no' ) ) ) {
                    return false;
                }
            }

            return $value;
        }

        /**
         * convert latin number to persian
         *
         * @param string $number
         *
         * @return string
         */
        public static function to_persian_number( $number ) {
            return self::convert_number( $number, 'en', 'fa' );
        }


        /**
         * convert persian number to latin
         *
         * @param string $number
         *
         * @return string
         */
        public static function to_latin_number( $number ) {
            return self::convert_number( $number, 'fa', 'en' );
        }


        /**
         * Convert persian & latin number
         *
         * @param boolean $number
         * @param string  $from
         * @param string  $to
         *
         * @since 1.0.0
         * @return string
         */
        public static function convert_number( $number, $from = 'fa', $to = 'en' ) {
            //arrays of persian and latin numbers
            $persian_num = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
            $latin_num   = range( 0, 9 );

            $find    = $persian_num;
            $replace = $latin_num;

            if ( $from == 'en' && $to == 'fa' ) {
                $find    = $latin_num;
                $replace = $persian_num;
            }

            $number = str_replace( $find, $replace, strval( $number ) );

            return $number;
        }

        /**
         * Validate mobile number
         *
         * @param $number
         *
         * @since 1.0.0
         * @return boolean
         */
        public static function is_valid_mobile( $number ) {
            $patern = '/(0|\+98)?([ ]|,|-|[()]){0,2}9[0|1|2|3|4]([ ]|,|-|[()]){0,3}(?:[0-9]([ ]|,|-|[()]){0,2}){8}/';

            if ( preg_match( $patern, strval( $number ), $matches, PREG_OFFSET_CAPTURE, 0 ) )
                return true;

            return false;
        }
    }

}
