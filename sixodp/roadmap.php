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

    <?php get_template_part('partials/page-hero'); ?>
    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php the_title(); ?></h1>
      </div>
    </div>
    <div class="page-content container">
      <!--
      <div class="row">
        <div class="sidebar col-md-3 col-sm-5 col-xs-12">

        </div>
        <div class="col-md-9 col-sm-7 col-xs-12 news-content">
      -->
      <?php
        // Start the loop.
        while ( have_posts() ) : the_post();

          // Include the page content template.
          get_template_part('partials/roadmap-content');

          // End of the loop.
        endwhile;
      ?>
    </div>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
