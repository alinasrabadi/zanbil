<?php get_header(); ?>

<div id="main" class="theme-clearfix" role="document">
<?php zanbil_breadcrumb_title(); ?>

	<div class="container">			
			<?php 
				get_template_part('templates/content');
			?>
	</div>
</div>

<?php get_footer(); ?>