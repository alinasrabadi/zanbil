<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_Comments_Like_Dislike' ) ) {

    /**
     * Main TCW_Comments_Like_Dislike class
     * @since       1.0.0
     */
    class TCW_Comments_Like_Dislike {
        /**
         * The single instance of the class.
         *
         * @var TCW_Comments_Like_Dislike
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
        public $template_path = 'comments-like-dislike/';


        /**
         * Get active instance
         *
         * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
         *
         * @since   1.0.0
         * @static
         * @see     tcw_comments_like_dislike()
         * @return  TCW_Comments_Like_Dislike - Main instance
         */
        public static function instance( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
            if ( is_null( self::$_instance ) ) {
                self::$WOOCOMMERCE = $WOOCOMMERCE;
                self::$_instance   = new self();
            }
            return self::$_instance;
        }


        public function __construct() {
            if ( !self::is_active() )
                return;

            $this->template_path = self::$WOOCOMMERCE->template_path . $this->template_path;

            $this->setup();
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
            return TCW::get_option( 'wc_comments_like_dislike' );
        }

        /**
         * @access  private
         * @since   1.0.0
         */
        private function setup() {

        }

        /**
         * Run action and filter hooks
         *
         * @access  private
         * @since   1.0.0
         */
        private function hooks() {
            //-> Add HTML
            add_filter( 'comment_text', array( $this, 'comments_like_dislike' ), 200, 2 ); // hook to add html for like dislike

            //-> Ajax Action
            add_action( 'wp_ajax_tcw_comment_ajax_action', array( $this, 'like_dislike_action' ) );
            add_action( 'wp_ajax_nopriv_tcw_comment_ajax_action', array( $this, 'like_dislike_action' ) );

            //-> Admin Hooks
            if ( is_admin() )
                $this->admin_hooks();
        }

        /**
         * Run Admin action and filter hooks
         *
         * @access  private
         * @since   1.0.0
         */
        private function admin_hooks() {

            if ( TCW::get_option( 'wc_comments_like_dislike_columns' ) ) {
                /**
                 * Add like dislike columns in comments section
                 */
                add_filter( 'manage_edit-comments_columns', array( $this, 'add_like_dislike_column' ) );

                /**
                 * Display Like Dislike count in each column
                 */
                add_filter( 'manage_comments_custom_column', array( $this, 'display_like_dislike_values' ), 10, 2 );
            }
        }

        /**
         * Show comment like dislike
         *
         * @since   1.0.0
         * @filter  comment_text
         *
         * @param string $comment_text
         * @param array  $comment
         *
         * @return string
         */
        public function comments_like_dislike( $comment_text, $comment = null ) {

            /**
             * Don't append like dislike when links are being checked
             *
             * @since 1.0.0
             */
            if ( isset( $_REQUEST['comment'] ) ) {
                return $comment_text;
            }

            /**
             * Only implement on single product page
             *
             * @since 1.0.0
             */
            if ( !is_product() ) {
                return $comment_text;
            }

            /**
             * Don't implement on admin section
             *
             * @since 1.0.0
             */
            if ( is_admin() ) {
                return $comment_text;
            }

            wp_enqueue_script( 'thf-customize-wc-comments-like-dislike' );
            wp_enqueue_style( 'thf-customize-wc-comments-like-dislike' );

            $template_path = $this->template_path;
            $user_ip       = $this->get_user_IP();

            ob_start();
            TCW_HELPER::get_template_part( $template_path . 'like-dislike-html.php', compact( 'comment', 'template_path', 'user_ip' ) );
            $like_dislike_html = ob_get_clean();

            if ( TCW::get_option( 'wc_comments_like_dislike_position' ) == 'after' ) {
                $comment_text .= $like_dislike_html;
            } else {
                $comment_text = $like_dislike_html . $comment_text;
            }

            return $comment_text;
        }

        /**
         * Ajax Comment Like Dislike Action
         *
         * @since  1.0.0
         * @action tcw_comment_ajax_action
         */
        public function like_dislike_action() {
            TCW_HELPER::check_ajax_referer();

            $comment_id          = sanitize_text_field( $_POST['comment_id'] );
            $type                = sanitize_text_field( $_POST['type'] );
            $user_ip             = sanitize_text_field( $_POST['user_ip'] );
            $comment_like_ips    = get_comment_meta( $comment_id, 'tcw_comment_like_ips', true );
            $comment_dislike_ips = get_comment_meta( $comment_id, 'tcw_comment_dislike_ips', true );
            $json                = [];

            if ( empty( $comment_like_ips ) ) {
                $comment_like_ips = array();
            }

            if ( empty( $comment_dislike_ips ) ) {
                $comment_dislike_ips = array();
            }

            if ( $type == 'like' ) {
                if ( !in_array( $user_ip, $comment_like_ips ) ) {
                    $json['like_count'] = $this->set_comment_like( $comment_id, 1 );

                    if ( !in_array( $user_ip, $comment_dislike_ips ) ) {
                        $json['dislike_count'] = $this->set_comment_dislike( $comment_id, 0 );
                    } else {
                        $json['dislike_count'] = $this->set_comment_dislike( $comment_id, -1 );
                        unset( $comment_dislike_ips[$user_ip] );
                    }

                    $comment_like_ips[$user_ip] = $user_ip;
                } else {
                    $json['like_count'] = $this->set_comment_like( $comment_id, -1 );

                    if ( !in_array( $user_ip, $comment_dislike_ips ) ) {
                        $json['dislike_count'] = $this->set_comment_dislike( $comment_id, 0 );
                    } else {
                        $json['dislike_count'] = $this->set_comment_dislike( $comment_id, -1 );
                        unset( $comment_dislike_ips[$user_ip] );
                    }

                    unset( $comment_like_ips[$user_ip] );
                }


            } elseif ( $type == 'dislike' ) {
                if ( !in_array( $user_ip, $comment_dislike_ips ) ) {
                    $json['dislike_count'] = $this->set_comment_dislike( $comment_id, 1 );

                    if ( !in_array( $user_ip, $comment_like_ips ) ) {
                        $json['like_count'] = $this->set_comment_like( $comment_id, 0 );
                    } else {
                        $json['like_count'] = $this->set_comment_like( $comment_id, -1 );
                        unset( $comment_like_ips[$user_ip] );
                    }

                    $comment_dislike_ips[$user_ip] = $user_ip;
                } else {
                    $json['dislike_count'] = $this->set_comment_dislike( $comment_id, -1 );

                    if ( !in_array( $user_ip, $comment_like_ips ) ) {
                        $json['like_count'] = $this->set_comment_like( $comment_id, 0 );
                    } else {
                        $json['like_count'] = $this->set_comment_like( $comment_id, -1 );
                        unset( $comment_like_ips[$user_ip] );
                    }

                    unset( $comment_dislike_ips[$user_ip] );
                }
            }

            update_comment_meta( $comment_id, 'tcw_comment_like_ips', $comment_like_ips );
            update_comment_meta( $comment_id, 'tcw_comment_dislike_ips', $comment_dislike_ips );

            wp_send_json_success( $json );
        }

        /**
         * @param int $comment_id
         * @param int $step
         *
         * @return int|mixed
         */
        public function set_comment_like( $comment_id, $step = 1 ) {
            $like_count = get_comment_meta( $comment_id, 'tcw_comment_like_count', true );
            if ( empty( $like_count ) ) {
                $like_count = 0;
            }

            if ( $step == 0 )
                return $like_count;

            $like_count += $step;

            if ( $like_count < 0 )
                $like_count = 0;

            update_comment_meta( $comment_id, 'tcw_comment_like_count', $like_count );

            return $like_count;
        }


        /**
         * @param int $comment_id
         * @param int $step
         *
         * @return int|mixed
         */
        public function set_comment_dislike( $comment_id, $step = 1 ) {
            $dislike_count = get_comment_meta( $comment_id, 'tcw_comment_dislike_count', true );
            if ( empty( $dislike_count ) ) {
                $dislike_count = 0;
            }

            if ( $step == 0 )
                return $dislike_count;

            $dislike_count += $step;

            if ( $dislike_count < 0 )
                $dislike_count = 0;

            update_comment_meta( $comment_id, 'tcw_comment_dislike_count', $dislike_count );

            return $dislike_count;
        }


        /**
         * Filter: manage_edit-comments_columns
         *
         * @param $columns
         *
         * @since 1.0.0
         * @return mixed
         */
        public function add_like_dislike_column( $columns ) {
            $columns['tcw_comment_like_column']    = TCW::get_option( 'wc_comments_like_hover_text', __( 'Likes', TCW_TEXTDOMAIN ) );
            $columns['tcw_comment_dislike_column'] = TCW::get_option( 'wc_comments_dislike_hover_text', __( 'Dislikes', TCW_TEXTDOMAIN ) );

            return $columns;
        }


        /**
         * Filter: manage_comments_custom_column
         *
         * @param $column
         * @param $comment_id
         *
         * @since 1.0.0
         */
        public function display_like_dislike_values( $column, $comment_id ) {
            if ( in_array( $column, [ 'tcw_comment_like_column', 'tcw_comment_dislike_column' ] ) ) {
                $key   = str_replace( 'column', 'count', $column );
                $count = get_comment_meta( $comment_id, $key, true );

                if ( empty( $count ) ) {
                    $count = 0;
                }

                echo $count;
            }
        }


        /**
         * Returns visitors IP address
         *
         * @since   1.0.0
         * @return  string $ip
         */
        public function get_user_IP() {
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];

            if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
                $ip = $client;
            } elseif ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
                $ip = $forward;
            } else {
                $ip = $remote;
            }

            return $ip;
        }


    }
} // End if class_exists check


/**
 * Main instance of TCW_Comments_Like_Dislike.
 * Returns the main instance of TCW_Comments_Like_Dislike to prevent the need to use globals.
 *
 * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
 *
 * @since   1.0.0
 * @return  TCW_Comments_Like_Dislike - Main instance
 */
function tcw_comments_like_dislike( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
    return TCW_Comments_Like_Dislike::instance( $WOOCOMMERCE );
}