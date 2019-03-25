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
<div id="<?php echo esc_attr( $id ) ?>" class="sidebar-related-post clearfix">
	<div class="sidebar-related-container">
		<div class="block-title-widget">
			<?php echo ( $title2 !='' ) ? '<h2><span>'. $title2 .'</span></h2>' : ''; ?>
		</div>
		<div class="sidebar-relate-content">
			<?php foreach ($list as $key => $post){ ?>
				<div class="item widget-pformat-detail <?php echo $class; ?>">
					<div class="item-inner">
						<div class="img_over">
							<a href="<?php echo get_permalink($post->ID)?>">
								<?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
							</a>
							<div class="entry-meta">
								<span class="latest_post_date">
									<span class="post_day"><?php echo get_the_time('d', $post->ID); ?></span>
									<span class="post_my"><?php echo get_the_time('M', $post->ID); ?></span>
								</span>
							</div>
						</div>
						<div class="entry-content">	
							<div class="entry-title">
								<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
							</div>
							<div class="entry-meta-content clearfix">
								<a href="<?php echo get_comments_link( $post->ID ); ?>">
								<span class="entry-comment pull-left">
									<i class="fa fa-comments-o"></i>
									<?php 
										$comment_count = $post->comment_count; 
										echo $post->comment_count .'<span>'. ( ( $comment_count > 1 ) ? esc_html__(' comments', 'zanbil') : esc_html__(' comment', 'zanbil') .'</span>' ); 
									?> 
								</span>
								</a>
								<span class="entry-view pull-right">
									<i class="fa fa-eye"></i>
									<?php 
										$count_view = get_post_meta( $item->ID,'count_page_hits', true );
										if( $count_view == '' ) {
											echo ' 0 ';
										} else {
											echo $count_view;  											
										}
										esc_html_e('view', 'zanbil');							
									?>
								</span>
								
							</div>				
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
</div>
<?php } ?>