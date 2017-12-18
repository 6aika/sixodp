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
    <div class="card-title-secondary">
      <?php
        $rating = get_ckan_package_rating($packageId)['rating'];
        $j = $rating['rating'];
        for ($i = 0; $i < 5; $i++) {
          if($i < $rating) {
            echo '<span class="fa fa-star"></span>';
          }
          else {
            echo '<span class="fa fa-star-o"></span>';
          }
        }
      ?>
    </div>
  </div>
</a>
