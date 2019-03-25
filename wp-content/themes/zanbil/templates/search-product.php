<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$product_cat = $_GET['search_category'];
	$s = $_GET['s'];	
	$args_product = array(
		's' => $s,
		'post_type'	=> 'product',
		'posts_per_page' => 12,
		'paged' => $paged,
	);
	if( isset( $product_cat ) && $product_cat != '' ){
		$args_product['tax_query'] = array(
			array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'id',
				'terms'	=> $product_cat				
			)
		);
	}
?>
<div class="content-list-category" style="margin-left: 15px;">
	<div class="content_list_product">
		<div class="products-wrapper">		
		<?php
			$product_query = new wp_query( $args_product );
			if( $product_query -> have_posts() ){
			?>
			<ul id="loop-products" class="products-loop row clearfix grid-view grid">
			<?php while( $product_query -> have_posts() ) : $product_query -> the_post(); global $product, $post;?>
				<li class="item col-lg-2 col-md-3 col-sm-4 col-xs-6">
					<div class="products-entry item-wrap clearfix">
						<div class="item-detail">
							<div class="item-img products-thumb">
								<?php
									/**
									 * woocommerce_before_shop_loop_item_title hook
									 *
									 * @hooked woocommerce_show_product_loop_sale_flash - 10
									 * @hooked woocommerce_template_loop_product_thumbnail - 10
									 */
									do_action( 'woocommerce_before_shop_loop_item_title' );
									
								?>
							</div>
							<div class="item-content products-content">
								<?php
									/**
									 * woocommerce_shop_loop_item_title hook
									 *
									 * @hooked woocommerce_template_loop_product_title - 10
									 */
									do_action( 'woocommerce_shop_loop_item_title' );

									/**
									 * woocommerce_after_shop_loop_item_title hook
									 *
									 * @hooked woocommerce_template_loop_rating - 5
									 * @hooked woocommerce_template_loop_price - 10
									 */
									do_action( 'woocommerce_after_shop_loop_item_title' );
								?>
								<?php
									/**
									 * woocommerce_after_shop_loop_item hook
									 *
									 * @hooked woocommerce_template_loop_add_to_cart - 10
									 */
									do_action( 'woocommerce_after_shop_loop_item' );
								?>
								</div>
							</div>
						</div>
				</li>
			<?php	endwhile;
				
			?>
			</ul>
			<!--Pagination-->
			<?php if ($product_query->max_num_pages > 1) : ?>
			<div class="pag-search ">
				<div class="pagination nav-pag pull-right">
					<ul class="list-inline">
						<?php if (get_previous_posts_link()) : ?>
						 <li class="paginations"><?php previous_posts_link('<i class="fa fa-caret-left"></i>'); ?></li>
							
						<?php endif; ?>

						<?php 
					if ($paged < 3){
						$i = 1;
					}
					elseif ($paged < $product_query->max_num_pages - 2){
						$i = $paged -1 ;
					}
					else {
						$i = $product_query->max_num_pages - 3;
					}
					 
					if ($product_query->max_num_pages > $i + 3){
						$max = $i + 2;
					}
					else $max = $product_query->max_num_pages;
			
					if ($paged > 3 && $product_query->max_num_pages > 4) {?>
						<li><a href="<?php echo get_pagenum_link('1')?>">1</a></li>
						<li><a>...</a></li>
						<?php }
					for ($i = 1; $i<= $max ; $i++){?>
					<?php if (($paged == $i) || ( $paged ==1 && $i==1)){?>
						<li class="disabled"><a><?php echo $i?> </a></li>
						<?php } else {?>
						<li><a href="<?php echo get_pagenum_link($i)?>"><?php echo $i?></a></li>
						<?php }?>
					<?php }?>

						<?php if ($max < $product_query->max_num_pages) {?>
						<li><a>...</a></li>
						<li><a
							href="<?php echo get_pagenum_link($product_query->max_num_pages)?>"><?php echo $product_query->max_num_pages?>
						</a></li>
						<?php }?>

						<?php if ( get_next_posts_link() && ( $paged < $product_query->max_num_pages ) ) :  ?>
						<li class="paginations"><?php next_posts_link( '<i class="fa fa-caret-right"></i>' ); ?>
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
	<?php endif;wp_reset_postdata(); ?>
	<!--End Pagination-->
	<?php 
		}else{
	?>
		<div class="alert alert-warning alert-dismissible" role="alert">
			<a class="close" data-dismiss="alert">&times;</a>
			<p>تتتتتتت</p>
		</div>
	<?php
		}
	?>
		</div>
	</div>
</div>