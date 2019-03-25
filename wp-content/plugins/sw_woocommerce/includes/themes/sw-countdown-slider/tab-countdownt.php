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
$countdown_id = 'sw_tab_countdown_'.rand().time();
$countdown_id2 = 'sw_tab_countdown2_'.rand().time();
$zanbil_page_offers = zanbil_options()->getCpanelValue('hot_offers');
$list = new WP_Query( $default );
if ( $list -> have_posts() ){ ?>
<div id="<?php echo $countdown_id; ?>" class="sw_tab_countdown">

<div  class="tab-countdown-slide clearfix">	
<?php if ($title1 !='') { ?> 
							<div class="order-title">
										   <?php if( $image_icon != '' ) { ?>
												<span class="order-icon">
													<?php 
														$image_thumb = wp_get_attachment_image( $image_icon, 'thumbnail' );
														echo $image_thumb; 
													 ?>
												</span>
											<?php } ?>
											<?php
												echo '<h2><strong>'. $title1 .'</strong></h2>';
											?>	
							</div>
							<?php } ?>	
		<div class="top-tab-slider clearfix">	
			<div id="<?php echo 'tab_' . $countdown_id; ?>" class="sw-tab-slider responsive-slider loading hidden-sm hidden-xs" data-lg="8" data-scroll="1" data-interval="3000" data-autoplay="false" data-vertical="true">
				<ul class="nav nav-tabs slider responsive">
					<?php
						$i = 0;
						while($list->have_posts()): $list->the_post();	
						global $product, $post;
					?>
					<li <?php echo ( $i == 0 )? 'class="item active"' : 'class="item"'; ?>>
						<a href="#<?php echo 'product_tab_'.$post->ID; ?>" data-toggle="tab">
							<?php echo $product->get_name(); ?>
						</a>
					</li>
					<?php
						$i++; endwhile; wp_reset_postdata();
					?>
				</ul>
			</div>
		</div>
		<div class="tab-content clearfix">
			<?php
				$count_items 	= 0;
				$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
				$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
				$i 				= 0;
				while($list->have_posts()): $list->the_post();
				global $product, $post;
				$start_time = get_post_meta( $post->ID, '_sale_price_dates_from', true );
				$countdown_time = get_post_meta( $post->ID, '_sale_price_dates_to', true );	
				$orginal_price = get_post_meta( $post->ID, '_regular_price', true );	
				$sale_price = get_post_meta( $post->ID, '_sale_price', true );
				$offer_price = $orginal_price - $sale_price;
				$symboy = get_woocommerce_currency_symbol( get_woocommerce_currency() );
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
			?>
			<div class="tab-pane <?php echo ( $i == 0 ) ? 'active' : ''; ?>" id="<?php echo 'product_tab_'.$post->ID; ?>" >
				<div class="item-wrap">
					<div class="item-detail">										
						<div class="item-img products-thumb">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php 
									$id = get_the_ID();
									if ( has_post_thumbnail() && !$start_time=="" ){
											echo get_the_post_thumbnail( $post->ID, 'medium' ) ? get_the_post_thumbnail( $post->ID, 'medium' ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'medium'.'.png" alt="No thumb">';		
									}
									if($start_time==""){
									echo '<div class="finish-sale">';
									echo get_the_post_thumbnail( $post->ID, 'medium' ) ? get_the_post_thumbnail( $post->ID, 'medium' ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'medium'.'.png" alt="No thumb">';	
									echo '<span>تمام شد.</span></div>';
								}
								?>
							</a>
						</div>
						<div class="item-content">																
							<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>
				
							<div class="description"><?php echo wp_trim_words( $post->post_excerpt, 115 ); ?></div>
												
							<!-- price -->
							<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										قیمت برای شما : <?php echo $price_html; ?>
									</span>
									<div class="offer">
										تخفیف شما از این خرید : <?php echo $offer_price; ?> تومان
									</div>
								</div>
							<?php } ?>	
						</div>
						
					    </div>
						<div class="countdown-left"><div class="product-countdown"  data-price="<?php echo esc_attr($orginal_price ); ?>" data-starttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" data-id="<?php echo 'product_'.$id.$post->ID; ?>"></div>
						</div>
						<?php 
							if ($zanbil_page_offers != ''  && get_page( $zanbil_page_offers ) != NULL ):
								$zanbil_pageoff = get_page( $zanbil_page_offers );
								?>
								<a class="showprod" href="<?php echo get_page_link( $zanbil_page_offers ); ?>">
									همـه شگفتـــــــــ انــگیز ها
								</a>					
								<?php endif ?>
							
				</div>
			</div>
			<?php
				$i++; endwhile; wp_reset_postdata();
			?>
		</div>

</div>
</div>
<?php
	} 
?>