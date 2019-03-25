<?php
	/*
	** Default Item
	*/
?>
<div class="item-wrap">
	<div class="item-detail">
		<div class="item-top">
			<span class="item-number"><?php echo $key + 1; ?></span>
			<span class="item-total-sales"><?php echo $product_qty[$key] . esc_html__( ' sale(s)', 'sw_woocommerce' ); ?></span>
		</div>
		<div class="item-img products-thumb">											
			<!-- quickview & thumbnail  -->
			<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
		</div>										
		<div class="item-content">
			<h4><a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo get_the_title( $post->ID );?>"><?php echo get_the_title( $post->ID ); ?></a></h4>								
														
			<!-- rating  -->
			<?php 
				$rating_count = $product->get_rating_count();
				$review_count = $product->get_review_count();
				$average      = $product->get_average_rating();
			?>
			<div class="reviews-content">
				<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : ''; ?></div>
				<div class="item-number-rating">
					<?php //echo $review_count; _e(' Review(s)', 'sw_woocommerce');?>
				</div>
			</div>	
			<!-- end rating  -->
			<?php if ( $price_html = $product->get_price_html() ){?>
			<div class="item-price theme-clearfix">
				<span>
					<?php echo $price_html; ?>
				</span>
			</div>
			<?php } ?>
			<!-- add to cart, wishlist, compare -->
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>													
	</div>
</div>