<?php 

/**
	* Layout Tab Category Default
	* @version     1.0.0
**/

	$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();
	if( $category == '' ){
		return '<div class="alert alert-warning alert-dismissible" role="alert">
			<a class="close" data-dismiss="alert">&times;</a>
			<p>'. esc_html__( 'Please select a category for SW Woocommerce Tab Category Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
		</div>';
	}
	if( !is_array( $category ) ){
		$category = explode( ',', $category );
	}
	$nav_id = 'nav_tabs_res'.rand().time();
?>
<div class="sw-woo-tab-cat <?php echo esc_attr($elclass) ?>" id="<?php echo esc_attr( $widget_id ); ?>" >
	<div class="resp-tab" style="position:relative;">
		<div class="top-tab-slider clearfix">
			<div class="order-title">
				<?php
					$titles = strpos($title1, ' ');
					$title = ($titles !== false) ? '<span>' . substr($title1, 0, $titles) . '</span>' .' '. substr($title1, $titles + 1): $title1 ;
					echo '<h2><strong>'. $title .'</strong></h2>';
				?>
			
				<?php if( $image_icon != '' ) { ?>
					<div class="order-icon">
						<?php 
							$image_thumb = wp_get_attachment_image( $image_icon, 'thumbnail' );
							echo $image_thumb; 
						 ?>
					</div>
				<?php } ?>
				
				<?php if( $description != '') {?>
					<div class="order-desc">
						<span><?php echo $description; ?></span>
					</div>
				<?php } ?>
			</div>			
			<ul class="nav nav-tabs" id="<?php echo esc_attr($nav_id); ?>">
				<?php
					foreach($category as $key => $cat){
						$terms = get_term_by('id', $cat, 'product_cat');
						if( $terms != NULL ){							
				?>
					<li class="<?php if( ( $key + 1 ) == $tab_active ){echo 'active'; }?>">
					<a href="#<?php echo esc_attr( $cat. '_' .$widget_id ) ?>" data-type="tab_ajax" data-row="<?php echo esc_attr( $item_row ) ?>" data-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>" data-category="<?php echo esc_attr( $cat ) ?>" data-toggle="tab" data-sorder="<?php echo esc_attr( $select_order ); ?>" data-catload="ajax" data-number="<?php echo esc_attr( $numberposts ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
						<?php echo $terms->name; ?>
					</a>
				</li>	
					<?php }} ?>
			</ul>
		</div>
        <div class="tab-content">
			<?php
				foreach($category as $key => $cat){
					$terms = get_term_by('id', $cat, 'product_cat');
					if( $terms != NULL ){				
			?>
			<div class="tab-pane<?php if( ( $key + 1 ) == $tab_active ){echo ' active in'; }?>" id="<?php echo esc_attr( $cat. '_' .$widget_id ) ?>"></div>
			<?php
					}
				}
			?>
		</div>
	</div>
</div>