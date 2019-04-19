<?php
add_theme_support( 'woocommerce' );

function zanbil_custom_product_scripts(){
	wp_dequeue_script('wc-add-to-cart-variation');	
	wp_dequeue_script('wc-single-product');
	wp_deregister_script('wc-add-to-cart-variation');
	wp_deregister_script('wc-single-product');
	wp_enqueue_script( 'wc-single-product', get_template_directory_uri() . '/js/woocommerce/single-product.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'wc-add-to-cart-variation', get_template_directory_uri() . '/js/woocommerce/add-to-cart-variation.min.js', array( 'jquery', 'wp-util' ),null, true  );
}
add_action( 'wp_enqueue_scripts', 'zanbil_custom_product_scripts', 110 );
/*
** WooCommerce Compare Version
*/

if( !function_exists( 'sw_woocommerce_version_check' ) ) :
	function sw_woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;
		if( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}else{
			return false;
		}
	}
endif;
/**************************************/
add_filter( 'wp_list_categories', 'zanbil_list_categories' );
function zanbil_list_categories( $output ){
	$output = preg_replace('~\((\d+)\)(?=\s*+<)~', '<span class="number-count">$1</span>', $output);
	return $output;
}
/*remove woo breadcrumb*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
/**
* Get the product thumbnail, or the placeholder if not set.
*
* @subpackage Loop
* @param string $size (default: 'shop_catalog')
* @param int $deprecated1 Deprecated since WooCommerce 2.0 (default: 0)
* @param int $deprecated2 Deprecated since WooCommerce 2.0 (default: 0)
* @return string
*/
function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $deprecated1 = 0, $deprecated2 = 0 ) {
global $post;
if ( has_post_thumbnail() ) {
return '<a href="' . get_permalink( $post->ID ) . '">' . get_the_post_thumbnail( $post->ID, $size ) . '</a>';
} elseif ( wc_placeholder_img_src() ) {
return wc_placeholder_img( $size );
}
}
}



/*********QUICK VIEW PRODUCT**********/
add_action("wp_ajax_zanbil_quickviewproduct", "zanbil_quickviewproduct");
add_action("wp_ajax_nopriv_zanbil_quickviewproduct", "zanbil_quickviewproduct");
function zanbil_quickviewproduct(){
	
	$productid = (isset($_REQUEST["post_id"]) && $_REQUEST["post_id"]>0) ? $_REQUEST["post_id"] : 0;
	
	$query_args = array(
		'post_type'	=> 'product',
		'p'			=> $productid
	);
	$outputraw = $output = '';
	$r = new WP_Query($query_args);
	if($r->have_posts()){ 

		while ($r->have_posts()){ $r->the_post(); setup_postdata($r->post);
			global $product;
			ob_start();
			wc_get_template_part( 'content', 'quickview-product' );
			$outputraw = ob_get_contents();
			ob_end_clean();
		}
	}
	$output = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $outputraw);
	echo $output;exit();
}

add_filter( 'product_cat_class', 'zanbil_product_category_class', 2 );
function zanbil_product_category_class( $classes, $category = null ){

		$classes[] = 'col-lg-4 col-md-4 col-sm-6 col-xs-6 col-mb-12';

	return $classes;
}

/* Add Product Tag To Tabs */
add_filter( 'woocommerce_product_tabs', 'zanbil_tab_tag' );
function zanbil_tab_tag($tabs){
	global $post, $product;
	$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
	if ( count( $tag_count ) > 1 ) {
		$tabs['product_tag'] = array(
			'title'    => esc_html__( 'Tags', 'zanbil' ),
			'priority' => 11,
			'callback' => 'zanbil_single_product_tab_tag'
		);
	}
	return $tabs;
}
function zanbil_single_product_tab_tag(){
	global $post, $product;
	echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'zanbil' ) . ' ', '</span>' );
}
/* single product */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );



// The saving price
function you_save_echo_product() {
	global $product;

	// works for Simple and Variable type
	$regular_price 	= get_post_meta( $product->get_id(), '_regular_price', true ); // 36.32
	$sale_price 	= get_post_meta( $product->get_id(), '_sale_price', true ); // 24.99
		
	if( !empty($sale_price) ) {
	
		$saved_amount 		= $regular_price - $sale_price;
		$currency_symbol 	= get_woocommerce_currency_symbol();

		$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
		?>
			<p class="you_save_price">تخفیف :  <?php echo ''.number_format($saved_amount, 0, ',', ',').' '.$currency_symbol.''; ?></p>				
		<?php		
	} 
		
}
add_action( 'woocommerce_single_product_summary', 'you_save_echo_product', 11 ); // hook number

