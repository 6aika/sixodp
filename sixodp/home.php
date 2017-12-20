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

<div id="primary">
	<main id="main" class="site-main site-main--home" role="main">
		<?php

      get_template_part( 'partials/hero' );
			get_template_part( 'partials/categories' );
			get_template_part( 'partials/horizontal-accordion' );
			get_template_part( 'partials/featured-apps' );
			get_template_part( 'partials/featured-content' );
      get_template_part( 'partials/latest-updates' );

		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
