<?php if (!have_posts()) : ?>
<?php get_template_part('templates/no-results'); ?>
<?php endif; ?>
<?php
	$blog_columns = zanbil_options()->getCpanelValue('blog_column');	
	$col = 'col-md-'.(12/$blog_columns).' col-sm-6 col-xs-12 theme-clearfix';
	global $instance;
?>
<div class="row blog-content blog-content-grid">
<?php 
	while (have_posts()) : the_post(); 
	$format = get_post_format();
	global $post;
?>
	<div id="post-<?php the_ID();?>" <?php post_class($col); ?>>
		<div class="entry clearfix">
		    <a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php if( $format == '' || $format == 'image' ){ ?>
					<?php if ( get_the_post_thumbnail() ){ ?>
						<div class="entry-thumb">	
							
								<?php the_post_thumbnail('large');?>	
								
							<div class="entry-meta-content">
						<span class="category-blog"><?php the_category(', '); ?></span>
						</div>
						</div>			
				<?php } ?>
			</a>
				<div class="entry-content">				
					<div class="content-top">
					    
						<div class="entry-title">
							<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
						</div>
						<div class="entry-summary">
							<?php
							$my_excerpt = get_the_excerpt();
							if ( '' != $my_excerpt ) {
								// Some string manipulation performed
							}
							echo $my_excerpt; // Outputs the processed value to the page
							?>
						</div>
					</div>					
					
					<div class="readmore"><a href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'zanbil'); ?><i class="fa fa-arrow-circle-right"></i></a></div>
				</div>
			<?php } elseif( !$format == ''){?>
			<div class="wp-entry-thumb">	
				<?php if( $format == 'video' || $format == 'audio' ){ ?>	
					<?php echo ( $format == 'video' ) ? '<div class="video-wrapper">'. get_entry_content_asset($post->ID) . '</div>' : get_entry_content_asset($post->ID); ?>										
				<?php } ?>
				
				<?php if( $format == 'gallery' ) { 
					if(preg_match_all('/\[gallery(.*?)?\]/', get_post($instance['post_id'])->post_content, $matches)){
						$attrs = array();
						if (count($matches[1])>0){
							foreach ($matches[1] as $m){
								$attrs[] = shortcode_parse_atts($m);
							}
						}
						if (count($attrs)> 0){
							foreach ($attrs as $attr){
								if (is_array($attr) && array_key_exists('ids', $attr)){
									$ids = $attr['ids'];
									break;
								}
							}
						}
					?>
						<div id="gallery_slider_<?php echo $post->ID; ?>" class="carousel slide gallery-slider" data-interval="0">	
							<div class="carousel-inner">
								<?php
									$ids = explode(',', $ids);						
									foreach ( $ids as $i => $id ){ ?>
										<div class="item<?php echo ( $i== 0 ) ? ' active' : '';  ?>">			
												<?php echo wp_get_attachment_image($id, 'full'); ?>
										</div>
									<?php }	?>
							</div>
							<a href="#gallery_slider_<?php echo $post->ID; ?>" class="left carousel-control" data-slide="prev"><?php esc_html_e( 'Prev', 'zanbil' ) ?></a>
							<a href="#gallery_slider_<?php echo $post->ID; ?>" class="right carousel-control" data-slide="next"><?php esc_html_e( 'Next', 'zanbil' ) ?></a>
						</div>
					<?php }	?>							
				<?php } ?>

				<?php if( $format == 'quote' ) { ?>
					
				<?php } ?>
			</div>
			<div class="entry-content">				
					<div class="content-top">
					    <div class="entry-meta">
							<span class="latest_post_date">
								<span class="post_day"><?php the_time('d'); ?></span>
								<span class="post_my"><?php the_time('/ F, Y'); ?></span>
							</span>
						</div>
						<div class="entry-title">
							<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
						</div>
						<div class="entry-meta-content">
							<span class="entry-meta-link category-blog"><i class="fa fa-folder-open"></i><?php the_category(', '); ?></span>
							<?php the_tags( '<span class="entry-meta-link entry-meta-tag"><span class="fa fa-tag"></span>', ', ', '</span>' ); ?>
						</div>
						<div class="entry-summary">
							<?php 												
								if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
									$content = explode($matches[0], $post->post_content, 2);
									$content = $content[0];
									$content = wp_trim_words($post->post_content, 22, '...');
									echo $content;	
								} else {
									the_content('...');
								}		
							?>	
						</div>
					</div>
					<?php 
		                    $comment_count = $post->comment_count;
		                    if($comment_count > 1) {
							?>
							<div class="entry-comment">
							    <?php echo $post->comment_count .'<span>'. esc_html__(' comments', 'zanbil').'</span>'; ?> <i class="fa fa-comments"></i>
							</div>
							<?php }else { ?>
							<div class="entry-comment">
							    <?php echo $post->comment_count .'<span>'. esc_html__(' comment', 'zanbil').'</span>'; ?> <i class="fa fa-comments"></i>
							</div>
							<?php } ?>
							<div class="readmore"><a href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'zanbil'); ?><i class="fa fa-arrow-circle-right"></i></a></div>
				</div>
			<?php } ?>
		</div>
	</div>
<?php endwhile; ?>
</div>
<div class="clearfix"></div>


