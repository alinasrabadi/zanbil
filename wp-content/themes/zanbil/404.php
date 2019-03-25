<?php get_template_part('header'); ?>
<div class="wrapper_404">
	<div class="container">
		<div class="row">
	        <div class="col-lg-12 col-md-12">
				<div class="content_404">
					<div class="block-middle">
					</div>
					<div class="block-bottom">
						<a href="<?php echo esc_url( home_url('/') ); ?>" class="custom-font btn-404 back2home" title="<?php esc_attr_e( 'back to Home', 'zanbil' ) ?>"><i class="fa fa-arrow-circle-right"></i><?php esc_html_e( "back to Home", 'zanbil' )?></a>
					</div>
					<script>
						function goBack() {
							window.history.back()
						}
					</script>
				</div>
			</div>
	    </div>
	</div>
	
</div>
<?php get_template_part('footer'); ?>