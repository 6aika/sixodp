<?php
  /**
  * Archive item
  */
?>
<div class="row archive-item">
  <div class="col-md-12">
    <h3 class="archive-item__title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <div class="archive-item__excerpt">
      <?php the_excerpt(); ?>
    </div>
    </a>
    <span class="archive-item__meta">
      <span class="archive-item__date"><?php the_date(); ?></span> 
      <span class="archive-item__comments"><a href="<?php the_permalink(); ?>#comments" title="<?php
      $comments = get_disqus_comment_count(get_the_id());

      if ($comments === 0) _e('0 comments');
      else if ($comments === 1) _e('1 comment');
      else printf( esc_html__( '%d comments' ), $comments );
      ?>"><?php echo $comments ?></a></span> 
      <span class="archive-item__readmore">
        <a href="<?php the_permalink()?>"><?php _e('Read more') ?> <i class="material-icons">arrow_forward</i></a>
      </span>
    </span>
  </div>
</div>