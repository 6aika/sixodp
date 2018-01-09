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
    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li><a href="<?php echo home_url( $wp->request ) ?>"><?php _e('Support', 'sixodp') ?></a></li>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php _e('Support', 'sixodp') ?></h1>
      </div>
    </div>
    <div class="page-content container">
      <div class="wrapper">
        <?php while ( have_posts() ) : the_post(); ?>
          <div class="row">
            <?php
              include( locate_template('partials/tuki-sidebar.php') );
              get_template_part( 'partials/content' );
            ?>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>
