<?php 
	/**
		** Theme: Responsive Slider
		** Author: flytheme
		** Version: 1.0
	**/
	$default = array(
			'category' => $category, 
			'orderby' => $orderby,
			'order' => $order, 
			'numberposts' => $numberposts,
	);
	$list = get_posts($default);
	do_action( 'before' ); 
	$id = 'sw_reponsive_post_slider_'.rand().time();
	
	if ( count($list) > 0 ){
?>
<div class="clear"></div>
<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider responsive-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="top-tab-slider">
		<div class="order-title">
			<?php
				$titles = strpos($title2, ' ');
				$title = ($titles !== false) ? '<span>' . substr($title2, 0, $titles) . '</span>' .' '. substr($title2, $titles + 1): $title2 ;
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
		</div> 
	</div> 
	<div class="resp-slider-container">
		<div class="slider responsive">
			<?php foreach ($list as $key => $post){ 
				
			?>
				<div class="item widget-pformat-detail">
					<div class="item-inner">
														
							<div class="img_over">
								<a href="<?php echo get_permalink($post->ID)?>">
									<?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
								</a>
							</div>
						
						<div class="entry-content">
							<div class="entry-meta">
								<span class="latest_post_date">							
									<span class="post_day"><?php echo get_the_time( 'd', $post->ID ); ?></span>
									<span class="post_my"><?php echo get_the_time('/ F, Y', $post->ID ); ?></span>
								</span>
							</div>
							<div class="widget-title">
								<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
							</div>
							<div class="description">
								<?php 										
									$content = self::ya_trim_words($post->post_content, $length, ' ');									
									echo $content;
								?>
							</div>
							<?php $comment_count = $post->comment_count; ?>
							<div class="entry-comment">
							    <a href="<?php echo get_comments_link( $post->ID ); ?>"><?php echo $post->comment_count; ?> <i class="fa fa-comments"></i></a>
							</div>							
							<div class="readmore"><a href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'zanbil'); ?><i class="fa fa-arrow-circle-right"></i></a></div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
</div>
<?php } ?>