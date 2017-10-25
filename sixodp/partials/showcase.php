<?php
  /**
  * Template for a single showcase
  *
  */
?>
<a class="card" href="<?php echo $showcaseUrl; ?>">
  <div class="card-image" style="background-image: url(<?php echo $imgUrl; ?>);"></div>
  <div class="card-content">
    <h4 class="card-title"><?php echo $showcase['title']; ?></h4>
  </div>
</a>
