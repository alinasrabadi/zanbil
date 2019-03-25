<?php
/**
 * SW WooCommerce Shortcodes
 * @author 		flytheme
 * @version     1.0.0
 */

if( is_plugin_active( 'js_composer/js_composer.php' ) ){
	require_once ( WCPATH . '/includes/visual-map.php' );
}
/*
** Accordion Product
*/
function ya_accordion_popular_product_shortcode( $atts ) {
			extract( shortcode_atts( array(
			'title'         =>'',
			'image_icon' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'numberposts'	=> 5,
			'el_class' =>'',
			), $atts )
		);
	ob_start();
	$default = array(
		'post_type' 			=> 'product',		
		'post_status' 			=> 'publish',
		'ignore_sticky_posts'   => 1,
		'showposts'				=> $numberposts,
		'meta_key' 		 		=> 'recommend_product',
		'orderby' 		 		=> 'meta_value_num',
	);
$i=0;
$list = new WP_Query( $default );
ob_start();		
		if ( count($list) > 0 ){ ?>
		<div class="panel-group accordion_popular_product" id="accordion">

			<?php if( $title != ''){ ?>
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
					$titles = strpos($title, ' ');
					$title1 = ($titles !== false) ? '<span>' . substr($title, 0, $titles) . '</span>' .' '. substr($title, $titles + 1): $title ;
					echo '<h2><strong>'. $title1 .'</strong></h2>';
				?>

            </div>
			<?php } ?>
			<?php while($list->have_posts()): $list->the_post();global $product, $post, $wpdb, $average;
			  $i++
			?>
		  <div class="panel panel-default">
		  
			<?php $att = ($i != '1') ? 'class="collapsed"': 'class=""'?>
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?> " <?php echo $att?>>
					<span class="item-count">
						 <?php echo ''.$i.'.'; ?>
					</span>
					 <h4 class="panel-title">
					 <?php the_title(); ?>
					 </h4>
				</a>
			<?php $attributes = ($i == '1') ? 'class="panel-collapse collapse in"': 'class="panel-collapse collapse"'?>
			<div id="collapse<?php echo $i ?>" <?php echo $attributes ?>>
				<div class="panel-body">
					<div class="item-content">
					<?php if(has_post_thumbnail()){ ?>
						<div class="item-img item-height">
							<div class="item-img-info products-thumb">
								<a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>">
									<?php echo woocommerce_get_product_thumbnail('medium') ?>
								</a>
							</div>
						</div>
					<?php } ?>							
						<?php if ( $price_html = $product->get_price_html() ){?>
						<div class="item-price">
							<span>
								<?php echo $price_html; ?>
							</span>
						</div>
						<?php } ?>
					</div>	
				</div>
			</div>
		  </div>
		<?php endwhile; wp_reset_postdata();?>
	</div>
	<?php } 
	$content = ob_get_clean();
		
		return $content;
}
add_shortcode('accordion_popular_product','ya_accordion_popular_product_shortcode');
/*
** Listing tab product
*/
function zanbil_listing_product_tab( $atts ){
	extract( shortcode_atts( array(
		'title'		=> 'Categories',
		'category'	=> '',
		'orderby' => 'date',
		'order' => 'DESC',
		'number'	=> 3,
		), $atts )
	);
	ob_start();		
	global $product;
	$rand_time = rand().time();
	$lf_id = 'listing_tab_'.rand().time();
	$categories_id = array();
	if( $categories_id  == '' ){
		return ;
	}
	if( $categories_id  != '' ){
		$categories_id = explode( ',', $category );
	}
	$attributes = '';
	$attributes .= 'tab-col-'.$number;
	?>
	<div id="<?php echo esc_attr( $lf_id ) ?>" class="listing-tab-shortcode">
	<div class="tabbable tabs"><ul id="myTabs" class="nav nav-tabs">
		<li class="title-cat custom-font"><span><?php echo $title ?></span></li>
	<?php 
		foreach( $categories_id as $key => $category_id ){
			$cat = get_term_by('slug', $category_id, 'product_cat');
			$active = ( $key == 0 ) ? 'active' : '';
			if( $cat != NULL ){
			
	?>
		<li class="<?php echo esc_attr( $active ) ?>" onclick="window.location='<?php echo get_term_link( $cat->term_id, 'product_cat' ) ?>'"><a href="#listing_category_<?php echo $category_id.'_'.$rand_time ?>" data-toggle="tab"><?php echo esc_html( $cat -> name ) ?></a></li>
	<?php } } ?>
	</ul>
	<div class="tab-content">
	<?php 
	foreach( $categories_id as $key => $category_id ){
		$active = ( $key == 0 ) ? 'active' : '';
	?>
		<div id="listing_category_<?php echo esc_attr( $category_id ).'_'.$rand_time ?>" class="tab-pane clearfix <?php echo esc_attr( $active ); ?>">
	<?php 
		if( $category_id != '' ){
		$args = array(
			'post_type' => 'product',
			'tax_query'	=> array(
			array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'slug',
				'terms'		=> $category_id)),
			
			'orderby'		=> $orderby,
			'order'			=> $order,
			'post_status' 	=> 'publish',
			'showposts' 	=> $number
		);
		}else{
			$args = array(
				'post_type' => 'product',
				'orderby' => $orderby,
				'order' => $order,
				'post_status' => 'publish',
				'showposts' => $number
			);
		}
		$list = new WP_Query( $args );
		while($list->have_posts()): $list->the_post();
		global $product, $post;
	?>
			<div class="item">
				<div class="item-wrap">
					<div class="item-detail">										
						<div class="item-img products-thumb">			
							<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
						</div>										
						<div class="item-content">
							<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>				
								
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
		<?php 
			endwhile; wp_reset_postdata();
			wp_reset_postdata();
		?>
			</div>
	<?php } ?>
		</div></div></div>
