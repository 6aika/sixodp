
<div class="card--image<?php if (isset($item['external_card_class'])) echo ' ' . $item['external_card_class'] ?>">
  <a href="<?php echo $item['url']; ?>" class="card-link" aria-label="<?php echo $item['title'] ?>">
  <img src="<?php echo $item['image_url']; ?>" alt="">
  <div class="card-content card-content-slide-up">
    <h3 class="card-title"><?php echo $item['title']; ?></h3>
    <div class="card-title-secondary">
      <?php
        /*
        if ($item['show_rating']) {
          $rating = get_ckan_package_rating($item['package_id'])['rating'];
          $j = $rating['rating'];
          for ($i = 0; $i < 5; $i++) {
            if($i < $rating) {
              echo '<span class="fa fa-star rating-star-filled"></span>';
            }
            else {
              echo '<span class="fa fa-star rating-star-empty"></span>';
            }
          }
        }
        else { */
          echo parse_date($item['date_updated']);
        //}
      ?>
    </div>
  </div>
  </a>
</div>
