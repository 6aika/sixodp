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

get_header(); 
$parent_category = get_translated_category_by_slug('tuki');
$themes_category = get_translated_category_by_slug('teemat');
$category = get_queried_object();
?>

<div id="primary" class="content-area">
  <div id="main" class="site-main" role="main">

    <?php get_template_part('partials/page-hero'); ?>

    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li><a href="<?php echo get_category_link($parent_category) ?>"><?php echo $parent_category->name ?></a></li>
            <?php if ($parent_category->term_id != $category->term_id) { ?>
              <li><a href="<?php echo get_category_link($category) ?>"><?php echo $category->name ?></a></li>
            <?php } ?>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php echo $category->name ?></h1>
      </div>
    </div>
    <div id="maincontent" class="page-content container">

      <div class="wrapper">

        <div class="row">
          <?php include( locate_template('partials/tuki-sidebar.php') ); ?>

          <div class="col-md-9 col-sm-12 col-xs-12 news-content">
            <div class="cards cards--3">
              <?php
              while ( have_posts() ) : the_post(); ?>
                <?php
                  $item = array(
                    'image_url' => get_post_thumbnail_url($post),
                    'title' => $post->post_title,
                    'show_rating' => false,
                    'date_updated' => $post->post_date,
                    'notes' => $post->post_content,
                    'url' => get_the_permalink(),
                  );
                  include(locate_template( 'partials/card-image.php' ));
                ?>
              <?php
              endwhile;
              wp_reset_postdata();
              ?>
            </div>
            <div class="paginate">
              <div class="paginate-prev">
                <?php previous_posts_link() ?>
              </div>
              <div class="paginate-next">
                <?php next_posts_link() ?>
              </div>
            </div>
          </div>
        </div><!-- .row -->
      </div><!-- .wrapper -->
    </div><!-- .page-content -->
  </main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>
