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

    <?php get_template_part('partials/page-hero'); ?>

    <div class="page-hero-content container">
      <div class="wrapper">

        <div class="headingbar">
          <h1 class="heading-main">
            <?php echo $category->name ?>
          </h1>
        </div>

        <div class="row">
          <div class="sidebar col-md-3 col-sm-5 col-xs-12">
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
                <li class="sidebar-item--highlight">
                  <a href="<?php echo get_category_link($cat); ?>">
                    <?php echo $cat->cat_name; ?>
                    <span class="sidebar-icon-wrapper">
                      <span class="fa fa-chevron-right"></span>
                    </span>
                  </a>
                </li>
                <?php
                foreach ($child_categories as $child_cat) :
                ?>
                <li class="sidebar-item">
                  <a href="<?php echo get_category_link($child_cat); ?>">
                    <?php echo $child_cat->name; ?>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endforeach; ?>
            <?php }
            ?>
          </div>
          <div class="col-md-9 col-sm-7 col-xs-12 news-content">
            <div class="cards cards--2 cards--image">
              <?php while ( have_posts() ) : the_post(); ?>
                <a class="card" href="<?php the_permalink(); ?>">
                  <?php
                    if (has_post_thumbnail( $post->ID ) ):
                      $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                    else :
                      $image = array("/assets/images/frontpage.jpg");
                    endif;
                  ?>
                  <div class="card-image" style="background-image: url(<?php echo $image[0]; ?>);"></div>
                  <div class="card-content">
                    <h4 class="card-title text-left"><?php the_title(); ?></h4>
                    <span class="card-timestamp"><?php echo parse_date(get_the_date('c')); ?></span>
                  </div>
                </a>
              <?php endwhile; ?>
            </div>
            <?php
              the_posts_pagination( array(
                'prev_text'          => '<span class="fa fa-chevron-left" title="' . __( 'Previous page', 'sixodp' ) . '"></span>',
                'next_text'          => '<span class="fa fa-chevron-right" title="' . __( 'Next page', 'sixodp' ) . '"></span>',
                'mid_size'  => 2
              ) );
            ?>
          </div>
        </div>
      </div>
    </div>
  </main><!-- .site-main -->
 </div><!-- .content-area -->
 <?php get_footer(); ?>
