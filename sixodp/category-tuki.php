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
  <main id="main" class="site-main" role="main">
    <div class="wrapper">
      <div class="container">

        <div class="headingbar">
          <h1 class="heading--main">
            <a href="<?php echo get_category_link($parent_category) ?>"><?php echo $parent_category->name ?></a>
            <?php if ($parent_category->term_id != $category->term_id) {
              ?>
                <i class="material-icons">navigate_next</i>
                <span style="font-size: 2.2rem;">
                  <?php echo $category->name ?>
                </span>
              <?php
            }
            ?>
          </h1>
        </div>

        <div class="row">
          <?php include( locate_template('partials/tuki-sidebar.php') ); ?>

          <div class="col-xs-12 col-sm-8 cards--post">
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
                  <div class="post__text">
                    <?php 
                    $content = get_the_content();

                    $content = wp_strip_all_tags($content);
                    if (strlen($content) > 200) {
                      $content = substr($content, 0, 180);
                      $content = substr($content, 0, strripos($content, ' '));
                      $content .= '...';
                    }

                    print $content;
                    ?>
                  </div>
                  <div class="post__footer">
                  </div>
                </div>
              </div>
            <?php
            endwhile;
            wp_reset_postdata();
            posts_nav_link();
            ?>
          </div>
        </div><!-- .row -->
      </div><!-- .container -->
    </div><!-- .wrapper -->
  </main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>
