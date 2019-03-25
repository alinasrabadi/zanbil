<?php 
/**
	* Layout Countdown Default
	* @version     1.0.0
**/
$term_name = esc_html__( 'All Categories', 'sw_woocommerce' );
$default = array(
	'post_type' => 'product',	
	'meta_query' => array(
		array(
			'key' => '_sale_price',
			'value' => 0,
			'compare' => '>',
			'type' => 'NUMERIC'
		),
		array(
			'key' => '_sale_price_dates_to',
			'value' => 0,
			'compare' => '>',
			'type' => 'NUMERIC'
		)
	),
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts	
);
if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );	
	$term_name = $term->name;
	$default['tax_query'] = array(
		array(
			'taxonomy'  => 'product_cat',
			'field'     => 'slug',
			'terms'     => $category ));
}
$id = 'sw_countdown_'.rand().time();
$list = new WP_Query( $default );

if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $category.'_'.$id; ?>" class="sw-woo-container-slider responsive-slider countdown-slider-2 loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false">       
		<div class="resp-slider-container">
			<div class="order-title">
				<?php
					$titles = strpos($title1, ' ');
					$title = ($titles !== false) ? '<span>' . substr($title1, 0, $titles) . '</span>' .' '. substr($title1, $titles + 1): $title1 ;
					echo '<h2><strong>'. $title .'</strong></h2>';
				?>
			</div>
			<div class="slider responsive">	
			<?php 
				$count_items = 0;
				$count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts;
				$i = 0;
				while($list->have_posts()): $list->the_post();					
				global $product, $post;
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
				$start_time = get_post_meta( $post->ID, '_sale_price_dates_from', true );
				$countdown_time = get_post_meta( $post->ID, '_sale_price_dates_to', true );	
				$orginal_price = get_post_meta( $post->ID, '_regular_price', true );	
				$sale_price = get_post_meta( $post->ID, '_sale_price', true );	
				$symboy = get_woocommerce_currency_symbol( get_woocommerce_currency() );
				$rating_count = $product->get_rating_count();
				$review_count = $product->get_review_count();
				$average      = $product->get_average_rating();
				if( $i % $item_row == 0 ){
			?>

				<div class="item item-countdown <?php echo esc_attr( $class )?>" id="<?php echo 'product_'.$id.$post->ID; ?>">
				<?php } ?>
					<div class="item-wrap">
					<?php if ($p_countdown == 'above') { ?>
						<div class="item-countdown product-countdown custom-font clearfix"  data-price="<?php echo esc_attr( $symboy.$orginal_price ); ?>" data-starttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" data-id="<?php echo 'product_'.$id.$post->ID; ?>"></div>				<?php } ?>					
						<div class="item-detail">
							<div class="item-image-countdown products-thumb">									
								<?php echo zanbil_product_thumbnail('shop_single'); ?>
								
							</div>
								<?php if ($p_countdown == 'into') { ?>
						           <div class="item-countdown product-countdown custom-font clearfix"  data-price="<?php echo esc_attr( $symboy.$orginal_price ); ?>" data-starttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" data-id="<?php echo 'product_'.$id.$post->ID; ?>"></div>	<?php } ?>	
							<div class="item-content">
							    <div class="reviews-content">
									<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : ''; ?></div>
								</div>			
								<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>
								 				
								<!-- Price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>
								<!-- add to cart, wishlist, compare -->
								<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</div>															
						</div>
					</div>
				<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
			<?php $i ++; endwhile; wp_reset_postdata();?>
			</div>
		</div>            
	</div>
<?php
	} 
?>