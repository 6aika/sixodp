<?php
/**
 * Featured content box on homepage.
 */
?>

<div class="horizontal-accordion-wrapper" id="horizontal-accordion">
  <div class="horizontal-accordion-container">
    <div class="horizontal-accordion-btn-group"></div>
    <ul class="horizontal-accordion horizontal-accordion-static">
      <?php
      $args = array( 'posts_per_page' => 4 );

      $myposts = get_posts( $args );
      foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
        <?php
        if (has_post_thumbnail( $post->ID ) ):
          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
        else :
          $image = array("/assets/images/frontpage.jpg");
        endif;
        ?>
        <li class="horizontal-accordion-tab" style="background-image: url(<?php echo $image[0]; ?>);">
          <a class="tab-overlay" href="<?php the_permalink(); ?>">
            <div class="tab-content">
              <h1><?php the_title(); ?></h1>
              <h2 class="tab-meta">
                <span><?php echo parse_date(get_the_date('c')); ?></span>
                <?php
                if ( count(get_the_category()) > 0 ) {
                  foreach ( get_the_category() as $cat ) { ?>
                    | <span href="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></span><?php
                  }
                }
                ?>
              </h2>
              <p><?php the_excerpt(); ?></p>
            </div>
          </a>
        </li>
      <?php endforeach;
      wp_reset_postdata();?>
    </ul>

    <div class="horizontal-accordion-btn-group">
      <div class="container">
        <a type="button" href="<?php echo get_category_link(get_translated_category_by_slug('ajankohtaista')) ?>" class="btn btn-secondary btn--show-all">
          <?php _e('Show all', 'sixodp');?>  <i class="material-icons">arrow_forward</i>
        </a>
        <a type="button" href="<?php echo get_permalink(get_translated_page_by_title('Ajankohtaista')); ?>" class="btn btn-secondary btn--ajankohtaista">
          <?php _e('Latest updates', 'sixodp');?>  <i class="material-icons">arrow_forward</i>
        </a>
      </div>
    </div>
  </div>
</div>
