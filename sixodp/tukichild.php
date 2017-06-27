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
    <div class="wrapper">
      <div class="container">
        <?php

          while ( have_posts() ) : the_post();

            get_template_part( 'partials/tuki-headingbar' );
            get_template_part( 'partials/tuki-sidebar' );
            get_template_part( 'partials/content' );

          endwhile;
          
        ?>
      </div>
    </div>
    <?php
      $morelinks_title = "Lisää aiheesta";
      $links = get_tuki_links();
      include(locate_template( 'partials/morelinks.php' ));
    ?>
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
