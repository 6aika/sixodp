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
      <?php
      // Start the loop.
      while ( have_posts() ) : the_post();
        global $post;
        $post_slug=$post->post_name;

        //var_dump($post);

        // Include the page content template.
        echo '<h1><a href="'.get_the_permalink().'">'.get_the_title().'</a></h1>';

        // End of the loop.
      endwhile;
      ?>
    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
