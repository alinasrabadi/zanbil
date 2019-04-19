<?php get_header(); ?>

<?php zanbil_breadcrumb_title(); ?>

<div class="container">
	<?php
		$zanbil_post_type = isset( $_GET['search_posttype'] ) ? $_GET['search_posttype'] : '';
		if( ( $zanbil_post_type != '' ) &&  locate_template( 'templates/search-' . $zanbil_post_type . '.php' ) ){
			get_template_part( 'templates/search', $zanbil_post_type );
		}else{ 
			if( have_posts() ){
		?>
			<div class="blog-content blog-content-list">
<?php 
	while (have_posts()) : the_post(); 
	$post_format = get_post_format();
?>
	<div id="post-<?php the_ID();?>" <?php post_class( 'theme-clearfix' ); ?>>
		<div class="entry clearfix">
			<?php if ( get_the_post_thumbnail() ){ ?>
				<div class="entry-thumb">	
					<a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail('zanbil_grid_blog');?>				
					</a>
				</div>			
			<?php } ?>			
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
					
					<div class="entry-summary">
						<?php 												
							if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
								$content = explode($matches[0], $post->post_content, 2);
								$content = $content[0];
								$content = wp_trim_words($post->post_content, 30, '...');
								echo $content;	
							} else {
								the_content('...');
							}		
						?>	
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
			</div>

		</div>
	</div>
<?php endwhile; ?>
	<?php get_template_part('templates/pagination'); ?>
		</div>
	
	<?php
		}else{
				get_template_part('templates/no-results');
			}
		}
	?>
</div>
<?php get_footer(); ?>