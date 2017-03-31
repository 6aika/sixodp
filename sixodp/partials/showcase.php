<?php
  /**
  * Template for a single showcase
  *
  */
?>
<div class="card--showcase">
  <a href="<?php echo site_url(); ?>" class="showcase__img--link">
    <img class="card__img" src="<?php echo $imgUrl; ?>" alt="showcase">
  </a>
  <div class="showcase__content">
    <h4 class="showcase__title">
      <a class="showcase__link" href="<?php echo site_url(); ?>">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</a>
    </h4>
    <div class="showcase__text">
      <p>Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="showcase__footer">
      <?php
        $rating = get_ckan_package_rating($packageId);
        $j = $rating['rating'];
        while ($j > 0.5) {
          echo '<span class="material-icons brandColor">star</span>';
          $j--;
        }
        if(abs($rating['rating']) - (int)(abs($rating['rating'])) == 0.5) {
          echo '<span class="material-icons brandColor">star_half</span>';
        }
      ?>

      <p><?php echo $rating['ratings_count'] . " arviota"; ?></p>
    </div>
  </div>
</div>
