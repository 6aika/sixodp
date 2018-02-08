<?php
  /**
  * Morelinks template
  */
?>

<h1 class="heading--themes"><?php echo $morelinks_title; ?></h1>
<div class="wrapper--morelinks">
  <?php foreach ($links as $link) : ?>
    <div class="icon-link morelink">
      <p class="icon-link__icon">
        <a class="icon-link__link--round" href="<?php echo $link->post_name; ?>">
          <i class="material-icons">arrow_forward</i>
        </a>
      </p>
      <p class="icon-link__text">
        <a href="<?php echo get_permalink($link) ?>"><?php echo $link->post_title; ?></a>
      </p>
    </div>
  <?php endforeach; ?>
</div>