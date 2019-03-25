<?php if(is_single ()) : ?>
<?php 
	/**
		** Theme: Responsive Slider
		** Author: flytheme
		** Version: 1.0
	**/
	global $post;	global $image_icon;
	$categories = get_the_category($post->ID);							
	$category_ids = array();
	foreach($categories as $individual_category) {$category_ids[] = $individual_category->term_id;}
	$default = array(
		    'category__in' => $category_ids,
		    'post__not_in' => array($post->ID),
			'ignore_sticky_posts'=>1,
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
<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider responsive-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="top-tab-slider">
		<div class="order-title">
			<?php
				$titles = strpos($title2, ' ');
				$title = ($titles !== false) ? '<span>' . substr($title2, 0, $titles) . '</span>' .' '. substr($title2, $titles + 1): $title1 ;
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
			<?php                
					$i = 0;
			    foreach ($list as $key => $post){ 
						if( $i % $item_row == 0 ){
				
			?>
				<div class="item widget-pformat-detail <?php echo $class; ?>">
				<?php } ?>
					<div class="item-inner">
														
							<div class="img_over">
								<a href="<?php echo get_permalink($post->ID)?>">
									<?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
								</a>
							</div>
						
						<div class="entry-content">
							<div class="entry-meta">
								<span class="latest_post_date">
									<span class="post_day"><?php echo get_the_time('d', $post->ID); ?></span>
									<span class="post_my"><?php echo get_the_time('/ F, Y', $post->ID); ?></span>
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
							    <a href="<?php echo get_comments_link( $post->ID ); ?>"><?php echo $post->comment_count .'<span>'. ( ( $comment_count > 1 ) ? esc_html__(' comments', 'zanbil') : esc_html__(' comment', 'zanbil') .'</span>' ); ?> <i class="fa fa-comments"></i></a>
							</div>
							<div class="readmore"><a href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'zanbil'); ?><i class="fa fa-arrow-circle-right"></i></a></div>
						</div>
					</div>
				<?php if( ( $i+1 ) % $item_row == 0 ){?> </div><?php } ?>
			<?php $i++;  }; wp_reset_postdata();?>
		</div>
	</div>
</div>
<?php }?>
<?php endif ?>