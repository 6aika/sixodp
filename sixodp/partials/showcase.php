<?php
  /**
  * Template for a single showcase
  *
  */
?>
<div class="card card--showcase">
  <a href="<?php echo $showcaseUrl; ?>" class="showcase__img--link" style="background-image: url(<?php echo $imgUrl; ?>);"></a>
  <div class="showcase__content">
    <h4 class="showcase__title">
      <a class="showcase__link" href="<?php echo $showcaseUrl; ?>"><?php echo $showcase['title']; ?></a>
    </h4>
  </div>
</div>
