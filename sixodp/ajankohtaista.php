<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Ajankohtaista
 *
 * @package WordPress
 * @subpackage Sixodp
 */


get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main site-main--home" role="main">
    <?php

      echo '<div class="container"><h1 class="heading--main">Ajankohtaista</h1></div>';
      get_template_part( 'partials/horizaccordion' );
      get_template_part( 'partials/ajankohtaista-links');
      echo '<div class="wrapper--twitterfeed">';
      echo '<div class="container"><h1 class="heading--main" style="margin-bottom:-20px;">Twitter</h1></div>';
      echo do_shortcode('[custom-twitter-feeds num=12 class="twitterfeed" screenname="tomdale" showheader=false showbutton=false]');
      echo '</div>';

    ?>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
