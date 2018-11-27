<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Oletuspohja valikolla
 *
 * @package WordPress
 * @subpackage Sixodp
 */

$post = get_queried_object();
$grandparent_id = get_post_grandparent_id($post->ID);
$parent_page = get_page($grandparent_id);
get_header();
$category = $post;
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

    <?php get_template_part('partials/page-hero'); ?>
    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
              <?php if ($parent_page && $parent_page != $post)  : ?>
                  <li><a href="<?php echo get_permalink($parent_page); ?>"><?php echo get_the_title( $parent_page); ?></a></li>
              <?php endif;?>
              <?php if ($post->post_parent && $post->post_parent != $grandparent_id) : ?>
              <li><a href="<?php echo get_permalink($post->post_parent); ?>"><?php echo get_the_title( $post->post_parent ); ?></a></li>
              <?php endif;?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php echo get_the_title( $post->post_parent ); ?></h1>
      </div>
    </div>
    <div class="page-content container">
      <?php

        while ( have_posts() ) : ?>
          <div class="row">
            <?php
            include(locate_template( 'partials/sidebar.php' ));
            the_post();
            get_template_part( 'partials/content' );
            ?>
          </div>
          <?php
        endwhile;

      ?>
    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
