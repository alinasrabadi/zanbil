<?php 

global $woocommerce; ?>
<div class="top-form top-form-minicart zanbil-minicart pull-right">
    <a class="cart-contents"><?php echo $woocommerce->cart->get_cart_total(); ?></a>
	<div class="top-minicart-icon pull-right">
	    <?php echo '<span class="minicart-number">'.$woocommerce->cart->cart_contents_count.'</span>';?>
	</div>
	<div class="wrapp-minicart">
		<div class="minicart-padding">
			<ul class="minicart-content">
				<?php
				 	foreach($woocommerce->cart->cart_contents as $cart_item_key => $cart_item):
				 	$_product  = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_name = ( sw_woocommerce_version_check( '3.0' ) ) ? $_product->get_name() : $_product->get_title();
				 ?>
					<li>
						<a href="<?php echo get_permalink($cart_item['product_id']); ?>" class="product-image">
							<?php $thumbnail_id = ($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id']; ?>
							<?php echo get_the_post_thumbnail($thumbnail_id, array(90,90)); ?>
						</a>
					<?php 	global $product, $post, $wpdb, $average;
						$count = $wpdb->get_var($wpdb->prepare("
							SELECT COUNT(meta_value) FROM $wpdb->commentmeta
							LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
							WHERE meta_key = 'rating'
							AND comment_post_ID = %d
							AND comment_approved = '1'
							AND meta_value > 0
						",$cart_item['product_id']));

						$rating = $wpdb->get_var($wpdb->prepare("
							SELECT SUM(meta_value) FROM $wpdb->commentmeta
							LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
							WHERE meta_key = 'rating'
							AND comment_post_ID = %d
							AND comment_approved = '1'
						",$cart_item['product_id']));
					?>	
						<?php 	global $product, $post, $wpdb, $average; ?>
						<div class="detail-item">
							<div class="product-details"> 
								<h4><a class="title-item" href="<?php echo get_permalink($cart_item['product_id']); ?>"><?php echo esc_html( $product_name ); ?></a></h4><div class="clear"></div>			
								<div class="product-price">
                                	<div class="qty">
										<span> X </span>
										<?php echo '<span>'.esc_html( $cart_item['quantity'] ).'</span>'; ?>
									</div>
									<span class="price"><?php echo $woocommerce->cart->get_product_subtotal($cart_item['data'], 1); ?></span>	      
								</div>
								<div class="product-action">
									<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="btn-remove" title="%s"><span class="fa fa-trash-o"></span></a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), 'حذف' ), $cart_item_key ); ?>              
								</div>
							</div>	
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="cart-checkout">
			    <div class="price-total">
				   <span class="label-price-total">مجموع سبد خرید : </span>
				   <span class="price-total-w"><span class="price"><?php echo $woocommerce->cart->get_cart_total(); ?></span></span>			
				</div>
				<div class="cart-links clearfix">
					<div class="cart-link"><a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>" title="نمایش سبد خرید">نمایش سبد خرید</a></div>
					<div class="checkout-link"><a href="<?php echo get_permalink(get_option('woocommerce_checkout_page_id')); ?>" title="تسویه حساب">تسویه حساب</a></div>
				</div>
			</div>
		</div>
	</div>
</div>