<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_Notifier' ) ) {

    /**
     * Main TCW_Notifier class
     * @since       1.0.0
     */
    class TCW_Notifier {
        /**
         * The single instance of the class.
         *
         * @var TCW_Notifier
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
        public $template_path = 'notifier/';

        /**
         * Notifier User Subscribe List Meta Key
         *
         * @since 1.0.0
         */
        private $meta_key = '_tcw_notifier_user_list';


        /**
         * Get active instance
         *
         * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
         *
         * @since   1.0.0
         * @static
         * @see     tcw_notifier()
         * @return  TCW_Notifier - Main instance
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
            return TCW::get_option( 'wc_notifier' );
        }

        /**
         * @return mixed
         */
        public static function email_is_active() {
            return TCW::get_option( 'wc_notifier_email' );
        }

        /**
         * @return mixed
         */
        public static function sms_is_active() {
            return TCW::get_option( 'wc_notifier_sms' );
        }

        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         */
        private function hooks() {

            //-> Fire function after Stock Status Updated
            // add_action( 'woocommerce_variation_set_stock_status', [ $this, 'stock_status_updated' ], 10, 3 );
            add_action( 'woocommerce_product_set_stock_status', [ $this, 'stock_status_updated' ], 10, 3 );

            add_action( 'template_redirect', [ $this, 'maybe_should_unsubscribe' ] );

            //-> Ajax Subscribe user
            add_action( 'wp_ajax_thf_woocommerce_subscribe_user', [ $this, 'process_ajax_request' ] );
            add_action( 'wp_ajax_nopriv_thf_woocommerce_subscribe_user', [ $this, 'process_ajax_request' ] );

            //-> Show Subscribe Button
            add_action( 'woocommerce_product_thumbnails', array( $this, 'show_in_single_product' ), 100 );
            add_action( 'woocommerce_single_product_summary', array( $this, 'show_in_single_product' ), 30 );

            //-> Insert modal html in footer
            add_action( 'wp_head', function () {
                if ( is_product() ) {
                    add_action( 'wp_footer', [ $this, 'get_modal_html' ] );
                }
            } );
        }

        public function maybe_should_unsubscribe() {
            if ( is_product() ) {
                $product_id = get_the_ID();
                $meta_key   = $this->meta_key;
                $cookie_key = $meta_key;

                if ( !empty( $product_id ) && isset( $_COOKIE[$cookie_key][$product_id] ) && !empty( $_COOKIE[$cookie_key][$product_id] ) ) {
                    $cookie_set_key       = $cookie_key . '[' . $product_id . ']';
                    $cookie_set_email_key = $cookie_set_key . '[email]';
                    $cookie_set_phone_key = $cookie_set_key . '[phone]';

                    $meta_data   = get_post_meta( $product_id, $meta_key, true );
                    $cookie_data = $_COOKIE[$cookie_key][$product_id];
                    $old_email   = isset( $cookie_data['email'] ) ? $cookie_data['email'] : null;
                    $old_phone   = isset( $cookie_data['phone'] ) ? $cookie_data['phone'] : null;

                    if ( empty( $old_email ) || !isset( $meta_data['email'] ) || !is_array( $meta_data['email'] ) || !in_array( $old_email, $meta_data['email'] ) ) {
                        unset( $_COOKIE[$meta_key][$product_id]['email'] );
                        setcookie( $cookie_set_email_key, null, -1, '/' );
                    }

                    if ( empty( $old_phone ) || !isset( $meta_data['phone'] ) || !is_array( $meta_data['phone'] ) || !in_array( $old_phone, $meta_data['phone'] ) ) {
                        unset( $_COOKIE[$meta_key][$product_id]['phone'] );
                        setcookie( $cookie_set_phone_key, null, -1, '/' );
                    }
                }
            }
        }

        /**
         * @param            $product_id
         * @param            $stock_status
         * @param WC_Product $product Product Object.
         *
         * @since 1.0.0
         */
        public function stock_status_updated( $product_id, $stock_status, $product ) {
            if ( $product->is_type( 'variation' ) ) {
                $product_id = $product->get_parent_id( 'edit' );
                $product    = wc_get_product( $product_id );
            }

            $this->send_notification( $product_id, $product );
        }

        /**
         * @param int        $product_id
         * @param WC_Product $product Product Object.
         *
         * @since 1.0.0
         */
        public function send_notification( $product_id, $product ) {

            if ( $product->is_in_stock() && metadata_exists( 'post', $product_id, $this->meta_key ) ) {
                $users = get_post_meta( $product_id, $this->meta_key, true );

                if ( !empty( $users ) && is_array( $users ) ) {
                    $email = $users['email'];
                    $phone = $users['phone'];

                    $sent       = [];
                    $email_sent = false;
                    $sms_sent   = false;

                    // Send Mail
                    if ( self::email_is_active() && !empty( $email ) ) {
                        $email_sent = $this->send_mail( $email, $product );

                        if ( $email_sent == true ) {
                            $sent['email'] = $email;
                        }
                    }

                    // Send SMS
                    if ( self::sms_is_active() && !empty( $phone ) ) {
                        $sms_sent = $email_sent = $this->send_sms( $phone, $product );

                        if ( $sms_sent == true ) {
                            $sent['phone'] = $phone;
                        }
                    }

                    if ( $email_sent ) $users['email'] = null;

                    if ( $sms_sent ) $users['phone'] = null;

                    // Delete/Un-CheckSubscribed users
                    if ( !TCW::get_option( 'wc_notifier_unsubscribe_after_send' ) ) {
                        if ( !isset( $users['sent'] ) || !is_array( $users['sent'] ) ) {
                            $users['sent'] = [];
                        }

                        $users['sent'] = array_merge( $users['sent'], $sent );

                        if ( isset( $users['sent']['email'] ) ) {
                            $users['sent']['email'] = array_unique( $users['sent']['email'] );
                        }

                        if ( isset( $users['sent']['phone'] ) ) {
                            $users['sent']['phone'] = array_unique( $users['sent']['phone'] );
                        }
                    }

                    // Update subscribed users
                    update_post_meta( $product_id, $this->meta_key, $users );
                }
            }
        }

        /**
         * @param string|array $to
         * @param WC_Product   $product Product Object.
         *
         * @since 1.0.0
         * @return boolean
         */
        public function send_mail( $to, $product ) {
            $subject = $this->get_notification_template( 'mail.subject', $product );
            $message = $this->get_notification_template( 'mail.content', $product );
            $from    = TCW::get_option( 'wc_notifier_email_from', false );
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
         *
         * @since 1.0.0
         * @return boolean
         */
        public function send_sms( $to, $product ) {
            if ( !THF_PERSIAN_WOOCOMMERCE_SMS_IS_ACTIVE ) return false;

            $message = $this->get_notification_template( 'sms', $product );

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
         * @param string     $type    What the message is for. Valid values are 'mail.subject', 'mail.content', 'sms'.
         * @param WC_Product $product Product Object.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function get_notification_template( $type, $product ) {
            if ( $type === 'mail.subject' ) {
                $template = TCW::get_option( 'wc_notifier_email_subject' );
            } elseif ( $type === 'mail.content' ) {
                $template = TCW::get_option( 'wc_notifier_email_content' );
            } elseif ( $type === 'sms' ) {
                $template = TCW::get_option( 'wc_notifier_sms_content' );
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

            $json         = [];
            $errors       = [];
            $tcw_notifier = $_POST['tcw_notifier'];

            if ( !empty( $tcw_notifier ) ) {

                $product_id = (int)esc_sql( $tcw_notifier['product_id'] );

                // Check Product ID
                if ( empty( $product_id ) ) {
                    $errors[] = __( 'Product not found!.', TCW_TEXTDOMAIN );
                } else {
                    $product = wc_get_product( $product_id );
                    if ( $product->is_in_stock() ) {
                        $errors[] = __( 'Product is in Stock!.', TCW_TEXTDOMAIN );
                    }
                    unset( $product );
                }

                if ( empty( $errors ) ) {
                    $meta_key         = $this->meta_key;
                    $cookie_key       = $meta_key . '[' . $product_id . ']';
                    $cookie_email_key = $cookie_key . '[email]';
                    $cookie_phone_key = $cookie_key . '[phone]';

                    $email_cookie = false;
                    $phone_cookie = false;

                    $email_checked = TCW_HELPER::maybe_bool( $tcw_notifier['email']['on'] );
                    $phone_checked = TCW_HELPER::maybe_bool( $tcw_notifier['phone']['on'] );

                    $user_email = strval( preg_replace( '/\s+/', '', $tcw_notifier['email']['input'] ) );
                    $user_phone = strval( preg_replace( '/\s+/', '', $tcw_notifier['phone']['input'] ) );

                    $new_user_data = [];

                    // Format persian number
                    $user_phone = TCW_HELPER::to_latin_number( $user_phone );

                    // Check email
                    if ( $email_checked ) {
                        if ( empty( $user_email ) || !is_email( $user_email ) ) {
                            $errors[] = __( 'Email is empty OR Invalid!.', TCW_TEXTDOMAIN );
                        } else {
                            $new_user_data['email'] = $user_email;
                        }
                    }

                    // Check phone number
                    if ( $phone_checked ) {
                        if ( empty( $user_phone ) || !TCW_HELPER::is_valid_mobile( $user_phone ) ) {
                            $errors[] = __( 'Phone number is empty OR Invalid!.', TCW_TEXTDOMAIN );
                        } else {
                            $user_phone             = substr( $user_phone, 1 );
                            $new_user_data['phone'] = $user_phone;
                        }
                    }

                    // Check Cookie
                    if ( empty( $new_user_data ) && empty( $errors ) ) {
                        if ( isset( $_COOKIE[$meta_key][$product_id] ) && !empty( $_COOKIE[$meta_key][$product_id] ) ) {
                            $meta_value  = get_post_meta( $product_id, $meta_key, true );
                            $cookie_data = $_COOKIE[$meta_key][$product_id];

                            $old_email   = isset( $cookie_data['email'] ) ? $cookie_data['email'] : null;
                            $old_phone   = isset( $cookie_data['phone'] ) ? $cookie_data['phone'] : null;
                            $unsubscribe = false;

                            if ( empty( $meta_value ) || !is_array( $meta_value ) ) {
                                $meta_value          = [];
                                $meta_value['email'] = [];
                                $meta_value['phone'] = [];
                            }

                            // UnSubscribe Email
                            if ( !$email_checked && ( $key = array_search( $old_email, $meta_value['email'] ) ) !== false ) {
                                $json['msg'][] = __( 'Successfully Unsubscribe form Email.', TCW_TEXTDOMAIN );
                                $unsubscribe   = true;

                                setcookie( $cookie_email_key, null, -1, '/' );
                                unset( $meta_value['email'][$key] );
                                unset( $_COOKIE[$meta_key][$product_id]['email'] );
                            }

                            // UnSubscribe Phone
                            if ( !$phone_checked && ( $key = array_search( $old_phone, $meta_value['phone'] ) ) !== false ) {
                                $json['msg'][] = __( 'Successfully Unsubscribe form SMS.', TCW_TEXTDOMAIN );
                                $unsubscribe   = true;

                                setcookie( $cookie_phone_key, null, -1, '/' );
                                unset( $meta_value['phone'][$key] );
                                unset( $_COOKIE[$meta_key][$product_id]['phone'] );
                            }

                            if ( $unsubscribe == true ) {
                                if ( update_post_meta( $product_id, $meta_key, $meta_value ) ) {
                                    wp_send_json_success( $json );
                                } else {
                                    $errors[] = __( 'Unexpected error. Please try again later.', TCW_TEXTDOMAIN );
                                }
                            }

                        } else {
                            $errors[] = __( 'Please select at least one of the items.', TCW_TEXTDOMAIN );
                        }
                    }

                    if ( empty( $errors ) ) {
                        $meta_value = get_post_meta( $product_id, $meta_key, true );
                        $prev_value = $meta_value;

                        if ( empty( $meta_value ) || !is_array( $meta_value ) ) {
                            $meta_value          = [];
                            $meta_value['email'] = [];
                            $meta_value['phone'] = [];
                        }


                        // Check Cookie
                        if ( isset( $_COOKIE[$meta_key][$product_id] ) && !empty( $_COOKIE[$meta_key][$product_id] ) ) {
                            $cookie_data = $_COOKIE[$meta_key][$product_id];
                            $old_email   = isset( $cookie_data['email'] ) ? $cookie_data['email'] : null;
                            $old_phone   = isset( $cookie_data['phone'] ) ? $cookie_data['phone'] : null;

                            // UnSubscribe/Update Email
                            if ( ( $key = array_search( $old_email, $meta_value['email'] ) ) !== false ) {
                                $email_cookie = true;

                                if ( !empty( $new_user_data['email'] ) ) {
                                    if ( $new_user_data['email'] !== $meta_value['email'][$key] ) {
                                        $json['msg'][] = __( 'Email Updated Successfully.', TCW_TEXTDOMAIN );
                                    }
                                } elseif ( !$email_checked ) {
                                    $json['msg'][] = __( 'Successfully Unsubscribe form Email.', TCW_TEXTDOMAIN );
                                }

                                unset( $meta_value['email'][$key] );
                            }

                            // UnSubscribe/Update Phone
                            if ( ( $key = array_search( $old_phone, $meta_value['phone'] ) ) !== false ) {
                                $phone_cookie = true;

                                if ( !empty( $new_user_data['phone'] ) ) {
                                    if ( $new_user_data['phone'] !== $meta_value['phone'][$key] ) {
                                        $json['msg'][] = __( 'Phone Updated Successfully.', TCW_TEXTDOMAIN );
                                    }
                                } elseif ( !$phone_checked ) {
                                    $json['msg'][] = __( 'Successfully Unsubscribe form SMS.', TCW_TEXTDOMAIN );
                                }

                                unset( $meta_value['phone'][$key] );
                            }

                        }

                        if ( !$email_cookie && !$phone_cookie && !empty( $new_user_data['email'] ) && !empty( $new_user_data['phone'] ) ) {
                            $json['msg'][] = __( 'Successfully Subscribed.', TCW_TEXTDOMAIN );
                        } elseif ( !$email_cookie && !empty( $new_user_data['email'] ) ) {
                            $json['msg'][] = __( 'Successfully Subscribed to Email.', TCW_TEXTDOMAIN );
                        } elseif ( !$phone_cookie && !empty( $new_user_data['phone'] ) ) {
                            $json['msg'][] = __( 'Successfully Subscribed to SMS.', TCW_TEXTDOMAIN );
                        }

                        // Set Cookie
                        setcookie( $cookie_email_key, $new_user_data['email'], time() + 30 * DAY_IN_SECONDS, "/" );
                        setcookie( $cookie_phone_key, $new_user_data['phone'], time() + 30 * DAY_IN_SECONDS, "/" );

                        // Save to database
                        if ( isset( $new_user_data['email'] ) && !empty( $new_user_data['email'] ) ) $meta_value['email'][] = $new_user_data['email'];

                        if ( isset( $new_user_data['phone'] ) && !empty( $new_user_data['phone'] ) ) $meta_value['phone'][] = $new_user_data['phone'];

                        $meta_value['email'] = array_values( array_unique( $meta_value['email'], SORT_REGULAR ) );
                        $meta_value['phone'] = array_values( array_unique( $meta_value['phone'], SORT_REGULAR ) );


                        if ( $prev_value == $meta_value ) {
                            $json['msg'][] = __( 'You have already subscribed!.', TCW_TEXTDOMAIN );

                            wp_send_json_error( $json );
                        } else {
                            if ( update_post_meta( $product_id, $meta_key, $meta_value ) ) {
                                wp_send_json_success( $json );
                            } else {
                                $errors[] = __( 'Unexpected error. Please try again later.', TCW_TEXTDOMAIN );
                            }
                        }
                    }
                }
            }

            $json['msg'] = $errors;

            wp_send_json_error( $json );
        }

        /**
         * @since 1.0.0
         */
        public function get_ajax_messages() {

        }

        /**
         * Show notifier subscribe button in single product
         *
         * @since  1.0.0
         * @action woocommerce_single_product_summary, woocommerce_product_thumbnails
         */
        public function show_in_single_product() {
            if ( !get_the_ID() ) return;

            if ( self::$WOOCOMMERCE->is_in_stock() ) return;

            $show_button = strval( TCW::get_option( 'wc_notifier_subscribe_btn' ) );

            if ( $show_button == 'manually' ) {
                return;
            }

            if ( $show_button == 'thumbnail' ) {
                $stop = current_action() != 'woocommerce_product_thumbnails';
            } else {
                $stop = current_action() == 'woocommerce_product_thumbnails';
            }

            if ( $stop ) {
                return;
            }

            $this->get_shortcode_content( 'button' );
        }

        /**
         * Get shortcode content
         *
         * @param string $template
         *
         * @since 1.0.0
         */
        public function get_shortcode_content( $template = 'both' ) {
            if ( !self::is_active() || self::$WOOCOMMERCE->is_in_stock() ) return;

            wp_enqueue_script( 'thf-customize-wc-notifier' );
            wp_enqueue_style( 'thf-customize-wc-notifier' );

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
         * Notifier Subscribe Button
         *
         * @since 1.0.0
         */
        public function get_button_html() {
            $notifier_button_text = TCW::get_option( 'wc_notifier_button_text', esc_html__( 'Notify Me', TCW_TEXTDOMAIN ) );
            $data                 = compact( 'notifier_button_text' );

            TCW_HELPER::get_template_part( $this->template_path . 'notifier-button.php', $data );
        }


        /**
         * Notifier Subscribe Modal
         *
         * @since 1.0.0
         */
        public function get_modal_html() {
            $product_id = get_the_ID();
            $meta_key   = $this->meta_key;
            $cookie_key = $meta_key;

            $email_is_active = self::email_is_active();
            $sms_is_active   = self::sms_is_active();

            $email_checked = false;
            $phone_checked = false;

            $current_user = wp_get_current_user();
            $user_email   = null;
            $user_phone   = null;


            if ( $email_is_active ) $user_email = $current_user->user_email;

            // Check Cookie
            if ( !empty( $product_id ) && isset( $_COOKIE[$cookie_key][$product_id] ) && !empty( $_COOKIE[$cookie_key][$product_id] ) ) {
                $cookie_data = $_COOKIE[$cookie_key][$product_id];
                $old_email   = isset( $cookie_data['email'] ) ? $cookie_data['email'] : null;
                $old_phone   = isset( $cookie_data['phone'] ) ? $cookie_data['phone'] : null;

                if ( !empty( $old_email ) ) {
                    $user_email    = $old_email;
                    $email_checked = true;
                }

                if ( !empty( $old_phone ) ) {
                    $user_phone = $old_phone;
                }

            }

            if ( !empty( $user_phone ) ) {
                $phone_checked = true;
                $user_phone    = '0' . $user_phone;
            }

            $data = compact( 'product_id', 'email_is_active', 'sms_is_active', 'email_checked', 'phone_checked', 'user_email', 'user_phone' );

            TCW_HELPER::get_template_part( $this->template_path . 'notifier-modal.php', $data );
        }


    }
} // End if class_exists check

/**
 * Main instance of TCW_Notifier.
 * Returns the main instance of TCW_Notifier to prevent the need to use globals.
 *
 * @param   TCW_PLUGIN_WooCommerce $WOOCOMMERCE
 *
 * @since   1.0.0
 * @return  TCW_Notifier - Main instance
 */
function tcw_notifier( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
    return TCW_Notifier::instance( $WOOCOMMERCE );
}