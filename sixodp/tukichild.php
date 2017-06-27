<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Tuki childpage
 *
 * @package WordPress
 * @subpackage Sixodp
 */


get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">
  <?php

    while ( have_posts() ) : the_post();
    ?>
    <div class="wrapper">
      <div class="container">

        <?php
          get_template_part( 'partials/tuki-headingbar' );
          get_template_part( 'partials/tuki-sidebar' );
          get_template_part( 'partials/content' );
        ?>
      </div>
    </div>
    <?php
      $morelinks_title = "Lisää aiheesta";

      $args = array(
        'cat' => array_map(function($category) { return $category->term_id; }, get_the_category()),
        'post_type' => 'page',
        'exclude' => get_the_id(),
        'posts_per_page' => 4
      );

      $links = get_posts($args);

      include(locate_template( 'partials/morelinks.php' ));
    endwhile;
    ?>
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
