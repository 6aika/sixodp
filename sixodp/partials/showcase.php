<?php
  /**
  * Template for a single showcase
  *
  */
?>
<a class="card--image" href="<?php echo $showcaseUrl; ?>">
  <img src="<?php echo $imgUrl; ?>">
  <div class="card-content card-content-slide-up">
    <h4 class="card-title"><?php echo $showcase['title']; ?></h4>
  </div>
</a>
