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


get_header(); 
$parent_category = get_translated_category_by_slug('tuki');
$themes_category = get_translated_category_by_slug('teemat');
$category = get_queried_object();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

    <?php get_template_part('partials/page-hero'); ?>

    <div class="page-hero-content container">
      <div class="wrapper">
        <?php while ( have_posts() ) : the_post(); ?>
          <div class="row">
            <?php
              include( locate_template('partials/tuki-sidebar.php') );
              get_template_part( 'partials/content' );
            ?>
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
          ?>
        <?php endwhile; ?>
      </div>
    </div>
  </main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>
