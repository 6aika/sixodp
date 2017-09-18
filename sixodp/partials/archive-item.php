<?php
  /**
  * Archive item
  */
?>
<div class="row archive-item">
  <div class="col-md-12">
    <span class="archive-item__meta"><?php the_date(); ?></span>
    <h3 class="archive-item__title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <a href="<?php the_permalink(); ?>#comments">
      <?php 
      $comments = get_disqus_comment_count(get_the_id());
      
      if ($comments === 0) _e('0 comments');
      else if ($comments === 1) _e('1 comment');
      else printf( esc_html__( '%d comments' ), $comments );
      ?>

    </a>
    <div class="archive-item__excerpt">
      <?php the_excerpt(); ?>
    </div>
  </div>
</div>