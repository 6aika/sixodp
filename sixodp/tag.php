<?php
/**
 * The template for displaying content by a selected tag
 *
 * @package WordPress
 * @subpackage Sixodp
 */

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main wrapper" role="main">

    <?php get_template_part('partials/page-hero'); ?>

    <div class="page-content container">

      <div class="headingbar">
        <h1 class="heading-main">
          <?php echo _e('Content with tag', 'sixodp') . ' "' . single_tag_title("", false) . '"'; ?>
        </h1>
      </div>

      <div class="row">
        <div class="sidebar col-md-3 col-sm-5 col-xs-12">
          <?php
            $tags = get_tags(
              array(
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 10
              )
            );
          ?>
          <?php foreach ( $tags as $tag ); ?>
          <ul>
            <li class="sidebar-item--highlight">
              <span class="sidebar-item-inner">
                <i class="material-icons">label</i> <?php _e('Popular tags', 'sixodp') ?>
              </span>
            </li>
            <?php
            foreach ($tags as $tag):
              ?>
              <li class="sidebar-item">
                <a href="<?php echo get_tag_link($tag); ?>">
                  <?php echo $tag->name; ?>
                  <span class="sidebar-icon-wrapper">
                      <span class="fa fa-chevron-right"></span>
                    </span>
                </a>
              </li>
              <?php
            endforeach;
            ?>
          </ul>
        </div>

        <div class="col-md-9 col-sm-12 col-xs-12 news-content">
          <div class="cards cards--3">
            <?php
            // Start the loop.
            while ( have_posts() ) : the_post();
              $item = array(
                'image_url' => get_post_thumbnail_url($post),
                'title' => $post->post_title,
                'show_rating' => false,
                'date_updated' => $post->post_date,
                'notes' => $post->post_content,
                'url' => get_the_permalink(),
              );
              include(locate_template( 'partials/card-image.php' ));
            endwhile;
            ?>
          </div>
        </div>
      </div>

    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
