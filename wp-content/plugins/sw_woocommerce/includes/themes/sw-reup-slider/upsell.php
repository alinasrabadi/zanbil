<?php 
	if( !is_singular( 'product' ) ){
		return ;
	}
	global $product, $woocommerce, $woocommerce_loop;
	$upsells = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_upsell_ids() : $product->get_upsells();
	if( count($upsells) == 0 || is_archive() ) return ;	
	$default = array(
		'post_type' => 'product',
		'post__in'   => $upsells,
		'post_status' => 'publish',
		'showposts' => $numberposts
	);
	$list = new WP_Query( $default );
	do_action( 'before' ); 
	if ( $list -> have_posts() ){
?>
	<div id="<?php echo 'sliderup_' . $widget_id; ?>" class="sw-woo-container-slider upsells-products responsive-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
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
			<?php  endwhile; wp_reset_postdata();?>
			</div>
		</div>					
	</div>
<?php
} 
?>