// Add this to your theme's functions.php
function kia_add_script_to_footer(){
    if( ! is_admin() ) { ?>
    <script>
    jQuery(document).ready(function($){
    $('.quantity').on('click', '.plus', function(e) {
        $input = $(this).prev('input.qty');
        var val = parseInt($input.val());
        var step = $input.attr('step');
        step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
        $input.val( val + step ).change();
    });
    $('.quantity').on('click', '.minus', 
        function(e) {
        $input = $(this).next('input.qty');
        var val = parseInt($input.val());
        var step = $input.attr('step');
        step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
        if (val > 0) {
            $input.val( val - step ).change();
        } 
    });
});
</script>
<?php }
}
add_action( 'wp_footer', 'kia_add_script_to_footer' );

//remove dokan seller tab
if( class_exists( 'Dokan_Vendor' ) ) {
remove_action( 'woocommerce_product_tabs', 'dokan_seller_product_tab');
}
/* cart */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );


/* Product loop content */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'zanbil_loop_product_title', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'zanbil_product_description', 11 );
add_action( 'woocommerce_after_shop_loop_item', 'zanbil_product_addcart_start', 1 );
add_action( 'woocommerce_after_shop_loop_item', 'zanbil_product_addcart_mid', 20 );
add_action( 'woocommerce_after_shop_loop_item', 'zanbil_product_addcart_end', 99 );
function zanbil_loop_product_title(){
	?>
		<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title() ?></a></h2>
	<?php
}
function zanbil_product_description(){
	global $post;
	if ( ! $post->post_excerpt ) return;
	
	echo '<div class="item-description">'.wp_trim_words( $post->post_excerpt, 50 ).'</div>';
}
function zanbil_product_addcart_start(){
	echo '<div class="item-bottom clearfix">';
}
function zanbil_product_addcart_end(){
	echo '</div>';
}
function zanbil_product_addcart_mid(){
	global $product, $post;
	$product_id = $post->ID;
	$quickview = 1;
	if( function_exists( 'zanbil_options' ) ){
		$quickview = zanbil_options()->getCpanelValue( 'product_quickview' );
	}
	$html ='';	
	/* quickview */
	if( $quickview ) : 
		$nonce = wp_create_nonce("zanbil_quickviewproduct_nonce");
		$link = admin_url('admin-ajax.php?ajax=true&amp;action=zanbil_quickviewproduct&amp;post_id='.$post->ID.'&amp;nonce='.$nonce);
		$html .= '<a href="'. $link .'" data-fancybox-type="ajax" class="group fancybox fancybox.ajax">'.apply_filters( 'out_of_stock_add_to_cart_text', __( 'نمایش سریع محصول ', 'zanbil' ) ).'</a>';	
	endif;
	echo $html;
}
/*minicart via Ajax*/
$zanbil_header  = zanbil_options()->getCpanelValue( 'header_style' );
	$filter = sw_woocommerce_version_check( $version = '3.0.3' ) ? 'woocommerce_add_to_cart_fragments' : 'add_to_cart_fragments';
	add_filter($filter, 'zanbil_add_to_cart_fragment', 100);
	function zanbil_add_to_cart_fragment( $fragments ) {
		ob_start();
		?>
		<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
		<?php
		$fragments['.topbar-minicart'] = ob_get_clean();
		return $fragments;
}
// WooCommerce Rename Checkout Fields
add_filter( 'woocommerce_checkout_fields' , 'custom_rename_wc_checkout_fields' );

