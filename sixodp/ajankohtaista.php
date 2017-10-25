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
      get_template_part('partials/header-logos');
    ?>

    <div class="container">
      <h1 class="heading-main"><?php _e('Latest updates', 'sixodp') ?></h1>
    </div>
    <?php get_template_part( 'partials/horizontal-accordion' ); ?>

    <?php get_template_part( 'partials/ajankohtaista-links'); ?>

    <div class="wrapper--twitterfeed">
      <div class="container">
        <h2 class="heading-main"><?php _e('Twitter', 'sixodp') ?></h2>
      </div>
      <?php
        echo do_shortcode('[custom-twitter-feeds num=12 class="twitterfeed" showheader=false showbutton=false]');
      ?>
    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
