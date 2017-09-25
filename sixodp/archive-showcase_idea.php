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

    <div class="page__hero"></div>
    <div class="page__content container">
      <h1 class="heading--archive"><?php _e('Showcase ideas') ?></h1>
      <a href="<?php echo get_permalink(get_translated_page_by_title('Uusi sovellusidea')); ?>" class="btn btn-small btn-secondary"><?php _e('New showcase idea') ?> &raquo;</a>
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
