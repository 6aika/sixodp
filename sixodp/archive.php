<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Sixodp
 */

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main wrapper" role="main">
    
    <?php
      get_template_part('partials/header-logos');
    ?>

    <div class="page-hero"></div>
    <div class="page-content container">
      <h1 class="heading--archive"><?php echo _e(get_post_type_object(get_post_type())->labels->name); ?></h1>
      <?php
      // Start the loop.
      while ( have_posts() ) : the_post();
        // Include the page content template.
        get_template_part('partials/archive-item');

        // End of the loop.
      endwhile;
      ?>
    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
