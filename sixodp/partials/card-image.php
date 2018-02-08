<div class="card--image<?php if (isset($item['external_card_class'])) echo ' ' . $item['external_card_class'] ?>">
  <img src="<?php echo $item['image_url']; ?>">
  <div class="card-content card-content-slide-up">
    <h4 class="card-title"><?php echo $item['title']; ?></h4>
    <div class="card-title-secondary">
      <?php
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
        else {
          echo parse_date($item['date_updated']);
        }
      ?>
    </div>
    <div class="card-description">
      <?php echo wp_html_excerpt( strip_shortcodes(render_markdown(get_translated($item, 'notes'))), 300, '...'); ?>
    </div>
    <div class="card-link-wrapper">
      <a href="<?php echo $item['url']; ?>"
         class="btn btn-transparent card-link">
        <?php _e('Read more', 'sixodp') ?>
      </a>
    </div>
  </div>
</div>