<?php
/**
 * Featured content box on homepage.
 */
?>

<div class="wrapper--featured">
  <div class="container">
    <?php
      $args = array( 'posts_per_page' => 4 );
      $posts = get_posts( $args );
    ?>

    <?php if (isset($horizontal_accordion_heading)): ?>
      <h1 class="heading-page"><?php echo $horizontal_accordion_heading ?></h1>
    <?php endif; ?>

    <div id="featured-content-carousel" class="carousel slide mobile-only" data-ride="carousel" data-interval="false">
      <div class="carousel-inner" role="listbox">
        <?php foreach($posts as $post) :
          if (has_post_thumbnail( $post->ID ) ):
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
          else :
            $image = array("/assets/images/frontpage.jpg");
          endif;

          $extra_classes = $post === reset($posts) ? ' active' : '';
        ?>

          <div class="item<?php echo $extra_classes ?>">
            <?php
              $item = array(
                'image_url' => $image[0],
                'title' => $post->post_title,
                'date_updated' => $post->post_date,
                'notes' => $post->post_content,
                'url' => get_the_permalink(),
              );
              include(locate_template( 'partials/card-image.php' ));
            ?>
          </div>
        <?php endforeach; ?>
      </div>

      <a class="left carousel-control" href="#featured-content-carousel" role="button" data-slide="prev">
        <span class="fa fa-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#featured-content-carousel" role="button" data-slide="next">
        <span class="fa fa-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

      <ol class="carousel-indicators">
        <?php foreach($posts as $index => $post) : ?>
          <?php $extra_classes = $post === reset($posts) ? 'active' : ''; ?>
          <li data-target="#featured-content-carousel" data-slide-to="<?php echo $index ?>" class="<?php echo $extra_classes ?>"></li>
        <?php endforeach; ?>
      </ol>
    </div>

    <div class="row cards cards--4 desktop-only">
      <?php
        foreach ( $posts as $post ) {
          setup_postdata($post);
          if (has_post_thumbnail($post->ID)):
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
          else :
            $image = array("/assets/images/frontpage.jpg");
          endif;

          $item = array(
            'image_url' => $image[0],
            'title' => $post->post_title,
            'date_updated' => $post->post_date,
            'notes' => $post->post_content,
            'url' => get_the_permalink(),
          );
          include(locate_template('partials/card-image.php'));
        }
        wp_reset_postdata();
      ?>
    </div>

    <div class="row btn-container">
      <a type="button" href="<?php echo get_category_link(get_translated_category_by_slug('ajankohtaista')) ?>" class="btn btn-transparent--inverse btn--show-all">
        <?php _e('Show all', 'sixodp');?>
      </a>
    </div>
  </div>
</div>
