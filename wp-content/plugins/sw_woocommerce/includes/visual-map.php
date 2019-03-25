<?php 

$attributes =	    array(
					'type' => 'attach_image',
					'heading' => __( 'Icon Image', 'sw_woocommerce' ),
					'param_name' => 'image',
					'value' => '',
					'description' => __( 'Select image from media library.', 'sw_woocommerce' ),
				);
vc_add_param('vc_tta_section', $attributes);
add_filter('vc-tta-get-params-tabs-list', 'change_gettabslist',10,2);
function change_gettabslist( $atts, $content ) {
	
		$isPageEditabe = vc_is_page_editable();
		$html = array();
		$html[] = '<div class="vc_tta-tabs-container">';
		$html[] = '<ul class="vc_tta-tabs-list">';
		if ( ! $isPageEditabe ) {
		     $tabs = WPBakeryShortCode_VC_Tta_Section::$tta_base_shortcode;

			foreach ( WPBakeryShortCode_VC_Tta_Section::$section_info as $nth => $section ) {
				$classes = array( 'vc_tta-tab' );
				if ( ( $nth + 1 ) === 1 ) {
					$classes[] = $tabs->activeClass;
				}
				$title = '<span class="vc_tta-title-text">' . $section['title'] . '</span>';
				if ( $section['image'] != '' ) {
					$image = $section['image'];
				}
				$a_html = '<a href="#' . $section['tab_id'] . '" data-vc-tabs data-vc-container=".vc_tta">'.wp_get_attachment_image( $image, 'thumbnail' ). $title . '</a>';
				$html[] = '<li class="' . implode( ' ', $classes ) . '" data-vc-tab>' .$a_html . '</li>';
			}
		}

		$html[] = '</ul>';
		$html[] = '</div>';
		return $html;
}
add_action( 'vc_before_init', 'SW_shortcodeVC' );
vc_add_shortcode_param( 'my_param', 'my_param_settings_field' );
function my_param_settings_field( $settings, $value ) {
    $output = '';
    $values = explode( ',', $value );
	$output .= '<select name="'
	           . $settings['param_name']
	           . '" class="wpb_vc_param_value wpb-input wpb-select '
	           . $settings['param_name']
	           . ' ' . $settings['type']
	           . '" multiple="multiple">';
	if ( is_array( $value ) ) {
		$value = isset( $value['value'] ) ? $value['value'] : array_shift( $value );
	}
	if ( ! empty( $settings['value'] ) ) {
		foreach ( $settings['value'] as $index => $data ) {
			if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
				$option_label = $data;
				$option_value = $data;
			} elseif ( is_numeric( $index ) && is_array( $data ) ) {
				$option_label = isset( $data['label'] ) ? $data['label'] : array_pop( $data );
				$option_value = isset( $data['value'] ) ? $data['value'] : array_pop( $data );
			} else {
				$option_value = $data;
				$option_label = $index;
			}
			$selected = '';
	 		$option_value_string = (string) $option_value;
	 		$value_string = (string) $value;
	 		$selected = (is_array($values) && in_array($option_value, $values))?' selected="selected"':'';
			$option_class = str_replace( '#', 'hash-', $option_value );
			$output .= '<option class="' . esc_attr( $option_class ) . '" value="' . esc_attr( $option_value ) . '"' . $selected . '>'
			           . htmlspecialchars( $option_label ) . '</option>';
		}
	}
	$output .= '</select>';

	return $output;
}
//vc_add_shortcode_param('my_param', 'my_param_settings_field', get_template_directory_uri().'/js/select2.min.js');
vc_add_shortcode_param( 'date', 'zanbil_date_vc_setting' );