// Change placeholder and label text
function custom_rename_wc_checkout_fields( $fields ) {
$fields['billing']['billing_first_name']['placeholder'] = 'نام (لازم) :';
$fields['billing']['billing_first_name']['label'] = '';

$fields['billing']['billing_last_name']['placeholder'] = 'نام خانوادگی (لازم) :';
$fields['billing']['billing_last_name']['label'] = '';

$fields['billing']['billing_address_1']['placeholder'] = 'آدرس :';
$fields['billing']['billing_address_1']['label'] = '';

$fields['billing']['billing_postcode']['placeholder'] = 'کد پستی (لازم)';
$fields['billing']['billing_postcode']['label'] = '';

$fields['billing']['billing_phone']['placeholder'] = 'شماره تماس (لازم) :';
$fields['billing']['billing_phone']['label'] = '';

$fields['billing']['billing_email']['placeholder'] = 'آدرس ایمیل (لازم) : ';
$fields['billing']['billing_email']['label'] = '';

unset($fields['billing']['billing_address_2']);

unset($fields['billing']['billing_company']);
return $fields;
}
/*
**Hook into review for rick snippet
*/
add_action( 'woocommerce_review_before_comment_meta', 'zanbil_title_ricksnippet', 10 ) ;
function zanbil_title_ricksnippet(){
	global $post;
	echo '<span class="hidden" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
    <span itemprop="name">'. $post->post_title .'</span>
  </span>';
}

/******************** new code *****************************/
/**
 * update time
 * @since 1.0.0
 */
 function cfwc_create_custom_field_date() {
woocommerce_wp_text_input( array( 'id' => 'pplu', 'class' => '', 'label' => 'تاریخ آخرین بروزرسانی قیمت ' ) );

}
add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field_date' );

/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_custom_field_date( $post_id ) {
update_post_meta( $post_id, 'pplu', stripslashes( $_POST['pplu'] ) );
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field_date' );

add_action( 'woocommerce_after_add_to_cart_form', 'bbloomer_custom_action_date', 5 );
 
function bbloomer_custom_action_date() {
	if( !empty( get_post_meta(get_the_ID(), 'pplu', true ) ) ) { echo '<p class="ppla">آخرین بروزرسانی قیمت : '.get_post_meta(get_the_ID(), 'pplu', true ).'</p>';}
}
/**
 * Display the custom text field
 * @since 1.0.0
 */
function cfwc_create_custom_field() {
woocommerce_wp_text_input( array( 'id' => 'orario', 'class' => '', 'label' => 'توضیحات ویژگی' ) );
woocommerce_wp_text_input( array( 'id' => 'luogo_evento', 'class' => '', 'label' => 'توضیحات ویژگی' ) );
woocommerce_wp_text_input( array( 'id' => 'indirizzo', 'class' => '', 'label' => 'توضیحات ویژگی' ) );
woocommerce_wp_text_input( array( 'id' => 'zona', 'class' => '', 'label' => 'توضیحات ویژگی' ) );
woocommerce_wp_text_input( array( 'id' => 'contatti', 'class' => '', 'label' => 'توضیحات ویژگی' ) );

}
add_action( 'woocommerce_product_options_advanced', 'cfwc_create_custom_field' );
/**
 * Save the custom field
 * @since 1.0.0
 */
function cfwc_save_custom_field( $post_id ) {
update_post_meta( $post_id, 'orario', stripslashes( $_POST['orario'] ) );
update_post_meta( $post_id, 'luogo_evento', stripslashes( $_POST['luogo_evento'] ) );
update_post_meta( $post_id, 'indirizzo', stripslashes( $_POST['indirizzo'] ) );
update_post_meta( $post_id, 'zona', stripslashes( $_POST['zona'] ) );
update_post_meta( $post_id, 'contatti', stripslashes( $_POST['contatti'] ) );
}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );

/*
* show category in shop 
*
*/
function tutsplus_product_subcategories( $args = array() ) {
	
$parentid = get_queried_object_id();         
$args = array( 'parent' => $parentid ); 
$terms = get_terms( 'product_cat', $args );
if( is_tax( 'product_cat' ) ) {
	$cat = get_queried_object();
	if( 0 == $cat->parent  ) {

	if ( $terms ) {
		echo '<div id="dataList" class="category-cards-list">';
		foreach ( $terms as $term ) {
			echo '<div class="category-card hiddencc"><a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">'; 

				$thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ); // Get Category Thumbnail
				$image = wp_get_attachment_url( $thumbnail_id );

				if ( $image ) {
					echo '<div class="card-grad-back"><img src="' . $image . '" alt="' . $term->name . '" /></div>';
				}
				echo '<h2>'.$term->name.'</h2></a>';            				
				$wct_id = $term->term_id;
				$wcatTerms = get_terms('product_cat', array('hide_empty' => 0, 'orderby' => 'ASC', 'parent' => $wct_id, ));
				echo '<ul>';				
				foreach($wcatTerms as $wcatTerm) { 
				?>				
					<li><a href="<?php echo get_term_link( $wcatTerm->slug, $wcatTerm->taxonomy ); ?>"><?php echo $wcatTerm->name; ?></a></li>								
				<?php }
			echo '</ul></div>';				
		}
		echo '<input id="moreButton" type="button" value="نمایش بیشتر" onclick="showMore()"/></div>';
	}
	}
}
}
add_action( 'woocommerce_before_shop_aside', 'tutsplus_product_subcategories', 20 );

