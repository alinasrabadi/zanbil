<?php 
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_testimonial'.rand().time();
	$default = array(
		'post_type' => 'testimonial',
		'orderby' => $orderby,
		'order' => $order,
		'post_status' => 'publish',
		'showposts' => $numberposts
	);
	$list = new WP_Query( $default );
	if ( count($list) > 0 ){
	$i = 0;
	$j = 0;
	$k = 0;
?>
	<div id="<?php echo esc_attr( $widget_id ) ?>" class="testimonial-slider carousel slide <?php echo esc_attr( $layout ) . ' ' . esc_attr( $el_class ) ?>" data-interval="0">
	<?php if($title !=''){ ?>
		<div class="top-tab-slider">
				<div class="order-title">
					<?php
						$titles = strpos($title, ' ');
						$title1 = ($titles !== false) ? '<span>' . substr($title, 0, $titles) . '</span>' .' '. substr($title, $titles + 1): $title ;
						echo '<h2><strong>'. $title1.'</strong></h2>';
					?>
					<?php if( $image_icon != '' ) { ?>
						<div class="order-icon">
							<?php 
								$image_thumb = wp_get_attachment_image( $image_icon, 'thumbnail' );
								echo $image_thumb; 
							 ?>
						</div>
				    <?php } ?>
					<div class="carousel-cl nav-custom">
						<a class="prev-test fa fa-arrow-left" href="#<?php echo esc_attr( $widget_id ) ?>" role="button" data-slide="prev"></a>
						<a class="next-test fa fa-arrow-right" href="#<?php echo esc_attr( $widget_id ) ?>" role="button" data-slide="next"></a>
					</div>	
				</div>
		</div>
	<?php } ?>
		<div class="carousel-inner">
		<?php 
			while($list->have_posts()): $list->the_post();				
			global $post;
			$au_name = get_post_meta( $post->ID, 'au_name', true );
			$au_url  = get_post_meta( $post->ID, 'au_url', true );
			$au_info = get_post_meta( $post->ID, 'au_info', true );
			$active = ( $i== 0 ) ? 'active' :'';
		?>
			<div class="item <?php echo esc_attr( $active ) ?>">
				<div class="item-inner">		
				<?php if( has_post_thumbnail() ){ ?>
					<div class="client-say-info">
						<div class="image-client">
							<a href="#" title="<?php echo esc_attr( $post->post_title ) ?> "><?php the_post_thumbnail( 'thumbnail' ) ?></a>
						</div>

					</div>
					<div class="client-comment">
					<?php 
						$text = get_the_content($post->ID);
						$content = wp_trim_words($text, $length);
						echo esc_html($content);
					?>
					</div>
							<div class="name-client">
							<h2><a href="#" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html($au_name) ?></a></h2>
								<p><?php echo esc_html($au_info) ?></p>
							</div>
				    
				<?php } ?>
				</div>
			</div>
			<?php $i++; endwhile; wp_reset_postdata();  ?>
		</div>
		<ul class="carousel-indicators">
		<?php 
			while ( $list->have_posts() ) : $list->the_post();
			if( $j % 1 == 0 ) {  $k++;
			$active = ($j== 0)? 'active' :'';
		?>
			<li class="<?php echo $active ?>" data-slide-to="<?php echo ($k-1) ?>" data-target="#<?php echo esc_attr( $widget_id ) ?>">
		<?php } if( ( $j+1 ) % 1 == 0 || ( $j+1 ) == $numberposts ){ ?>
			</li>
		<?php 
				}					
			$j++; 
			endwhile; 
			wp_reset_postdata(); 
		?>
		</ul>
	</div>
<?php	
}
?>