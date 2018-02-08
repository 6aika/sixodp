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
    <?php get_template_part('partials/page-hero'); ?>
    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li><a href="<?php echo get_home_url() ?>/ajankohtaista"><?php _e('Ajankohtaista', 'sixodp') ?></a></li>
          </ol>
        </div>
      </div>
    </div>
    <?php
      $horizontal_accordion_heading = __('Latest updates', 'sixodp');
      include(locate_template( 'partials/horizontal-accordion.php' ));
    ?>

    <?php get_template_part( 'partials/ajankohtaista-links'); ?>

    <div class="wrapper--twitterfeed wrapper--featured">
      <div class="container">
        <h2 class="heading-page"><?php _e('Twitter', 'sixodp') ?></h2>
      </div>
      <?php
        echo do_shortcode('[custom-twitter-feeds num=12 class="twitterfeed" showheader=false showbutton=false]');
      ?>
      <div class="container">
        <div class="row btn-container">
          <a href="https://twitter.com/@6Aika" class="btn btn-transparent--inverse" target="_blank">Lue lisää Twitterissä</a>
        </div>
      </div>
    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
