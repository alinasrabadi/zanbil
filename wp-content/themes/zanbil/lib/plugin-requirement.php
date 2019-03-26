<?php
/***** Active Plugin ********/
require_once( get_template_directory().'/lib/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'zanbil_register_required_plugins' );
function zanbil_register_required_plugins() {
    $plugins = array(
        array(
            'name'               => esc_html__( 'ووکامرس', 'zanbil' ), 
            'slug'               => 'woocommerce', 
            'required'           => true, 
						'version'            => '3.1.0'
        ),
        
            array(
            'name'               => esc_html__( 'افزونه اختصاصی قالب پارسیان 2', 'zanbil' ),
            'slug'               => 'sw_woocommerce',
						'source'         		 => esc_url( 'http://zanbil.avin-tarh.ir/plugins/sw_woocommerce.zip' ), 
            'required'           => true,
        ),
        
         array(
            'name'               => esc_html__( 'افزونه اختصاصی قالب پارسیان 3', 'zanbil' ), 
            'slug'               => 'sw-responsive-post-slider', 
            'source'             => esc_url( 'http://zanbil.avin-tarh.ir/plugins/sw-responsive-post-slider.zip' ), 
            'required'           => true, 
        ),

        array(
            'name'               => esc_html__( 'افزونه صفحه ساز ویژال کامپوزر', 'zanbil' ), 
            'slug'               => 'js_composer', 
            'source'             => esc_url( 'http://zanbil.avin-tarh.ir/plugins/js_composer.zip' ), 
            'required'           => true, 
        ),         
    );
    $config = array();

    tgmpa( $plugins, $config );

}
add_action( 'vc_before_init', 'Zanbil_vcSetAsTheme' );
function Zanbil_vcSetAsTheme() {
    vc_set_as_theme();
}