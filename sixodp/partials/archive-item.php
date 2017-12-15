<?php
  /**
  * Archive item
  */
?>
<a class="card" href="<?php the_permalink(); ?>">
  <?php
    if (has_post_thumbnail( $post->ID ) ):
      $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
    else :
      $image = array("/assets/images/frontpage.jpg");
    endif;
  ?>
  <img src="<?php echo $image[0]; ?>">
  <div class="card-content">
    <h4 class="card-title text-left"><?php the_title(); ?></h4>
    <span class="card-timestamp"><?php echo parse_date(get_the_date('c')); ?></span>
  </div>
</a>
