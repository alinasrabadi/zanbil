<!DOCTYPE html>
<html class="no-js" dir="rtl" lang="fa-IR">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="body-wrapper theme-clearfix" id="body_wrapper">
		<?php 
		$zanbil_my_phone = zanbil_options()->getCpanelValue('phone_number');
$zanbil_page_hotdeals = zanbil_options()->getCpanelValue('hot_deals');
		?>
	<div id="header" class="header-style1">
		<div class="yt-header-middle">
			<div class="container">
				<div class="row">
					<div class="col-lg-2 col-md-3 logo-wrapper">
						<div class="logo">
							<a  href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<img src="<?php echo esc_attr( zanbil_options()->getCpanelValue('sitelogo') ); ?>" alt="<?php bloginfo('name'); ?>"/>
							</a>
						</div>
					</div>
					
					<div class="col-lg-10 col-md-9 yt-searchbox">
						<div class="search-pro">
							<a class="phone-icon-search  fa fa-search" href="#" title="<?php esc_attr_e ('Search','zanbil')?>"></a>
							<div id="sm_serachbox_pro" class="sm-serachbox-pro">
								<div class="sm-searbox-content">
									<?php get_template_part( 'widgets/zanbil_top/searchcate' ); ?>
								</div>
							</div>
							<?php get_template_part('widgets/zanbil_top/login-header1'); ?>
							<!-- MiniCart -->
							<div class="mini-cart-header">
								<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
							</div>
						</div>	
					</div>
				</div>
			</div>
			<div class="yt-header-under-2">
				<div class="container">
					<div class="row yt-header-under-wrap">
						<div class="yt-main-menu col-md-12">
							<div class="header-under-2-wrapper">
								<div class="yt-searchbox-vermenu">
									<div class="row">
										<div class="yt-header-under no-padding-l">
											<?php if ( has_nav_menu('primary_menu') ) {?>
												<nav id="primary-menu" class="primary-menu header-type-1">
													<div class="yt-menu">
														<div class="navbar-inner navbar-inverse">
															<?php
															if ( has_nav_menu( 'leftmenu' ) ) {
																wp_nav_menu( array( 'theme_location' => 'leftmenu','menu_class' => 'vertical-megamenu' ) );
															} ?> 
														</div>
													</div>
												</nav>
											<?php } ?>

											<!-- <?php 
												if ($zanbil_page_hotdeals != ''  && get_page( $zanbil_page_hotdeals ) != NULL ):
													$zanbil_pagedeal = get_page( $zanbil_page_hotdeals );
											?>
												<div class="menu-hotdeals hidden-sm hidden-xs">
													<a class="custom-font" href="<?php echo get_page_link( $zanbil_page_hotdeals ); ?>">
														<?php echo $zanbil_pagedeal->post_title; ?>
													</a>
												</div>						
											<?php endif ?> -->
											<!-- MiniCart -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
