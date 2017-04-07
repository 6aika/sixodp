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
    <div class="archive-item__excerpt">
      <?php the_excerpt(); ?>
    </div>
  </div>
</div>