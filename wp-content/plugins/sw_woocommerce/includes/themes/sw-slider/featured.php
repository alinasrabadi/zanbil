<?php 
$term_name = esc_html__( 'Featured Products', 'sw_woocommerce' );
$default = array(
	'post_type'				=> 'product',
	'post_status' 			=> 'publish',
	'ignore_sticky_posts'	=> 1,
	'posts_per_page' 		=> $numberposts,
	'orderby' 				=> $orderby,
	'order' 				=> $order,
);

if( sw_woocommerce_version_check( '3.0' ) ){	
	$default['tax_query'][] = array(						
		'taxonomy' => 'product_visibility',
		'field'    => 'name',
		'terms'    => 'featured',
		'operator' => 'IN',	
	);
}else{
	$default['meta_query'] = array(
		array(
			'key' 		=> '_featured',
			'value' 	=> 'yes'
		)					
	);				
}

if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );	
	if( $term ) :
		$term_name = $term->name;
	endif;
	
	$default['tax_query'][] = array(
		'taxonomy'	=> 'product_cat',
		'field'		=> 'slug',
		'terms'		=> $category
	);
}
$id = 'sw_featured_'.rand().time();
$list = new WP_Query( $default );
if ( $list -> have_posts() ){
?>
	<div id="<?php echo $id; ?>" class="sw-woo-container-slider  responsive-slider featured-product clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>"  data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="top-tab-slider">
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
		</div>  
		<div class="resp-slider-container">
			<div class="slider responsive">			
			<?php
				$i = 1;
				$count_items 	= 0;
				$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
				$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
				$i 				= 0;
				while($list->have_posts()): $list->the_post();global $product, $post;
				if( $i % $item_row == 0 ){
			?>
				<div class="item">
			<?php } ?>
		          <?php include( WCTHEME . '/default-item.php' ); ?>
				<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
			<?php $i++; endwhile; wp_reset_postdata();?>
			</div>
		</div>					
	</div>
<?php
}	
?>