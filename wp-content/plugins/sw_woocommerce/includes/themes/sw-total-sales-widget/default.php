<?php
	$widget_id 		  = isset( $widget_id ) ? $widget_id : 'total_sale_slider_'.rand().time();
	$top_seller = $this->GetTopSalesProducts( $from_date, $to_date, $numberposts );
?>
<div id="<?php echo 'slider_'.$widget_id; ?>" class="sw-woo-container-slider woo-total-sale-slider responsive-slider loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="block-title">
		<h2><?php echo $title1; ?></h2>
	</div>  
	<?php 
		if( count( $top_seller ) < 1 ){
			esc_html_e( 'No Order Found!', 'sw_woocommerce' );
		}else{
			
	?>
	<div class="resp-slider-container">
		<div class="slider responsive">	
		<?php 	
			$product_arr = array();
			$product_qty = array();
			foreach( $top_seller as $key => $top ){
				$product_arr[$key] = $top->product_id;
				$product_qty[$key] = $top->qty;
			}
			if( $category == '' ) :
				$_pf = new WC_Product_Factory();  
				foreach( $top_seller as $key => $top ){					
					global $product, $post;
					$product = $_pf->get_product( $top->product_id );
					$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
		?>
					<div class="item <?php echo esc_attr( $class )?>">
						<?php include( 'default-item.php' ); ?>
					</div>
		<?php 
					wp_reset_postdata();
				}
			else :
				$key = 0;
				$array = array(
					'post_type' => 'product',
					'tax_query' => array(
						array(
							'taxonomy'	=> 'product_cat',
							'field'			=> 'slug',
							'terms'			=> $category
						)
					),
					'post__in'	=> $product_arr,
					'orderby'		=> 'post__in'					
				);
				$list = new WP_Query( $array );
				while( $list->have_posts() ) : $list->the_post();
					global $product;
					$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
		?>
					<div class="item <?php echo esc_attr( $class )?>">
						<?php include( 'default-item.php' ); ?>
					</div>
		<?php 
				$key++;
				endwhile;
				wp_reset_postdata();
			endif;
		?>
		</div>
	</div> 
	<?php } ?>
</div>