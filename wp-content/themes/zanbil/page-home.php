<?php 
/*
Template Name: Page Home
*/
?>

<?php get_header();
$page_title = esc_html( get_the_title() );
echo '<h1 class="seo-home">'.$page_title.'</h1>';
 ?>

	<div class="container">
		<div class="row">
			 <div id="contents" role="main" class="col-lg-12 col-md-12 col-sm-12">
				<?php if(have_posts()):
					while (have_posts()) : the_post(); ?>
						<div <?php post_class(); ?>>
							<div class="entry-content">
							  <?php the_content(); ?>
							</div>
						</div>
						<?php
					endwhile;
				else:
					get_template_part('templates/no-results');
				endif;
			?>
			</div>
		</div>
		
	</div>
<?php get_footer(); ?>