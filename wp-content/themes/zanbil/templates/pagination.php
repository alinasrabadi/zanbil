<?php if ($wp_query->max_num_pages > 1) : ?>
	<?php global $paged, $posts_per_page; ?>
<div class="pagination nav-pag pull-right">
	<span class="pagination-title">صفحه</span>
    <ul>
    	<?php if (get_previous_posts_link()) : ?>
	        <li class="prev"><?php previous_posts_link(__('Prev', 'zanbil')); ?></li>
	    <?php else: ?>
	 		<li class="disabled prev"><a><?php esc_html_e('Prev', 'zanbil'); ?></a></li>
	    <?php endif; ?>
	      
	      <?php 
	      	if($paged == 1 || $wp_query->max_num_pages <= 3){
	      		$i = 1;
	      	} elseif ($paged > $wp_query->max_num_pages - 3 && $paged > 3 ) {
	      		$i = $wp_query->max_num_pages - 3;
	      	} else $i = $paged -1;
	      	
	      	if ($wp_query->max_num_pages - $i > 3){
	      		$max_num_pages = $i + 3;
	      	} else $max_num_pages = $wp_query->max_num_pages;
	      	
	      	for ($i = 1; $i<= $max_num_pages ; $i++){?>
	      		<?php if ( ( $paged == $i ) || ( $paged == 0 && $i==1 ) ){?>
	      			<li class="disabled"><a><?php echo $i?></a></li>
	      		<?php } else {?>
	      			<li><a href="<?php echo get_pagenum_link($i)?>"><?php echo $i?></a></li>
	      		<?php }?>
	      	<?php }?>
      		
	      <?php if (get_next_posts_link()) : ?>
	        <li class="next"><?php next_posts_link(__('Next', 'zanbil')); ?></li>
	      <?php else: ?>
	        <li class="disabled next"><a><?php esc_html_e('Next', 'zanbil'); ?></a></li>
	      <?php endif; ?>
    </ul>
</div>
<?php endif; ?>
<!--End Pagination-->