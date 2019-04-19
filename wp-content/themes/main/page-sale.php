<?php 
/*
Template Name: Page sale
*/
?>
<!DOCTYPE html>
<!--[if IE 10]>         <html class="no-js lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 11]>         <html class="no-js ie11" <?php language_attributes(); ?>> <![endif]-->
<html class="no-js" dir="rtl" lang="fa-IR">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>	
	<div class="container">
		<div class="row">
			 <div id="contents" role="main" class="col-lg-12 col-md-12 col-sm-12">
				<?php
				get_template_part('templates/content', 'page')
				?>
			</div>
		</div>
		
	</div>
<?php wp_footer(); ?>
</body>
</html>