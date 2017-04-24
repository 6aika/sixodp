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

      get_template_part('partials/header-logos');

			// Get the board id for retrieving the lists
			$board_id = get_option('wptsettings_settings')['wptsettings_helper_boards'];
			
			// Render the widget
			echo do_shortcode('[wp-trello type="lists" id="'.$board_id.'" link="yes"]');
			
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
