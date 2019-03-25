<?php
/*
Element Description: VC Real-time Offer Box
*/

// Element Class
class vcPriceTable extends WPBakeryShortCode {

    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_price_table_mapping' ) );
        add_shortcode( 'vc_price_table', array( $this, 'vc_price_table_html' ) );
    }

    // Element Mapping
    public function vc_price_table_mapping() {

        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        // Map the block with vc_map()
        vc_map( array(
            'name'     => 'لیست قیمت',
            'base'     => 'vc_price_table',
            'category' => 'زنبیل',
            'icon'     => get_template_directory_uri().'/assets/img/vc-icon.png',
            'params'   => array(

                array(
                    'type'        => 'textfield',
                    'holder'      => 'h3',
                    'param_name'  => 'title',
                    'class'       => 'title-class',
                    'heading'     => __( 'Title', TCW_TEXTDOMAIN ),
                    'weight'      => 0,
                    'admin_label' => 0,
                    'value'       => TCW::get_option( 'wc_price_table_title' ),
                ),
                
                 array(
                    'type'        => 'textfield',
                    'param_name'  => 'listlink',
                    'class'       => 'list-page-link',
                    'heading'     =>  'لینک صفحه آرشیو لیست قیمت :',
                    'weight'      => 0,
                    'admin_label' => 0,
                    'value'       => '#',
                ),

                array(
                    'type'        => 'dropdown_multi_product',
                    'param_name'  => 'products',
                    'heading'     => __( 'Search Products', TCW_TEXTDOMAIN ),
                    'placeholder'  => __( 'Type something to start searching...', TCW_TEXTDOMAIN ),
                    'weight'      => 0,
                    'admin_label' => 0,
                    'value'       => [],
                ),
            ),
        ) );

    }


    // Element HTML
    public function vc_price_table_html( $atts ) {
        /** @var string $title */
        /** @var string|array $products */

        // Params extraction
        extract( shortcode_atts( array(
            'title'    => '',
            'listlink' => '',
            'products' => [],
        ), $atts ) );

        if ( !empty( $products ) ) {
            $products = explode( ',', $products );
            if ( !is_array( $products ) ) {
                $products = [];
            }
        }

        ob_start();
        tcw()->WOOCOMMERCE->PRICE_TABLE->get_shortcode_content( $title, $products , $listlink );
        return ob_get_clean();
    }

} // End Element Class