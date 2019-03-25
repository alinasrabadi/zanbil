<?php 

/**
	* Layout Default
	* @version     1.0.0
**/

$widget_id = isset( $widget_id ) ? $widget_id : 'sw_woo_slider_'.rand().time();
$term_name = esc_html__( 'All Categories', 'sw_woocommerce' );
$default = array(
	'post_type' => 'product',		
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
			'terms'     => $category )
	);	
}
$list = new WP_Query( $default );
do_action( 'before' ); 
if ( $list -> have_posts() ){ ?>
<div id="<?php echo 'slider_' . $widget_id; ?>" class="sw-woo-container-slider responsive-slider <?php echo $style ?> woo-slider-lastest4 loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="order-title">
			<?php
				$titles = strpos($title1, ' ');
				$title = ($titles !== false) ? '<span>' . substr($title1, 0, $titles) . '</span>' .' '. substr($title1, $titles + 1): $title1 ;
				echo '<h2><strong>'. $title .'</strong></h2>';
			?>
				
			<?php if( $image_icon != '' ) { ?>
				<div class="order-icon">
					<?php 
						$image_thumb = wp_get_attachment_image( $image_icon, 'thumbnail' );
						echo $image_thumb; 
					 ?>
				</div>
			<?php } ?>

			<?php echo ( $description != '' ) ? '<div class="order-desc">'. $description .'</div>' : ''; ?>
		</div>
		<div class="resp-slider-container">
			<div class="slider responsive">	
				<?php
				$content_array = $list->posts;
				self::addOtherItem( $content_array, array( 'empty' ), 2, $items );
				$count_items 	= 0;
				$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
				$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
				$_nb = 5;
				$j = 0;
				$k = -1;
				while ($list->have_posts()) : $list->the_post();
					global $product; global $post; $j++; $k++;
						?>
					<?php if($j%$_nb == 1){ ?>
						<div class="item">
					<?php } ?>
					<?php if($k%$_nb == 4){ ?>
					
						<div class="item-larger" style="color:red">
							<div class="item-wrap">
								<div class="item-detail">
									<div class="item-img products-thumb">
										<?php echo zanbil_product_thumbnail('shop_single'); ?>
									</div>
									<div class="item-content">
									<?php
										$rating_count = $product->get_rating_count();
										$review_count = $product->get_review_count();
										$average      = $product->get_average_rating();
										?>
										<div class="reviews-content">
											<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : ''; ?></div>
										</div>
										<h4>
											<a href="<?php echo get_the_permalink( $post->ID ); ?>" title="<?php echo get_the_title( $post->ID );?>">
												<?php echo get_the_title( $post->ID ); ?>
											</a>
										</h4>
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
						</div>
					<?php } else {?>
						<?php if($k%$_nb == 0 || $k%$_nb == 1){ ?>
							<?php if($k%$_nb == 0){ ?>
							
								<div class="item-small" style="color:blue">
							<?php } ?>
							<div class="item-wrap">
								<div class="item-detail">
									<div class="item-img products-thumb">
										<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
									</div>
									<div class="item-content">

										<?php
										$rating_count = $product->get_rating_count();
										$review_count = $product->get_review_count();
										$average      = $product->get_average_rating();
										?>
										<div class="reviews-content">
											<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : ''; ?></div>
										</div>
										<h4>
											<a href="<?php echo get_the_permalink( $post->ID ); ?>" title="<?php echo get_the_title( $post->ID );?>">
												<?php echo get_the_title( $post->ID ); ?>
											</a>
										</h4>
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
							<?php if(($k+1)%$_nb==2) { ?>
							
								</div>
							<?php } ?>
						<?php } else { ?>
							<?php if($k%$_nb == 2){ ?>
						
								<div class="item-small" style="color:blue">
							<?php } ?>
							<div class="item-wrap">
								<div class="item-detail">
									<div class="item-img products-thumb">
										<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
									</div>
									<div class="item-content">

										<?php
										$rating_count = $product->get_rating_count();
										$review_count = $product->get_review_count();
										$average      = $product->get_average_rating();
										?>
										<div class="reviews-content">
											<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : ''; ?></div>
										</div>
										<h4>
											<a href="<?php echo get_the_permalink( $post->ID ); ?>" title="<?php echo get_the_title( $post->ID );?>">
												<?php echo get_the_title( $post->ID ); ?>
											</a>
										</h4>
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
							<?php if(($k+1)%$_nb==4) { ?>
								</div>
							
							<?php } ?>
						<?php }} ?>
					<?php if($j%$_nb == 0  || $j == $count_items){ ?>
						</div>
					<?php } ?>
					<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>					
	</div>
	<?php
	}
?>
