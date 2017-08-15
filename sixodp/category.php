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

$category = get_queried_object();
$grandparent_id = get_category_grandparent_id($category->term_id);
$sibling_categories = get_categories(array('parent' => $grandparent_id, 'hide_empty' => false));
if ($grandparent_id != $category->term_id) {
  $child_categories = get_categories(array('parent' => $category->term_id, 'hide_empty' => false));
}
?>

 <div id="primary" class="content-area">
  <main id="main" class="site-main site-main--news" role="main">
    <?php get_template_part('partials/header-logos'); ?>

    <h1 class="page-title"><?php echo $category->name ?></h1>
    <div class="container">
      <div class="row">
        <div class="col-md-4 news-content">
          <div class="filters secondary">
            <div>
              <section class="module module-narrow module-shallow">
                <nav>
                  <ul class="unstyled nav nav-simple nav-facet filtertype-res_format">
                    <?php
                    foreach ($sibling_categories as $sibling_category) {
                      echo '<li class="nav-item news-category"><a href="' . get_category_link($sibling_category->cat_ID) . '" class="news-category__link">'. $sibling_category->name .'<span class="news-category__count">'. $sibling_category->count .'</span></a></li>';
                    }
                    ?>
                  </ul>
                </nav>
                <p class="module-footer"> </p>
              </section>
              <?php
              if (isset($child_categories) && sizeof($child_categories) > 0) {
                ?>
                  <section class="module module-narrow module-shallow">
                    <h2 class="module-heading">
                      <i class="icon-medium icon-filter"></i>
                      <?php _e('Categories', 'sixodp');?>
                    </h2>
                    <nav>
                      <ul class="unstyled nav nav-simple nav-facet filtertype-res_format">
                        <?php
                        foreach ($child_categories as $child_category) {
                          echo '<li class="nav-item news-category"><a href="' . get_category_link($child_category->cat_ID) . '" class="news-category__link">'. $child_category->name .'<span class="news-category__count">'. $child_category->count .'</span></a></li>';
                        }
                        ?>
                      </ul>
                    </nav>
                    <p class="module-footer"> </p>
                  </section>
                <?php
              }
              ?>
            </div>
          </div>
        </div>
        <div class="col-md-8 news-content cards--post">
          <div class="cards--post">
            <?php
            while ( have_posts() ) : the_post(); ?>

            <div class="card--post">
              <?php
                if (has_post_thumbnail( $post->ID ) ):
                  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                else :
                  $image = array("/assets/images/frontpage.jpg");
                endif;
              ?>
              <a href="<?php the_permalink(); ?>" class="post__img--link" style="background-image: url(<?php echo $image[0]; ?>);"></a>
              <div class="post__content">
                <h4 class="post__title">
                  <a class="post__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
                <div class="post__separator"></div>
                <div class="post__meta">
                  <span><?php echo parse_date(get_the_date('c')); ?></span>
                </div>
                <div class="post__categories">
                  <ul>
                    <?php
                      if ( count(get_the_category()) > 0 ) {
                        foreach ( get_the_category() as $cat ) { ?>
                          <li><a href="<?php echo get_category_link($cat->cat_ID) ?>"><?php echo $cat->name; ?></a></li><?php
                        }
                      }
                    ?>
                  </ul>
                </div>
                <div class="post__footer">
                </div>
              </div>
            </div>

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
      </div>
    </div>
  </main><!-- .site-main -->
 </div><!-- .content-area -->
 <?php get_footer(); ?>
