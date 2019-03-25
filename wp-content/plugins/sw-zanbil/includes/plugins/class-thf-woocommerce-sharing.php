<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_Sharing' ) ) {

    /**
     * Main TCW_Sharing class
     *
     * @since       1.0.0
     */
    class TCW_Sharing {
        /**
         * The single instance of the class.
         *
         * @var TCW_Sharing
         * @since 1.0.0
         */
        private static $_instance;


        /**
         * instance of the TCW_PLUGIN_WooCommerce.
         *
         * @var     TCW_PLUGIN_WooCommerce
         * @since   1.0.0
         */
        private static $WOOCOMMERCE;


        /**
         * Path to template folder
         *
         * @since 1.0.0
         */
        public $template_path = 'sharing/';


        /**
         * Get active instance
         *
         * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
         * @since   1.0.0
         * @static
         * @see     tcw_sharing()
         * @return  TCW_Sharing - Main instance
         */
        public static function instance( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
            if ( is_null( self::$_instance ) ) {
                self::$WOOCOMMERCE = $WOOCOMMERCE;
                self::$_instance   = new self();
            }
            return self::$_instance;
        }


        public function __construct() {
            if ( !self::is_active() ) return;

            $this->template_path = self::$WOOCOMMERCE->template_path . $this->template_path;

            $this->hooks();
        }

        /**
         * Check is active
         *
         * @since   1.0.0
         * @static
         * @return  boolean
         */
        public static function is_active() {
            return TCW::get_option( 'wc_sharing' );
        }

        /**
         * @return mixed
         */
        public static function email_is_active() {
            return TCW::get_option( 'wc_sharing_email' );
        }


        /**
         * @return mixed
         */
        public static function sms_is_active() {
            return TCW::get_option( 'wc_sharing_sms' );
        }


        /**
         * @param string $icon twitter, facebook, google_plus, telegram
         * @return mixed
         */
        public static function icon_is_active( $icon ) {
            if ( !in_array( $icon, [ 'twitter', 'facebook', 'google_plus', 'telegram' ] ) ) return false;
            return TCW::get_option( 'wc_sharing_' . $icon );
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         */
        private function hooks() {

            //-> Ajax Sharing events
            add_action( 'wp_ajax_thf_woocommerce_sharing', [ $this, 'process_ajax_request' ] );
            add_action( 'wp_ajax_nopriv_thf_woocommerce_sharing', [ $this, 'process_ajax_request' ] );

            //-> Show Share Button
            add_action( 'woocommerce_product_thumbnails', array( $this, 'show_in_single_product' ), 20 );

            //-> Insert modal html in footer
            add_action( 'wp_head', function () {
                if ( is_product() ) {
                    add_action( 'wp_footer', [ $this, 'get_modal_html' ] );
                }
            } );
        }


        /**
         * @param string|array $to
         * @param WC_Product   $product Product Object.
         * @since 1.0.0
         * @return boolean
         */
        public function send_mail( $to, $product ) {
            $subject = $this->get_template( 'mail.subject', $product );
            $message = $this->get_template( 'mail.content', $product );
            $from    = TCW::get_option( 'wc_sharing_email_from', false );
            $headers = [];

            if ( $from ) $headers[] = 'From: ' . $from;

            if ( is_array( $to ) ) {
                $headers[] = 'Bcc:' . implode( ',', $to );
                $to        = null;
            }

            $headers[] = 'Content-Type: text/html; charset=UTF-8';

            $mail = wp_mail( $to, $subject, $message, $headers );

            if ( !is_wp_error( $mail ) ) {
                return true;
            }

            return false;
        }


        /**
         * @param string|array $to
         * @param WC_Product   $product Product Object.
         * @since 1.0.0
         * @return boolean
         */
        public function send_sms( $to, $product ) {
            if ( !THF_PERSIAN_WOOCOMMERCE_SMS_IS_ACTIVE ) return false;

            $message = $this->get_template( 'sms', $product );

            $data = array(
                'post_id' => $product->get_id(),
                'type'    => 11,
                'mobile'  => $to,
                'message' => $message,
            );

            if ( PWooSMS()->SendSMS( $data ) === true ) {
                return true;
            }

            return false;
        }


        /**
         * @param string     $type    What the message is for. Valid values are 'mail.subject', 'mail.content'.
         * @param WC_Product $product Product Object.
         * @since 1.0.0
         * @return mixed
         */
        public function get_template( $type, $product ) {
            if ( $type === 'mail.subject' ) {
                $template = TCW::get_option( 'wc_sharing_email_subject' );
            } elseif ( $type === 'mail.content' ) {
                $template = TCW::get_option( 'wc_sharing_email_content' );
            } elseif ( $type === 'sms' ) {
                $template = TCW::get_option( 'wc_sharing_sms_content' );
            }

            if ( !empty( $template ) ) {
                $find = [
                    '{site_title}',
                    '{site_url}',
                    '{product_id}',
                    '{product_url}',
                    '{product_title}',
                    '{product_image}',
                    '{regular_price}',
                    '{onsale_price}',
                    '{onsale_from}',
                    '{onsale_to}',
                    '{sku}',
                    '{stock}',
                ];

                $replace = [
                    get_bloginfo( 'name' ),
                    site_url(),
                    $product->get_id(),
                    wp_get_shortlink( $product->get_id() ),
                    $product->get_title(),
                    $product->get_image(),
                    $product->get_regular_price(),
                    $product->get_sale_price(),
                    $product->get_date_on_sale_from(),
                    $product->get_date_on_sale_to(),
                    $product->get_sku(),
                    $product->get_stock_quantity(),
                ];

                $template = str_replace( $find, $replace, $template );

                return $template;
            }

            return false;
        }


        /**
         * @since 1.0.0
         */
        public function process_ajax_request() {
            TCW_HELPER::check_ajax_referer();

            $json        = [];
            $errors      = [];
            $tcw_sharing = $_POST['tcw_sharing'];

            if ( !empty( $tcw_sharing ) ) {

                $product_id = (int)esc_sql( $tcw_sharing['product_id'] );
                $user_email = strval( preg_replace( '/\s+/', '', $tcw_sharing['email'] ) );
                $user_phone = strval( preg_replace( '/\s+/', '', $tcw_sharing['sms'] ) );

                // Check Product ID
                if ( empty( $product_id ) ) {
                    $errors[] = __( 'Product not found!.', TCW_TEXTDOMAIN );
                }

                // Check Email
                if ( !empty( $user_email ) && !is_email( $user_email ) ) {
                    $errors[] = __( 'Email is Invalid!.', TCW_TEXTDOMAIN );
                }

                // Check Phone
                if ( !empty( $user_phone ) && !TCW_HELPER::is_valid_mobile( $user_phone ) ) {
                    $errors[] = __( 'Phone is Invalid!.', TCW_TEXTDOMAIN );
                }

                if ( empty( $user_email ) && empty( $user_phone ) ) {
                    $errors[] = __( 'Please select at least one of the items.', TCW_TEXTDOMAIN );
                }

                if ( empty( $errors ) ) {
                    $product = wc_get_product( $product_id );

                    // Send Email
                    if ( !empty( $user_email ) ) {
                        if ( $this->send_mail( $user_email, $product ) ) {
                            $json['msg'][] = __( 'The email was successfully sent to your friend.', TCW_TEXTDOMAIN );
                        } else {
                            $errors[] = __( 'Unexpected error. Email not sent. Please try again later.', TCW_TEXTDOMAIN );
                        }
                    }

                    // Send SMS
                    if ( !empty( $user_phone ) ) {
                        if ( $this->send_sms( $user_phone, $product ) ) {
                            $json['msg'][] = __( 'The sms was successfully sent to your friend.', TCW_TEXTDOMAIN );
                        } else {
                            $errors[] = __( 'Unexpected error. SMS not sent. Please try again later.', TCW_TEXTDOMAIN );
                        }
                    }
                }
            }

            if ( empty( $errors ) ) {
                wp_send_json_success( $json );
            } else {
                $json['msg'] = $errors;

                wp_send_json_error( $json );
            }
        }


        /**
         * Show sharing subscribe button in single product
         *
         * @since  1.0.0
         * @action woocommerce_single_product_summary, woocommerce_product_thumbnails
         */
        public function show_in_single_product() {
            if ( !get_the_ID() ) return;

            $this->get_shortcode_content( 'button' );
        }

        /**
         * Get shortcode content
         *
         * @param string $template
         * @since 1.0.0
         */
        public function get_shortcode_content( $template = 'both' ) {
            if ( !self::is_active() ) return;

            if ( self::email_is_active() || self::sms_is_active() ) wp_enqueue_script( 'thf-customize-wc-sharing' );

            wp_enqueue_style( 'thf-customize-wc-sharing' );

            if ( $template == 'modal' ) {
                $this->get_modal_html();
            } elseif ( $template == 'button' ) {
                $this->get_button_html();
            } else {
                $this->get_button_html();
                $this->get_modal_html();
            }
        }

        /**
         * Sharing Subscribe Button
         *
         * @since 1.0.0
         */
        public function get_button_html() {
            $sharing_button_text = TCW::get_option( 'wc_sharing_button_text', esc_html__( 'Share', TCW_TEXTDOMAIN ) );
            $data                = compact( 'sharing_button_text' );

            TCW_HELPER::get_template_part( $this->template_path . 'sharing-button.php', $data );
        }


        /**
         * Sharing Subscribe Modal
         *
         * @since 1.0.0
         */
        public function get_modal_html() {
            $product_id            = get_the_ID();
            $product_title         = get_the_title();
            $product_link          = get_permalink();
            $product_shortlink     = wp_get_shortlink( $product_id );
            $email_is_active       = self::email_is_active();
            $sms_is_active         = self::sms_is_active();
            $twitter_is_active     = self::icon_is_active( 'twitter' );
            $facebook_is_active    = self::icon_is_active( 'facebook' );
            $google_plus_is_active = self::icon_is_active( 'google_plus' );
            $telegram_is_active    = self::icon_is_active( 'telegram' );

            $share_title = urlencode( $product_title );
            $share_link  = urlencode( $product_link );

            // Compose the share links for Facebook, Twitter and Google+
            $twitter_link     = sprintf( 'https://twitter.com/intent/tweet?text=%2$s&url=%1$s', $share_link, $share_title );
            $facebook_link    = sprintf( 'https://www.facebook.com/sharer/sharer.php?m2w&s=100&p[url]=%1$s', $share_link );
            $google_plus_link = sprintf( 'https://plus.google.com/share?url=%1$s', $share_link );
            $telegram_link    = sprintf( 'https://telegram.me/share/url?url=%1$s', $share_link );

            $data = compact( 'product_id', 'product_title', 'product_link', 'product_shortlink', 'twitter_link', 'facebook_link', 'google_plus_link', 'telegram_link', 'email_is_active', 'sms_is_active', 'twitter_is_active', 'facebook_is_active', 'google_plus_is_active', 'telegram_is_active' );

            TCW_HELPER::get_template_part( $this->template_path . 'sharing-modal.php', $data );
        }


    }
} // End if class_exists check

/**
 * Main instance of TCW_Sharing.
 * Returns the main instance of TCW_Sharing to prevent the need to use globals.
 *
 * @param   TCW_PLUGIN_WooCommerce $WOOCOMMERCE
 * @since   1.0.0
 * @return  TCW_Sharing - Main instance
 */
function tcw_sharing( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
    return TCW_Sharing::instance( $WOOCOMMERCE );
}