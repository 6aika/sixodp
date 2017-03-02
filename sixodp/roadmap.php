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

			echo do_shortcode('[wp-trello type="lists" id="557aae1c6bde881c9cbc6e0e" link="yes"]');

		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