/**
ordering
**/
add_action( 'woocommerce_before_shop_loop','zanbil_woommerce_view_mode_wrap',15 );
function zanbil_woommerce_view_mode_wrap () {
	global $data;

	$html = '<div class="view-top clearfix pull-right">';
	$html .= '<div class="view-mode-wrap">
				<div class="view-mode">
						<a href="javascript:void(0)" class="grid-view active" title="'. esc_attr__('Grid view', 'zanbil').'"><span>نمایش جدولی</span></a>
						<a href="javascript:void(0)" class="list-view" title="'. esc_attr__('List view', 'zanbil') .'"><span>نمایش لیستی</span></a>
				</div>	
			</div>';
	$html .= '</div>';
	
	echo $html;
}
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_stock', 10 );
function woocommerce_template_loop_stock() {
    global $product;
    if ( ! $product->is_in_stock() )
        echo '<div class="archive-out-of-stock">ناموجــود</div>';
}

/**
 * @snippet       Show only lowest prices in WooCommerce variable products
 * @how-to        Watch tutorial @ https://wpglorify.com/?p=2082
 * @author        Garry Singh
 * @tested        WooCommerce 3.0.8
 */
 
function wc_varb_price_range( $wcv_price, $product ) {
 
    $prefix = sprintf('%s: ', __('<small>شروع از </small>', 'wcvp_range'));
 
    $wcv_reg_min_price = $product->get_variation_regular_price( 'min', true );
    $wcv_min_sale_price    = $product->get_variation_sale_price( 'min', true );
    $wcv_max_price = $product->get_variation_price( 'max', true );
    $wcv_min_price = $product->get_variation_price( 'min', true );
 
    $wcv_price = ( $wcv_min_sale_price == $wcv_reg_min_price ) ?
        wc_price( $wcv_reg_min_price ) :
        '<del>' . wc_price( $wcv_reg_min_price ) . '</del>' . '<ins>' . wc_price( $wcv_min_sale_price ) . '</ins>';
 
    return ( $wcv_min_price == $wcv_max_price ) ?
        $wcv_price :
        sprintf('%s%s', $prefix, $wcv_price);
}
 
add_filter( 'woocommerce_variable_sale_price_html', 'wc_varb_price_range', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wc_varb_price_range', 10, 2 );

/**
 * Set WooCommerce image dimensions upon theme activation
 */
// Remove each style one by one
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Display Price For Variable Product With Same Variations Prices
add_filter('woocommerce_available_variation', function ($value, $object = null, $variation = null) {
if ($value['price_html'] == '') {
    $value['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
}
return $value;}, 10, 3);

//upsell
add_filter( 'woocommerce_upsell_display_args', 'custom_woocommerce_upsell_display_args' );

function custom_woocommerce_upsell_display_args( $args ) {
  $args['posts_per_page'] = 4; // Change this number
  $args['columns']        = 4; // This is the number shown per row.
  return $args;
}
// //wp_dashboard_setup
// add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
  
// function my_custom_dashboard_widgets() {
// global $wp_meta_boxes;
 
// wp_add_dashboard_widget('custom_help_widget', 'پارسیان وب', 'custom_dashboard_help');
// }
 
// function custom_dashboard_help() {
// echo '<a target="_blank" href="https://www.rtl-theme.com/author/mahmoodbeheshti/"><img class="aligncenter size-full wp-image-1124" src="http://parsianweb.ir/avin-dashborad.png" alt="" width="100%" height="auto" /></a>';
// }
?>