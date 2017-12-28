<?php
/**
 * Featured content box on homepage.
 */
?>

<div class="wrapper--featured">
  <div class="container">
    <div class="row cards cards--4">
      <?php
      $args = array( 'posts_per_page' => 4 );

      $myposts = get_posts( $args );

      foreach ( $myposts as $post ) {
        setup_postdata( $post );
        if (has_post_thumbnail( $post->ID ) ):
          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
        else :
          $image = array("/assets/images/frontpage.jpg");
        endif; ?>

        <div class="card--image">
          <img src="<?php echo $image[0]; ?>">
          <div class="card-content card-content-slide-up">
            <h4 class="card-title"><?php the_title(); ?></h4>
            <div class="card-title-secondary"><?php echo parse_date(get_the_date('c')); ?></div>
            <div class="card-description"><?php the_excerpt(); ?></div>
            <div class="card-link-wrapper">
              <a href="<?php the_permalink(); ?>"
                 class="btn btn-transparent card-link">
                <?php _e('Read more', 'sixodp') ?>
              </a>
            </div>
          </div>
        </div>
      <?php } wp_reset_postdata();?>
    </div>

    <div class="row btn-container">
      <a type="button" href="<?php echo get_category_link(get_translated_category_by_slug('ajankohtaista')) ?>" class="btn btn-transparent--inverse btn--show-all">
        <?php _e('Show all', 'sixodp');?>
      </a>
    </div>
  </div>
</div>
