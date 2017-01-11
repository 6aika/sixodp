<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Home
 *
 * @package WordPress
 * @subpackage Sixodp
 */


get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main site-main--home" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

      get_template_part( 'partials/searchbar' );
			get_template_part( 'partials/categories' );
			get_template_part( 'partials/featured-posts' );
			get_template_part( 'partials/featured-datasets' );
			get_template_part( 'partials/featured-apps' );

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
