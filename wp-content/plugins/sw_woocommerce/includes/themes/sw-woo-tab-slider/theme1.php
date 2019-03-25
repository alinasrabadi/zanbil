<?php 
	$id = $this -> number;
	$id ++;
	$tag_id = 'sw_woo_tab_' .rand().time();
	//var_dump( $select_order );
	if( !is_array( $select_order ) ){
		$select_order = explode( ',', $select_order );
	}
?>
<div class="sw-woo-tab-style2" id="<?php echo esc_attr( $tag_id ); ?>" >
	<div class="resp-tab" style="position:relative;">
		<div class="top-tab-slider clearfix">
			<div class="order-title">
			<?php
			if( $category != '' ){
				$term = get_term_by( 'slug', $category, 'product_cat' );
				echo '<h2>'. $term->name . '</h2>'; 
			}else{
				echo '<h2>'. esc_html__( 'All Categories', 'sw_woocommerce' ) . '</h2>';
			}?>
			</div>
			<ul class="nav nav-tabs">
			<?php 
					$tab_title = '';
					foreach( $select_order as $i  => $so ){						
						switch ($so) {
						case 'latest':
							$tab_title = __( 'Latest Products', 'sw_woocommerce' );
						break;
						case 'rating':
							$tab_title = __( 'Top Rating Products', 'sw_woocommerce' );
						break;
						case 'bestsales':
							$tab_title = __( 'Best Selling Products', 'sw_woocommerce' );
						break;						
						default:
							$tab_title = __( 'Featured Products', 'sw_woocommerce' );
						}
				?>
				<li <?php echo ( $i == ( $tab_active -1 ) )? 'class="active"' : ''; ?>>
					<a href="#<?php echo $so . '_' . $id; ?>" data-toggle="tab">
						<?php echo esc_html( $tab_title ); ?>
					</a>
				</li>			
			<?php } ?>
			</ul>
		</div>		
		<div class="category-slider-content clearfix">	
			<div class="tab-content panel-group clearfix">	
			<!-- Product tab slider -->
			<?php 
				foreach( $select_order as $i  => $so ){ 
				switch ($so) {
					case 'latest':
						$tab_title = __( 'Latest Products', 'sw_woocommerce' );
					break;
					case 'rating':
						$tab_title = __( 'Top Rating Products', 'sw_woocommerce' );
					break;
					case 'bestsales':
						$tab_title = __( 'Best Selling Products', 'sw_woocommerce' );
					break;						
					default:
						$tab_title = __( 'Featured Products', 'sw_woocommerce' );
				}
			?>
				<h4><a href="#<?php echo $so . '_' . $category.$id; ?>" class="restab-heading <?php echo ( $i == ( $tab_active -1 ) ) ? '' : 'collapsed'; ?>" data-toggle="collapse"><?php echo $tab_title; ?></a></h4>
				<div class="tab-pane <?php echo ( $i == ( $tab_active -1 ) ) ? 'active in' : ''; ?>" id="<?php echo $so . '_' . $id; ?>">
				<?php
					global $woocommerce;
					$default = array();
					if( $category == '' ){
						$default['tax_query'] =  array(
							array(
								'taxonomy'	=> 'product_cat',
								'field'		=> 'slug',
								'terms'		=> $category,
								'operator' 	=> 'IN'
							)
						);
					}
					if( $so == 'latest' ){
						$default = array(
							'post_type'	=> 'product',							
							'paged'		=> 1,
							'showposts'	=> $numberposts,
							'orderby'	=> 'date'
						);
					}
					if( $so == 'rating' ){
						$default = array(
							'post_type'		=> 'product',
							'post_status' 	=> 'publish',
							'no_found_rows' => 1,					
							'showposts' 	=> $numberposts						
						);
						if( sw_woocommerce_version_check( '3.0' ) ){	
							$default['meta_key'] = '_wc_average_rating';	
							$default['orderby'] = 'meta_value_num';
						}else{	
							add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
						}
					}
					if( $so == 'bestsales' ){
						$default = array(
							'post_type' 			=> 'product',
							'post_status' 			=> 'publish',
							'ignore_sticky_posts'   => 1,
							'paged'	=> 1,
							'showposts'				=> $numberposts,
							'meta_key' 		 		=> 'total_sales',
							'orderby' 		 		=> 'meta_value_num',
							'meta_query' 			=> array(
								array(
									'key' 		=> '_visibility',
									'value' 	=> array( 'catalog', 'visible' ),
									'compare' 	=> 'IN'
								)
							)
						);
					}
					if( $so == 'featured' ){
						$default = array(
							'post_type'	=> 'product',
							'post_status' 			=> 'publish',
							'ignore_sticky_posts'	=> 1,
							'posts_per_page' 		=> $numberposts,
							'orderby' 				=> $orderby,
							'order' 				=> $order,
						);
						if( sw_woocommerce_version_check( '3.0' ) ){	
							$default['tax_query'][] = array(						
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',
								'operator' => 'IN',	
							);
						}else{
							$default['meta_query'] = array(
								array(
									'key' 		=> '_featured',
									'value' 	=> 'yes'
								)					
							);				
						}
					}
					$list = new WP_Query( $default );
					$max_page = $list -> max_num_pages;
					if( $so == 'rating' && ! sw_woocommerce_version_check( '3.0' ) ){
						remove_filter( 'posts_clauses',  array( $this, 'order_by_rating_post_clauses' ) );
					}
				?>
					<div id="<?php echo $so.'_category_id_'.$category.$id; ?>" class="woo-tab-container-slider responsive-slider loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
						<div class="resp-slider-container">
							<div class="slider responsive">
							<?php 
								$count_items 	= 0;
								$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
								$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
								$i 				= 0;
								while($list->have_posts()): $list->the_post();
								global $product, $post;
								$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
								if( $i % $item_row == 0 ){
							?>
								<div class="item <?php echo esc_attr( $class )?>">
							<?php } ?>
									<?php include( WCTHEME . '/default-item.php' ); ?>
								<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
							<?php $i++; endwhile; wp_reset_postdata();?>
							</div>
						</div>
					</div>			
				</div>
			<?php } ?>
			<!-- End product tab slider -->
			</div>
		</div>
	</div>
</div>