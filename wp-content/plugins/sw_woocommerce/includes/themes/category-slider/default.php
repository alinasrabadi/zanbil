<?php 
$widget_id = isset( $widget_id ) ? $widget_id : 'category_slide_'.rand().time();
if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Please select a category for SW Woocommerce Category Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
	</div>';
}
?>
<div id="<?php echo 'slider_' . $widget_id; ?>" class="responsive-slider sw-category-slider loading"  data-append=".resp-slider-container" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<?php	if( $title1 != '' ){ ?>
	<div class="order-title">
		<h2><?php echo $title1; ?></h2>
	</div>
	<?php } ?>
	<div class="resp-slider-container">
		<div class="slider responsive">
		<?php
			if( !is_array( $category ) ){
				$category = explode( ',', $category );
			}
			foreach( $category as $cat ){
				$term = get_term_by('slug', $cat, 'product_cat');							
				$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ));
				$thumb = wp_get_attachment_image( $thumbnail_id,'full' );
		?>
			<div class="item item-product-cat">					
				<div class="item-image">
					<a href="<?php echo get_term_link( $term->term_id, 'product_cat' ); ?>"><?php echo $thumb; ?></a>
					<div class="item-content">
						<a href="<?php echo get_term_link( $term->term_id, 'product_cat' ); ?>"><h3><?php echo esc_html( $term->name ); ?><span><?php echo '('.esc_html($term->count).')'; ?></span></h3></a>
					</div>
				</div>
				
			</div>
		<?php } ?>
		</div>
	</div>
</div>		