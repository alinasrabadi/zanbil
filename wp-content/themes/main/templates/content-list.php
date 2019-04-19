<?php if (!have_posts()) : ?>
<?php get_template_part('templates/no-results'); ?>
<?php endif; ?>
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
						<?php if( !get_the_title() ) { ?><a href="<?php the_permalink(); ?>"><?php } ?>
						
						<?php if( !get_the_title() ) { ?></a><?php } ?>
					</div>
					<div class="entry-title">
						<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
					</div>
					
					<div class="entry-meta-content">
						<span class="category-blog"><i class="fa fa-folder-open" aria-hidden="true"></i><?php the_category(', '); ?></span>
						<?php if( has_tag() ){ ?><span class="tag-blog"><?php the_tags( '<span class="entry-meta-link entry-meta-tag"><span class="fa fa-tags"></span>', ', ', '</span>' ); } ?></span>
						<span class="latest_post_date">
							<span class="post_day"><?php the_time('d'); ?></span>
							<span class="post_my"><?php the_time('/ F, Y'); ?></span>
						</span>
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
						
						<div class="entry-comment">
							<a href="<?php echo get_comments_link( $post->ID ); ?>"><?php echo $post->comment_count .' <i class="fa fa-comments"></i>' ?> </a>
						</div>
						
						<div class="readmore"><a href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'zanbil'); ?><i class="fa fa-arrow-circle-right"></i></a></div>
				</div>
			</div>

		</div>
	</div>
<?php endwhile; ?>
</div>
<div class="clearfix"></div>