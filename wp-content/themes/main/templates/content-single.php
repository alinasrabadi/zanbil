<?php while (have_posts()) : the_post(); ?>
<?php $related_post_column = zanbil_options()->getCpanelValue('sidebar_blog'); ?>
<?php 
	setPostViews(get_the_ID()); 
?>
<div <?php post_class(); ?>>
	<?php $pfm = get_post_format();?>
	<div class="entry-wrap">
		<?php if( $pfm == '' || $pfm == 'image' ){?>
			<?php if( has_post_thumbnail() ){ ?>
				<div class="entry-thumb single-thumb">
					<?php the_post_thumbnail('zanbil_detail_thumb'); ?>
				</div>
		<?php } }?>
		<div class="entry-content clearfix">
			<div class="entry-meta clearfix">
				<span class="entry-date pull-left">
					<span class="post_day custom-font"><?php the_time('d'); ?></span>
					<span class="post_my"><?php the_time('/ F, Y'); ?></span>
				</span>
				
				<div class="entry-comment pull-right">
				<a href="#respond">
					<i class="fa fa-comments"></i>
					<?php $comment_count = $post->comment_count; ?>
					<?php echo $post->comment_count .'<span>'. ( ( $comment_count > 1 ) ? esc_html__(' comments', 'zanbil') : esc_html__(' comment', 'zanbil') .'</span>' ); ?>
				</a>
				</div>
				<div class="entry-author pull-right">
				    <span class="category-author"><i class="fa fa-user"></i><?php echo esc_html__('Posted by','zanbil') ?> <?php the_author_posts_link(); ?></span>
				</div>
			</div>
			<h1 class="entry-title clearfix"><?php the_title(); ?></h1>
			<div class="entry-summary single-content ">
				<?php the_content(); ?>				
				<div class="clearfix"></div>
				
				<!-- link page -->
				<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'zanbil' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>	
				<div class="clearfix"></div>
			</div>
			
			<!-- Tag -->
			<?php if(get_the_tag_list()) { ?>
				<div class="entry-tag single-tag pull-left">
					<?php echo get_the_tag_list('<span class="custom-font title-tag">برچسب ها: </span>',' , ','');  ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="clearfix"></div>
    <?php comments_template('/templates/comments.php'); ?>
</div>
<?php endwhile; ?>
