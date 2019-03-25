<?php 

/**
	* Layout Theme Default
	* @version     1.0.0
**/
?>
<div class="products-entry item-wrap">
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
<script type="text/javascript">
    jQuery(".product_type_simple.add_to_cart_button").attr('title', 'Add to Cart');
	jQuery("a.fancybox").attr('title', 'Quickview');
</script>