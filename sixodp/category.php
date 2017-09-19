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


$category = get_queried_object();
$grandparent_id = get_category_grandparent_id($category);

get_header();

?>

 <div id="primary" class="content-area">
  <main id="main" class="site-main site-main--news" role="main">
    <?php get_template_part('partials/header-logos'); ?>

    <h1 class="page-title"><?php echo $category->name ?></h1>
    <div class="container">
      <div class="row">
        <div class="sidebar-links col-sm-4">
          <?php
            $categories=get_categories(array(
              'parent' => $grandparent_id,
              'hide_empty' => false,
            ));

            if (count($categories) > 0) {
          ?>
            <?php foreach ( $categories as $cat ) : 
            $child_categories = get_categories(array('parent' => $cat->term_id, 'hide_empty' => false));
            ?>
            <ul>
              <li class="sidebar__item--heading">
                <a href="<?php echo get_category_link($cat); ?>" class="sidebar__link--block">
                  <i class="material-icons">settings</i>
                  <?php echo $cat->cat_name; ?>
                  <span class="sidebar__icon-wrapper">
                    <i class="material-icons">arrow_forward</i>
                  </span>
                </a>
              </li>
              <?php
              foreach ($child_categories as $child_cat) : 
              ?>
              <li class="sidebar__item">
                <a href="<?php echo get_category_link($child_cat); ?>" class="sidebar__link">
                  <?php echo $child_cat->name; ?>  
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endforeach; ?>
          <?php } 
          ?>
        </div>
        <div class="col-md-8 news-content">
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
              <h4 class="post__title">
                <a class="post__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h4>
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
