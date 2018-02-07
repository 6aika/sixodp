<?php
  /**
  * More links template
  */

?>


<div class="headingbar">
  <h1 class="heading-main">
    <a href="<?php echo get_the_permalink($parent_page); ?>"><?php echo $parent_page->post_title; ?></a>

    <?php
    if ($parent_page->ID !== get_the_id()) {
      ?>
      <i class="material-icons">navigate_next</i>
      <span>
        <?php the_title(); ?>
      </span>
      <?php
    }
    ?>
  </h1>
</div>
