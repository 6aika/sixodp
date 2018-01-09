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
$grandparent_cat = get_category($grandparent_id);

get_header();

?>

 <div id="primary" class="content-area">
  <main id="main" class="site-main site-main--news" role="main">

    <?php get_template_part('partials/page-hero'); ?>
    <div class="toolbar">
      <div class="container">
        <ol class="breadcrumb">
          <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
          <?php if ($grandparent_cat != $category) : ?>
            <li><a href="<?php echo get_category_link($grandparent_id) ?>"><?php echo $grandparent_cat->name ?></a></li>
          <?php endif;?>
          <li><a href="<?php echo get_category_link($category) ?>"><?php echo $category->name ?></a></li>
        </ol>
      </div>
    </div>
    <div class="page-content container">
      <div class="wrapper">
        <div class="row">
          <div class="sidebar col-md-3 col-sm-12 col-xs-12">
            <h3 class="heading-sidebar"><?php _e('Ajankohtaista', 'sixodp') ?></h3>
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
                <li class="sidebar-item<?php if ($cat->cat_name === $category->name) { echo '--highlight'; } ?>">
                  <a href="<?php echo get_category_link($cat); ?>">
                    <span class="sidebar-icon-wrapper">
                      <span class="fa fa-long-arrow-right"></span>
                    </span>
                    <?php echo $cat->cat_name; ?>
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
              ?>
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
