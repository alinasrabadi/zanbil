<?php  ?>
<?php
if( $category == '' ){
	echo __( 'Please select a category', 'smartaddons' );
	return;
}
$widget_id = isset( $widget_id ) ? $widget_id : 'sw_woo_slider_'.rand().time();

$default = array();
$default = array(
	'post_type' => 'product',
	'tax_query' => array(
	array(
		'taxonomy'  => 'product_cat',
		'field'     => 'slug',
		'terms'     => $category ) ),
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts
);
$term = get_term_by( 'slug', $category, 'product_cat' );	
$list = new WP_Query( $default );
$nav_id = 'nav_tabs_res'.rand().time();
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $widget_id; ?>" class="sw-woo-container-slider responsive-slider woo-slider-childcat loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
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
				<?php }
				$termchild = get_term_children( $term->term_id, 'product_cat' );
				if( count( $termchild ) > 0 ){
			?>
			<div class="childcat-slider">				
				<div class="childcat-content">
				<?php 
					$termchild = get_term_children( $term->term_id, 'product_cat' );
					echo '<ul class="nav nav-tabs" id="'.esc_attr($nav_id).'">';
					foreach ( $termchild as $child ) {
						$term = get_term_by( 'id', $child, 'product_cat' );
						echo '<li><a href="' . get_term_link( $child, 'product_cat' ) . '">' . $term->name . '</a></li>';
					}
					echo '</ul>';
				?>
				</div>
			</div>
			<?php } ?>
		</div>          
		<div class="resp-slider-container img-right">
		<div class="row">
		<div class="left-child col-lg-3 col-md-3">
		<div class="child-overlay"> </div>
		<a class="img-class" href="#" title="<?php echo esc_attr( $term->name ) ?>"><?php   echo wp_get_attachment_image( $image, 'full' ) ?><span><?php echo ( $description != '' ) ? '<div class="order-desc">'. $description .'</div>' : ''; ?></span></a>
		</div>
		<div class="right-childs col-lg-9 col-md-9 col-sm-12">
		<div class="rw-margin">
			<div class="slider responsive">	
			<?php while($list->have_posts()): $list->the_post();global $product, $post, $wpdb, $average; ?>
				<div class="item">
				    <?php include( WCTHEME . '/default-item.php' ); ?>
				</div>
			<?php endwhile; wp_reset_postdata();?>
			</div>
		</div>
		</div>
		</div>    
       </div>        
	</div>
	<?php
	}
?>