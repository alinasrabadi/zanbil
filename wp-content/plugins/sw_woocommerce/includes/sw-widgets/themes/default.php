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
	<div id="<?php echo esc_attr( $widget_id ) ?>" class="client-wrapper-b carousel slide <?php echo esc_attr( $el_class ) ?>" data-interval="0">
		<div class="block-title-bottom">
			<h2><?php echo $title ?></h2>
			<div class="carousel-cl nav-custom">
				<a class="prev-test fa fa-chevron-left" href="#<?php echo esc_attr( $widget_id ) ?>" role="button" data-slide="prev"><span></span></a>
				<a class="next-test fa fa-chevron-right" href="#<?php echo esc_attr( $widget_id ) ?>" role="button" data-slide="next"><span></span></a>
			</div>					
		</div>
		<div class="carousel-inner">
		<?php 
			while($list->have_posts()): $list->the_post();				
			global $post;
			$au_name = get_post_meta( $post->ID, 'au_name', true );
			$au_url  = get_post_meta( $post->ID, 'au_url', true );
			$active = ($i== 0)? 'active' :'';
		?>
			<div class="item <?php echo esc_attr( $active ) ?>">
				<div class="item-inner">
				<?php if( has_post_thumbnail() ){ ?>
					<div class="image-client">
						<a href="#" title="<?php echo esc_attr( $post->post_title ) ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
					</div>		
					<div class="client-say-info">
						<div class="client-comment">
						<?php 
							$text = get_the_content($post->ID);
							$content = wp_trim_words($text, $length);
							echo esc_html($content);
						?>
						</div>
						<div class="name-client">
							<h2><a href="<?php echo esc_url( $au_url ) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html($au_name) ?></a></h2>
						</div>
					</div>
				<?php } ?>
				</div>
			</div> 
			<?php $i++; endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
<?php	
}
?>