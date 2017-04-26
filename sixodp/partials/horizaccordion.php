<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--horizaccordion">
  <div class="wrapper--horizaccordion-inner">
    <div class="container">
      <div class="row">
        <div class="horizaccordion">
          <?php
            $args = array( 'posts_per_page' => 4 );

            $myposts = get_posts( $args );
            foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
              <div class="horizaccordion__container">
                <?php
                  if (has_post_thumbnail( $post->ID ) ):
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                  else :
                    $image = array("/assets/images/frontpage.jpg");
                  endif;
                ?>
                <div class="horizaccordion__item" style="background-image: url(<?php echo $image[0]; ?>);">
                  <h4 class="horizaccordion__title">
                    <a class="horizaccordion__link" href="<?php the_permalink(); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h4>
                  <div class="horizaccordion__meta">
                    <span><?php echo parse_date(get_the_date()); ?></span>
                      <?php 
                        if ( count(get_the_category()) > 0 ) {
                          foreach ( get_the_category() as $cat ) { ?>
                            | <a href="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></a><?php
                          }
                        }
                      ?>
                  </div>
                  <div class="horizaccordion__text">
                    <?php the_excerpt(); ?>
                  </div>
                  <div class="horizaccordion__footer">
                    <a type="button" href="<?php the_permalink(); ?>" class="btn btn-secondary">Lue lisää</a>
                  </div>
                </div>
              </div>
            <?php endforeach; 
            wp_reset_postdata();?>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row horizaccordion__btn-container">
      <a type="button" href="/ajankohtaista" class="btn btn-lg btn-secondary btn--ajankohtaista">
        Ajankohtaista <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>
</div>
