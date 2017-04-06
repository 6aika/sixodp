<?php
  /**
  * Archive item
  */
?>
<div class="row" style="border-bottom: 1px solid #e7e7e7; padding: 40px 0;">
  <div class="col-md-12">
    <span class="meta" style="font-size: 12px;"><?php the_date(); ?></span>
    <h3 class="heading" style="margin-top: 10px;">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <div class="excerpt">
      <?php the_excerpt(); ?>
    </div>
  </div>
</div>