<?php 
	$output = ob_get_clean();
	return $output;
}
add_shortcode('product_tab','zanbil_listing_product_tab');

 /*
 * Recommend product
 *
 */
 function zanbil_recommend_shortcode($atts){
	 extract(shortcode_atts(array(
	 'number' => '',
	 'title'=>'',
	 'el_class'=>'',
	 'item_slide'=>'',
    		'post_status' 	 => 'publish',
    		'post_type' 	 => 'product',
    		'meta_key' 		 => 'recommend_product',
			'meta_value'     => 'yes',
    		'orderby' 		 => 'ID',
    		'no_found_rows'  => 1
	 ),$atts));
	
		ob_start();
		global $woocommerce;
		$pf_id = 'sw_recommend_product-'.rand().time();
		$query_args =array( 'posts_per_page'=> $number,'post_type' => 'product','meta_key' => 'recommend_product','meta_value' => 'yes','orderby' => $orderby,'no_found_rows' => 1); 
		$query_args['meta_query'] = $woocommerce->query->get_meta_query();
		$r = new WP_Query($query_args);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
?>
	<div id="<?php echo $pf_id ?>" class="sw-recommend-product-slider carousel slide <?php echo $el_class ?>" data-interval="0">
	<?php if( $title != '' ) { ?>
		<div class="top-tab-slider">
			<div class="order-title">
				<?php
					$titles = strpos($title, ' ');
					$title1 = ($titles !== false) ? '<span>' . substr($title, 0, $titles) . '</span>' .' '. substr($title, $titles + 1): $title1 ;
					echo '<h2><strong>'. $title1.'</strong></h2>';
				?>
				<div class="carousel-cl nav-custom">
					<a class="prev-test fa fa-arrow-left" href="#<?php echo esc_attr( $pf_id ) ?>" role="button" data-slide="prev"></a>
					<a class="next-test fa fa-arrow-right" href="#<?php echo esc_attr( $pf_id ) ?>" role="button" data-slide="next"></a>
				</div>	
			</div>
		</div>
	<?php } ?>
    <div class="carousel-inner">
		<?php 
			$i = 0;
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( ( $i % $item_slide ) == 0 && ( $item_slide != 0 ) ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item  <?php echo $active ?> " >
	<?php } ?>
				<div class="item-detail">
				
						<div class="item-img products-thumb">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php 
								if( has_post_thumbnail() ){  
									echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
								}
							?>
							</a>
						</div>
						<div class="item-content">
						    <h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							
							<div class="item-price"><?php echo $product->get_price_html() ?></div>
							
						</div>
				
				</div>
			<?php if( ( $i+1 ) % ($item_slide) == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
			<?php }
				$i++;endwhile;
				wp_reset_postdata();
			?>
		</div>
	</div>
	<?php 
		}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('Recommend','zanbil_recommend_shortcode');
 function zanbil_latest_products_shortcode($atts){
	 extract(shortcode_atts(array(
			'number' => 5,
			'title'=>'',
			'el_class'=>'',
			'post_status' 	 => 'publish',
			'post_type' 	 => 'product',
			'orderby' 		 => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => 1
		),$atts));
		ob_start();
		global $woocommerce;
		$pf_id = 'SW_latest_product-'.rand().time();
		$query_args =array( 'posts_per_page'=> $number, 'post_type' => 'product', 'orderby' => $orderby, 'order' => $order, 'no_found_rows' => 1); 
		$query_args['meta_query'] = $woocommerce->query->get_meta_query();
		$r = new WP_Query($query_args);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) { ?>
	<div id="<?php echo $pf_id ?>" class="sw-latest-product vc_element carousel slide <?php echo $el_class ?>" data-interval="0">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span><?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php } ?>
		<div class="customNavigation nav-left-product">
			<a title="<?php echo esc_attr__( 'Previous', 'sw_woocommerce' ) ?>" class="btn-bs prev-bs fa  fa-arrow-left"  href="#<?php echo $pf_id ?>" role="button" data-slide="prev"></a>
			<a title="<?php echo esc_attr__( 'Next', 'sw_woocommerce' ) ?>" class="btn-bs next-bs fa  fa-arrow-right" href="#<?php echo $pf_id ?>" role="button" data-slide="next"></a>
		</div>
    <div class="carousel-inner">
		<?php 
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( $i % 4 == 0 ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item <?php echo $active ?>" >
	<?php } ?>
				<div class="item cf">
					<div class="item-inner">
						<div class="item-img">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php
								if( has_post_thumbnail() ){  
									echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
						</div>
					</div>
				</div>
			<?php if( ( $i+1 ) % 4 == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
		<?php }
			$i++;endwhile;
			wp_reset_postdata();
		?>
		</div>
	</div>
<?php
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('Latest','zanbil_latest_products_shortcode');


/*
**	Countdown Banner
*/
function zanbil_banner_countdown_shortcode($atts){
	extract(shortcode_atts(array(		
		'title'			=> '',
		'image_icon'	=> '',
		'description'	=> '',
		'el_class'	=> '',
		'image'		=> '',
		'date'			=> '',
		'url'			=> '',
	),$atts));
	ob_start();
$bcd_id = 'banner_countdown_'.rand().time();
?>
	<div id="<?php echo esc_attr( $bcd_id ); ?>" class="banner-count-shortcode">
		<?php if( $title != '') { ?>
			<div class="wp-order-title">
				<div class="order-title">
					<?php
						$titles = strpos($title, ' ');
						$title1 = ($titles !== false) ? '<span>' . substr($title, 0, $titles) . '</span>' .' '. substr($title, $titles + 1): $title ;
						echo '<h2><strong>'. $title1 .'</strong></h2>';
					?>
					<?php if( $image_icon != '' ) { ?>
						<div class="order-icon">
							<?php 
								$image_thumb = wp_get_attachment_image( $image_icon, 'thumbnail' );
								echo $image_thumb; 
							 ?>
						</div>
					<?php } ?>
					<?php echo ( $description != '') ? '<div class="order-desc">'. $description .'</div>' : ''; ?>
				</div>
			</div>
		<?php } ?>
		<?php 
			if( $image != '' && $url != '' ) {
			    $countdown_time = strtotime( $date );
		?>
		<div class="banner-inner clearfix">
			<div class="item-banner" style="width:100%"><a href="<?php echo esc_url( $url ); ?>"><?php echo wp_get_attachment_image( $image, 'full' ); ?></a></div>
			<div class="countdown-left"><div class="product-countdown" id="img-banner" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>"></div></div>
		</div>
		<?php
		    }else {
		    $countdown_time = strtotime( $date );
		?>
		    <div class="countdown-left"><div class="product-countdown" id="img-banner" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>"></div></div>
		    
		<?php } ?>
	</div>
<?php 
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'banner_countdown', 'zanbil_banner_countdown_shortcode' );
