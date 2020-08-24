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

    <?php get_template_part('partials/page-hero'); ?>

    <div id="maincontent" class="page-content container">

      <div class="headingbar">
        <h1 class="heading-main"><?php echo _e(get_post_type_object(get_post_type())->labels->name); ?></h1>
      </div>

      <div class="row">
        <div class="sidebar col-md-3 col-sm-12 col-xs-12">

        </div>

        <div class="col-md-9 col-sm-12 col-xs-12 news-content">
          <div class="cards cards--3">
            <?php
              // Start the loop.
              while ( have_posts() ) : the_post();
                $item = array(
                  'image_url' => get_post_thumbnail_url($post),
                  'title' => $post->post_title,
                  'show_rating' => false,
                  'date_updated' => $post->post_date,
                  'notes' => $post->post_content,
                  'url' => get_the_permalink(),
                );
                include(locate_template( 'partials/card-image.php' ));
              endwhile;
            ?>
          </div>
        </div>
      </div>

    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
