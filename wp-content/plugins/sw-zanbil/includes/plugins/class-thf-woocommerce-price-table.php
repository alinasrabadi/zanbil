<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( !class_exists( 'TCW_Price_Table' ) ) {

    /**
     * Main TCW_Price_Table class
     * @since       1.0.0
     */
    class TCW_Price_Table {
        /**
         * The single instance of the class.
         *
         * @var TCW_Price_Table
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
        public $template_path = 'price-table/';


        /**
         * Get active instance
         *
         * @param TCW_PLUGIN_WooCommerce $WOOCOMMERCE
         *
         * @since   1.0.0
         * @static
         * @see     tcw_price_table()
         * @return  TCW_Price_Table - Main instance
         */
        public static function instance( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
            if ( is_null( self::$_instance ) ) {
                self::$WOOCOMMERCE = $WOOCOMMERCE;
                self::$_instance   = new self();
            }
            return self::$_instance;
        }

        /**
         * TCW_Price_Table constructor.
         */
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
            return TCW::get_option( 'wc_price_table' );
        }

        /**
         * Run action and filter hooks
         *
         * @private
         * @since 1.0.0
         */
        private function hooks() {
            add_action( 'woocommerce_product_options_pricing', [ $this, 'woocommerce_product_options_pricing' ] );
            add_action( 'woocommerce_process_product_meta', [ $this, 'woocommerce_process_product_meta' ] );

            add_action( 'woocommerce_variation_options_pricing', [ $this, 'woocommerce_variation_options_pricing' ], 10, 3 );
            add_action( 'woocommerce_save_product_variation', [ $this, 'woocommerce_save_product_variation' ], 10, 2 );
        }

        /**
         * @since 1.0.0
         */
        public function woocommerce_product_options_pricing() {

            // Field: Old Price
            $label = sprintf( __( 'Old price (%s)', TCW_TEXTDOMAIN ), get_woocommerce_currency_symbol() );
            woocommerce_wp_text_input( array(
                'id'        => '_tcw_old_price',
                'label'     => $label,
                'data_type' => 'price',
            ) );
        }


        /**
         * @param $post_id
         *
         * @since 1.0.0
         */
        public function woocommerce_process_product_meta( $post_id ) {
            // Field: Old Price
            $old_price = wc_clean( $_POST['_tcw_old_price'] );
            $this->set_old_price( $post_id, $old_price );
        }


        /**
         * @param $loop
         * @param $variation_data
         * @param $variation
         *
         * @since 1.0.0
         */
        public function woocommerce_variation_options_pricing( $loop, $variation_data, $variation ) {

            // Field: Old Price
            $label = sprintf( __( 'Old price (%s)', TCW_TEXTDOMAIN ), get_woocommerce_currency_symbol() );
            woocommerce_wp_text_input( array(
                'id'            => "variable_tcw_old_price_{$loop}",
                'name'          => "variable_tcw_old_price[{$loop}]",
                'label'         => $label,
                'data_type'     => 'price',
                'wrapper_class' => 'form-row form-row-first',
                'placeholder'   => __( 'Variation Old price', '' ),
                'value'         => get_post_meta( $variation->ID, '_tcw_old_price', true ),
            ) );
        }


        /**
         * @param integer $variation_id
         * @param integer $i
         *
         * @since 1.0.0
         */
        public function woocommerce_save_product_variation( $variation_id, $i ) {
            // Field: Old Price
            $old_price = wc_clean( $_POST['variable_tcw_old_price'][$i] );
            $this->set_old_price( $variation_id, $old_price );
        }

        /**
         * @param $product_id
         * @param $value
         *
         * @since 1.0.0
         */
        public function set_old_price( $product_id, $value ) {
            $product = wc_get_product( $product_id );
            $product->update_meta_data( '_tcw_old_price', $value );
            $product->save();
        }

        /**
         * Price Table Get Product Data
         *
         * @param integer $ID
         *
         * @since   1.0.0
         * @return  array|WP_Error|WC_Product
         */
        public function get_product( $ID ) {
            if ( !self::is_active() || empty( $ID ) ) return [];

            $product = wc_get_product( $ID );

            if ( !is_wp_error( $product ) ) {

                $title         = $product->get_title();
                $thumbnail     = $product->get_image();
                $link          = $product->get_permalink();
                $regular_price = intval( $product->get_regular_price() );
                $sale_price    = intval( $product->get_sale_price() );
                $new_price     = empty( $sale_price ) ? $regular_price : $sale_price;
                $old_price     = intval( get_post_meta( $ID, '_tcw_old_price', true ) ) ? : $regular_price;
                $diff          = absint( $new_price - $old_price );
                // $status        = '<i class="fa fa-arrow-up"></i>';
                $status       = 'increase';
                $status_title = __( 'Price increase', TCW_TEXTDOMAIN );
                $status_icon  = '<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDQ0NC44MTkgNDQ0LjgxOSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDQ0LjgxOSA0NDQuODE5OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTQzMy45NjgsMjc4LjY1N0wyNDguMzg3LDkyLjc5Yy03LjQxOS03LjA0NC0xNi4wOC0xMC41NjYtMjUuOTc3LTEwLjU2NmMtMTAuMDg4LDAtMTguNjUyLDMuNTIxLTI1LjY5NywxMC41NjYgICBMMTAuODQ4LDI3OC42NTdDMy42MTUsMjg1Ljg4NywwLDI5NC41NDksMCwzMDQuNjM3YzAsMTAuMjgsMy42MTksMTguODQzLDEwLjg0OCwyNS42OTNsMjEuNDExLDIxLjQxMyAgIGM2Ljg1NCw3LjIzLDE1LjQyLDEwLjg1MiwyNS42OTcsMTAuODUyYzEwLjI3OCwwLDE4Ljg0Mi0zLjYyMSwyNS42OTctMTAuODUyTDIyMi40MSwyMTMuMjcxTDM2MS4xNjgsMzUxLjc0ICAgYzYuODQ4LDcuMjI4LDE1LjQxMywxMC44NTIsMjUuNywxMC44NTJjMTAuMDgyLDAsMTguNzQ3LTMuNjI0LDI1Ljk3NS0xMC44NTJsMjEuNDA5LTIxLjQxMiAgIGM3LjA0My03LjA0MywxMC41NjctMTUuNjA4LDEwLjU2Ny0yNS42OTNDNDQ0LjgxOSwyOTQuNTQ1LDQ0MS4yMDUsMjg1Ljg4NCw0MzMuOTY4LDI3OC42NTd6IiBmaWxsPSIjNGNhZjdlIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />';
                if ( $new_price < $old_price ) {
                    $status       = 'decrease';
                    $status_title = __( 'Price decrease', TCW_TEXTDOMAIN );
                    $status_icon  = '<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDQ0NC44MTkgNDQ0LjgxOSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDQ0LjgxOSA0NDQuODE5OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTQzNC4yNTIsMTE0LjIwM2wtMjEuNDA5LTIxLjQxNmMtNy40MTktNy4wNC0xNi4wODQtMTAuNTYxLTI1Ljk3NS0xMC41NjFjLTEwLjA5NSwwLTE4LjY1NywzLjUyMS0yNS43LDEwLjU2MSAgIEwyMjIuNDEsMjMxLjU0OUw4My42NTMsOTIuNzkxYy03LjA0Mi03LjA0LTE1LjYwNi0xMC41NjEtMjUuNjk3LTEwLjU2MWMtOS44OTYsMC0xOC41NTksMy41MjEtMjUuOTc5LDEwLjU2MWwtMjEuMTI4LDIxLjQxNiAgIEMzLjYxNSwxMjEuNDM2LDAsMTMwLjA5OSwwLDE0MC4xODhjMCwxMC4yNzcsMy42MTksMTguODQyLDEwLjg0OCwyNS42OTNsMTg1Ljg2NCwxODUuODY1YzYuODU1LDcuMjMsMTUuNDE2LDEwLjg0OCwyNS42OTcsMTAuODQ4ICAgYzEwLjA4OCwwLDE4Ljc1LTMuNjE3LDI1Ljk3Ny0xMC44NDhsMTg1Ljg2NS0xODUuODY1YzcuMDQzLTcuMDQ0LDEwLjU2Ny0xNS42MDgsMTAuNTY3LTI1LjY5MyAgIEM0NDQuODE5LDEzMC4yODcsNDQxLjI5NSwxMjEuNjI5LDQzNC4yNTIsMTE0LjIwM3oiIGZpbGw9IiNmZjYzN2QiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />';
                } elseif ( $old_price === $new_price ) {
                    $diff         = '---';
                    $status       = 'unchanged';
                    $status_title = __( 'Price Unchanged', TCW_TEXTDOMAIN );
                    $status_icon  = '---';
                }

                $status_icon = '<span data-toggle="tooltip" title="' . $status_title . '">' . $status_icon . '</span>';

                if ( $product->is_type( 'variation' ) ) {
                    $va_attrs = $product->get_attributes();

                    $va_list_attributes = [];

                    if ( !empty( $va_attrs ) && is_array( $va_attrs ) ) {
                        foreach ( $va_attrs as $attr_key => $attr_value ) {
                            $taxonomy             = urldecode( $attr_key );
                            $attr_label           = wc_attribute_label( $taxonomy );
                            $attr_value           = urldecode( $attr_value );
                            $va_list_attributes[] = "<strong>$attr_label</strong> $attr_value";
                        }
                    }

                    $title = $title . ' => ' . implode( ' |  ', $va_list_attributes );
                }

                return [
                    'ID'           => $ID,
                    'title'        => $title,
                    'thumb'        => $thumbnail,
                    'link'         => $link,
                    'old_price'    => tcw_price( $old_price ),
                    'new_price'    => tcw_price( $new_price ),
                    'diff'         => tcw_price( $diff ),
                    'status'       => $status,
                    'status_title' => $status_title,
                    'status_icon'  => $status_icon,
                ];
            }

            return $product;
        }

        /**
         * @param bool  $title
         * @param array $ids
         */
        public function get_shortcode_content( $title = false, $ids = [] , $listlink = false ) {
            if ( !self::is_active() ) return;

            if ( $this !== false && empty( $title ) ) $title = TCW::get_option( 'wc_price_table_title' );
            if ( empty( $listlink ) ) $listlink = '#';

            wp_enqueue_script( 'thf-customize-wc-price-table' );
            wp_enqueue_style( 'thf-customize-wc-price-table' );

            TCW_HELPER::get_template_part( $this->template_path . 'price-table.php', compact( 'title', 'ids','listlink' ) );
        }
    }
} // End if class_exists check

/**
 * Main instance of TCW_Price_Table.
 * Returns the main instance of TCW_Price_Table to prevent the need to use globals.
 *
 * @param   TCW_PLUGIN_WooCommerce $WOOCOMMERCE
 *
 * @since   1.0.0
 * @return  TCW_Price_Table - Main instance
 */
function tcw_price_table( TCW_PLUGIN_WooCommerce $WOOCOMMERCE ) {
    return TCW_Price_Table::instance( $WOOCOMMERCE );
}