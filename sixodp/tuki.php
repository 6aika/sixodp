<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Tuki
 *
 * @package WordPress
 * @subpackage Sixodp
 */


get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main site-main--home" role="main">
		<?php

			echo '<h1 class="heading--tuki">Tuki</h1>';
			get_template_part( 'partials/tuki-contentbox' );
      get_template_part( 'partials/tuki-themes' );

		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