function zanbil_date_vc_setting( $settings, $value ) {
	return '<div class="vc_date_block">'
		 .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
		 esc_attr( $settings['param_name'] ) . ' ' .
		 esc_attr( $settings['type'] ) . '_field" type="date" value="' . esc_attr( $value ) . '" placeholder="dd-mm-yyyy"/>' .
		'</div>'; 
}
function SW_shortcodeVC(){
$target_arr = array(
	__( 'Same window', 'sw_woocommerce' ) => '_self',
	__( 'New window', 'sw_woocommerce' ) => "_blank"
);	
$args = array(
			'type' => 'post',
			'child_of' => 0,
			'parent' => 0,
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => false,
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'number' => '',
			'taxonomy' => 'product_cat',
			'pad_counts' => false,

		);
		$product_categories_dropdown = array( __( 'All Categories Products', 'sw_woocommerce' ) => '' );
		$categories = get_categories( $args );
		foreach($categories as $category){
			$product_categories_dropdown[$category->name] = $category -> slug;
		}
$menu_locations_array = array( __( 'All Categories', 'sw_woocommerce' ) => '' );
$menu_locations = wp_get_nav_menus();	
foreach ($menu_locations as $menu_location){
	$menu_locations_array[$menu_location->name] = $menu_location -> slug;
}

vc_map( array(
	'name' => 'شمارنده با بنر',
	'base' => 'banner_countdown',
	"icon" => get_template_directory_uri().'/assets/img/vc-icon.png',
	'category' => 'زنبیل',
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'params' => array(
		array(
			'type' => 'textfield',
				"heading" =>  "عنوان",
			'param_name' => 'title',
		),
		array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "",
					"heading" => "تصویر آیکون",
					"param_name" => "image_icon",
					"value" => '',
				 ),
		array(
			'type' => 'textfield',
			'heading' => 'توضیحات',
			'param_name' => 'description',
		),
		array(
			'type' => 'textfield',
			'heading' => 'کلاس اضافی',
			'param_name' => 'el_class',
		),
		array(
			'type' => 'attach_image',
			'heading' => 'عکس بنر',
			'param_name' => 'image',
		),
		array(
			'type' => 'date',
			'heading' => 'تاریخ شمارنده',
			'param_name' => 'date',
		),
		array(
			'type' => 'textfield',
			'heading' => 'لینک',
			'param_name' => 'url',
		),
	)
) );

$terms = get_terms( 'product_cat', array( 'parent' => '', 'hide_emty' => false ) );
if( count( $terms ) == 0 ){
	return ;
}
$term = array();
foreach( $terms as $cat ){
	$term[$cat->name] = $cat -> slug;
}

vc_map( array(
	  "name" => "نمایش محصول در منو",
	  "base" => "product_tab",
		"icon" => get_template_directory_uri().'/assets/img/vc-icon.png',
	  "class" => "",
	  "category" => "زنبیل",
	  "params" => array(
	  	array(
			'type' => 'textfield',
			'heading' => 'عنوان',
			'param_name' => 'title',
		),
		 array(
				"type" => "checkbox",
				"holder" => "div",
				"class" => "",
				"heading" => "دسته بندی",
				"param_name" => "category",
				"value" => $term,
		 ),
		 array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => "ترتیب بر اساس",
			"param_name" => "orderby",
			"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
			"description" => __( "Order By", 'sw_woocommerce' )
		 ),
		 array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => "نوع مرتب سازی",
			"param_name" => "order",
			"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
			"description" => __( "Order", 'sw_woocommerce' )
		 )
	  )
   ) );
	/***********************************
	*Accordion Recommend Product
	************************************/
	vc_map( array(
		'name' => 'آکاردئون محصولات پیشنهادی',
		'base' => 'accordion_popular_product',
		"icon" => get_template_directory_uri().'/assets/img/vc-icon.png',
		'category' => 'زنبیل',
		'class' => 'wpb_vc_wp_widget',
		'weight' => - 50,
		'params' => array(
			array(
				'type' => 'textfield',
				"heading" =>  "عنوان",
				'param_name' => 'title',
			),
			array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "",
					"heading" => "تصویر آیکون",
					"param_name" => "image_icon",
					"value" => '',
				 ),
			array(
				'type' => 'textfield',
				'heading' => 'تعداد محصول برای نمایش',
				'param_name' => 'numberposts',
				'admin_label' => true
			),
			
			array(
				'type' => 'textfield',
				'heading' => 'کلاس اضافی',
				'param_name' => 'el_class',
			),	
		)
	) );
///////////////////Recommend Product/////////////////////
vc_map( array(
	'name' => 'اسلایدر محصولات پیشنهادی',
	'base' => 'Recommend',
	"icon" => get_template_directory_uri().'/assets/img/vc-icon.png',
	'category' =>  'زنبیل',
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => 'عنوان',
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
		),
		array(
			'type' => 'textfield',
			'heading' => 'تعداد نمایش محصول در هر اسلاید',
			'param_name' => 'item_slide',
			'admin_label' => true,
		),
		
		array(
			'type' => 'textfield',
			'heading' => 'تعداد محصول برای نمایش',
			'param_name' => 'number',
			'admin_label' => true
		),
		
		array(
			'type' => 'textfield',
			'heading' => 'کلاس اضافی',
			'param_name' => 'el_class',
		),	
	)
) );
}
?>