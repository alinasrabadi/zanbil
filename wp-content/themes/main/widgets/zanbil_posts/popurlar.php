<?php 
if (!isset($instance['category'])){
	$instance['category'] = 0;
}
extract($instance);

$default = array(
	'category' => $category,
	'meta_query' => array('key' => 'count_page_hits',
						  'value' => 'meta_value_number'),
	'include' => $include,
	'exclude' => $exclude,
	'post_status' => 'publish',
	'numberposts' => $numberposts
);

$list = get_posts($default);
if (count($list)>0){
?>
<div class="widget-popular-blog">
	<ul>
		<?php foreach ($list as $key => $post){?>
		<?php if (get_the_post_thumbnail( $post->ID ) ) {?>
			<li class="widget-post item-<?php echo $key;?>">
				<div class="widget-post-inner">			
					<div class="widget-thumb">
						<a href="<?php echo get_permalink($post->ID)?>" title="<?php echo esc_attr( $post->post_title );?>"><?php echo get_the_post_thumbnail($post->ID, 'zanbil_popular_post');?></a>
					</div>
				<?php } ?>
					<div class="item-title">
							<h4><a href="<?php echo get_permalink($post->ID)?>" title="<?php echo esc_attr( $post->post_title );?>"><?php echo esc_html( $post->post_title );?></a></h4>
							<div class="item-meta">
								<span class="author"><?php esc_html_e('By', 'zanbil'); ?> <?php the_author_posts_link(); ?></span>	
							</div>
					</div>
				</div>
			</li>
			<?php }?>
	</ul>
</div>
<?php }?>