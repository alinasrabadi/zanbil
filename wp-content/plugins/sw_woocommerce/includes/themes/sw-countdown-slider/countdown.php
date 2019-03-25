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
	<div id="<?php echo $category.'_'.$id; ?>" class="sw-woo-container-slider responsive-slider countdown-slider loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false">       
		<div class="resp-slider-container">
			<div class="order-title">
				<?php
					echo '<h2><strong>'. $title1 .'</strong></h2>';
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
				if( $i % $item_row == 0 ){
			?>

				<div class="item item-countdown <?php echo esc_attr( $class )?>" id="<?php echo 'product_'.$id.$post->ID; ?>">
				<?php } ?>
					<div class="item-wrap">					
						<div class="item-detail">
							<div class="item-image-countdown products-thumb">
								<a href="<?php the_permalink(); ?>">
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
								<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h3>							
								<!-- Price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>
						        <div class="countdown-left"><div class="product-countdown"  data-price="<?php echo esc_attr( $symboy.$orginal_price ); ?>" data-starttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" data-id="<?php echo 'product_'.$id.$post->ID; ?>"></div></div>
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