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
      
      $args = array ( 'category' => get_queried_object()->term_id, 'posts_per_page' => -1, 'post_type' => 'page');
      $myposts = get_posts( $args );
      foreach( $myposts as $post ) :  setup_postdata($post);
        echo get_template_part('partials/archive-item');
        //echo '<h1><a href="'.get_the_permalink().'">'.the_title().'</a></h1>';
      endforeach;
      ?>
    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
