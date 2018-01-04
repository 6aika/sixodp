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
            <li><a href="<?php echo get_home_url() ?>"><?php echo _('Home') ?></a></li>
            <li><a href="<?php echo get_category_link($parent_category) ?>"><?php echo $parent_category->name ?></a></li>
            <?php if ($parent_category->term_id != $category->term_id) { ?>
              <li><a href="<?php echo get_category_link($category) ?>"><?php echo $category->name ?></a></li>
            <?php } ?>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php echo $category->name ?></h1>
      </div>
    </div>
    <div class="page-hero-content container">
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
