<?php 
	if( !is_singular( 'product' ) ){
		return ;
	}
	$related = array();
	global $post;
	if( function_exists( 'wc_get_related_products' ) ){
		$related = wc_get_related_products( $post->ID, $numberposts );
	}else{
		$related = $product->get_related($numberposts);
	}
	if ( sizeof( $related ) == 0 ) return;
	$args = apply_filters( 'woocommerce_related_products_args', array(
		'post_type'            => 'product',
		'ignore_sticky_posts'  => 1,
		'no_found_rows'        => 1,
		'posts_per_page'       => $numberposts,
		'post__in'             => $related,
		'post__not_in'         => array( $post->ID )
	) );
	$list = new WP_Query( $args );
	do_action( 'before' ); 
	if ( $list -> have_posts() ){
?>
	<div id="<?php echo 'slider_' . $widget_id; ?>" class="sw-woo-container-slider related-products responsive-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	    <div class="top-tab-slider">
			<div class="order-title">		
			<?php
				$titles = strpos($title1, ' ');
				$title = ($titles !== false) ? '<span>' . substr($title1, 0, $titles) . '</span>' .' '. substr($title1, $titles + 1): $title1 ;
				echo '<h2><strong>'. $title .'</strong></h2>';
			?>
			</div>
		</div>
		<div class="resp-slider-container">
			<div class="slider responsive">			
			<?php 
				while($list->have_posts()): $list->the_post();global $product, $post;
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
			?>
				<div class="item <?php echo esc_attr( $class )?>">
					<?php include( WCTHEME . '/default-item.php' ); ?>
				</div>
			<?php endwhile; wp_reset_postdata();?>
			</div>
		</div>					
	</div>
<?php
} 
?>