<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

 get_header(); ?>

 <div id="primary" class="content-area">
 	<main id="main" class="site-main site-main--home" role="main">
		<h1 class="page-title">Hae sivustolta</h1>
        <?php get_search_form(); ?>
        <?php get_template_part( 'partials/search-content' ); ?>
 	</main><!-- .site-main -->
 </div><!-- .content-area -->
 <?php get_footer(); ?>
