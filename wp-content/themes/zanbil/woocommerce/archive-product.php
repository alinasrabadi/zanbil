<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php get_template_part('header'); ?>


<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

<?php zanbil_breadcrumb_title(); ?>

<?php endif; ?>

<div class="container">
<div class="row">

<?php do_action( 'woocommerce_before_shop_aside'); ?>

<aside id="left" class="sidebar col-lg-3 col-md-4 col-sm-4">
	<?php dynamic_sidebar('left-product'); ?>
</aside>

<div id="contents" class="content col-lg-9 col-md-8 col-sm-8" role="main">
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		 global $post;
		do_action('woocommerce_before_main_content');
	?>
	<div class="products-wrapper">	
				
		<?php if ( have_posts() ) : ?>
		    <?php do_action('woocommerce_message'); ?>
			<div class="products-nav">
			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
			</div>
			<div class="clear"></div>
			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>
				
				<?php while ( have_posts() ) : the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
			<div class="clear"></div>			
			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>
		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>
	</div>
	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>
</div>
</div>
<?php do_action( 'woocommerce_archive_description' ); ?>
</div>
<script>
(function($) {
	"use strict";
$( window ).load(function() {
	/* Change Layout */
	$('.grid-view').on('click',function(){
		$('.list-view').removeClass('active');
		$('.grid-view').addClass('active');
		jQuery("ul.products-loop").fadeOut(300, function() {
			$(this).removeClass("list").fadeIn(300).addClass( 'grid' );			
		});
	});
	
	$('.list-view').on('click',function(){
		$( '.grid-view' ).removeClass('active');
		$( '.list-view' ).addClass('active');
		$("ul.products-loop").fadeOut(300, function() {
			jQuery(this).addClass("list").fadeIn(300).removeClass( 'grid' );
		});
	});
	/* End Change Layout */
   
});
})(jQuery);

function showMore() {

	var listData = Array.prototype.slice.call(document.querySelectorAll('#dataList .category-card:not(.showncc)')).slice(0, 3);

  for (var i=0; i < listData.length; i++)
  {
  	listData[i].className  = 'category-card showncc';
  }
  switchButtons();
}



function switchButtons() {
	var hiddenElements = Array.prototype.slice.call(document.querySelectorAll('#dataList .category-card:not(.showncc)'));
  if(hiddenElements.length == 0)
  {
  	document.getElementById('moreButton').style.display = 'none';
  }
}

onload= function(){
	showMore();
}				
</script>
<?php get_template_part('footer'); ?>
