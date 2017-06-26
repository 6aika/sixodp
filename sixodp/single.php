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

        ?>
        <h1 class="heading--main"><?php the_title() ?></h1>
        <article class="article"><?php the_content() ?></article>
        <?php

        include( locate_template('partials/article-footer.php') );

        // End of the loop.
      endwhile;
      ?>
      <div class="addthis_toolbox">
        <a class="addthis_button_facebook_like at300b"></a>
        <a class="addthis_button_tweet at300b"></a>
      </div>
    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
