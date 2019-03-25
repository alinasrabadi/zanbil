<?php 
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_brand_'.rand().time();
	$term_brands = array();
	if( !is_array( $category ) ){
		$category = explode( ',', $category );
	}
	if( count( $category ) == 1 && $category[0] == '' ){
		$terms = get_terms( 'product_brand', array( 'parent' => '', 'hide_empty' => 0, 'number' => $numberposts ) );
		foreach( $terms as $key => $cat ){
			$term_brands[$key] = $cat -> slug;
		}
	}else{
		$term_brands = $category;
	}
	
?>
	<div id="<?php echo $widget_id ?>" class="block-brand">
		<?php if( $title1 != '' ) : ?>
			<div class="order-title"><h2><strong><?php echo esc_html( $title1 ); ?></strong></h2></div>
		<?php endif; ?>
			
		<div class="block-content">
			<div class="brand-wrapper">
				<ul>
				<?php 
					foreach( $term_brands as $term_brand ) {
						$term = get_term_by( 'slug', $term_brand, 'product_brand' );	
						$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_bid', true ) );
						$thumb = wp_get_attachment_image( $thumbnail_id, array(350, 230) );
				?>
					<li>	
					   <?php echo '<a href="'. get_term_link( $term->term_id, 'product_brand' ).'">'.$thumb .'</a>'; ?>
						
					</li>
				<?php } ?>
				</ul>
			</div>
		</div>
	</div>
