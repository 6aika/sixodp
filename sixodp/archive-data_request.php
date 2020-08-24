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
  <main id="main" class="site-main" role="main">

    <?php get_template_part('partials/page-hero'); ?>

    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li><a href="<?php echo home_url( $wp->request ) ?>"><?php _e('Data Requests', 'sixodp') ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php _e('Data Requests', 'sixodp') ?></h1>
      </div>
    </div>

    <div id="maincontent" class="page-content container">
      <div class="wrapper--archive">

        <div class="row">
          <?php $data_req_url = get_permalink(get_translated_page_by_title('Uusi datatoive')); ?>
          <div class="news-content">
            <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12 btn-container-inverse">
              <a href="<?php echo $data_req_url; ?>" class="btn btn-transparent--inverse">
                <?php _e('New data request', 'sixodp') ?>
              </a>
            </div>
            
            <div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12">
              <div class="cards cards--3">
                <?php
                  // Start the loop.
                  while ( have_posts() ) : the_post();
                    $item = array(
                      'image_url' => get_post_thumbnail_url($post),
                      'title' => $post->post_title,
                      'show_rating' => false,
                      'timestamp' => $post->post_date,
                      'notes' => $post->post_content,
                      'url' => get_the_permalink(),
                      'meta' => __('Data request', 'sixodp'),
                      'external_card_class' => 'card-danger',
                    );
                    include(locate_template( 'partials/card.php' ));
                  endwhile;
                ?>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
      <?php
      the_posts_pagination( array(
          'prev_text'          => '<span class="fa fa-chevron-left" title="' . __( 'Previous page', 'sixodp' ) . '"></span>',
          'next_text'          => '<span class="fa fa-chevron-right" title="' . __( 'Next page', 'sixodp' ) . '"></span>',
          'mid_size'  => 2
      ) );
      ?>
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
