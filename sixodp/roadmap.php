<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Roadmap
 *
 * @package WordPress
 * @subpackage Sixodp
 */


get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main site-main--home" role="main">
		<?php

      get_template_part( 'partials/hero' );
			echo do_shortcode('[wp-trello type="lists" id="58a6ee156f0df90aea3f7d6d" link="yes"]');

		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
