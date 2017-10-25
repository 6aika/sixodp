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
    <?php get_template_part('partials/header-logos'); ?>

    <div class="page-hero"></div>
    <div class="page-hero-content container">

      <div class="wrapper">
        <div class="headingbar">
          <h1 class="heading-main">
            <a href="<?php echo get_category_link($parent_category) ?>"><?php echo $parent_category->name ?></a>
            <?php if ($parent_category->term_id != $category->term_id) {
              ?>
                <i class="material-icons">navigate_next</i>
                <span><?php echo $category->name ?></span>
              <?php
            }
            ?>
          </h1>
        </div>

        <div class="row">
          <?php include( locate_template('partials/tuki-sidebar.php') ); ?>

          <div class="col-xs-12 col-md-9 col-sm-7">
            <div class="cards cards--2 cards--image">
              <?php
              while ( have_posts() ) : the_post(); ?>

              <a href="<?php the_permalink(); ?>" class="card">
                <?php
                  if (has_post_thumbnail( $post->ID ) ):
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                  else :
                    $image = array("/assets/images/frontpage.jpg");
                  endif;
                ?>
                <div class="card-image" style="background-image: url(<?php echo $image[0]; ?>);"></div>
                <div class="card-content">
                  <h3 class="card-title"><?php the_title(); ?></h3>
                </div>
              </a>

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
    </div><!-- .page-hero-content -->
  </main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>
