<?php
  /**
  * Template for a single showcase
  *
  */
?>
<div class="card--image">
  <img src="<?php echo $imgUrl; ?>">
  <div class="card-content card-content-slide-up">
    <h4 class="card-title"><?php echo $showcase['title']; ?></h4>
    <div class="card-title-secondary">
      <?php
        $rating = get_ckan_package_rating($packageId)['rating'];
        $j = $rating['rating'];
        for ($i = 0; $i < 5; $i++) {
          if($i < $rating) {
            echo '<span class="fa fa-star star-filled"></span>';
          }
          else {
            echo '<span class="fa fa-star star-empty"></span>';
          }
        }
      ?>
    </div>
    <div class="card-description">
      <?php echo wp_html_excerpt( strip_shortcodes(render_markdown(get_translated($showcase, 'notes'))), 300, '...'); ?>
    </div>
    <div class="card-link-wrapper">
      <a href="<?php echo $showcaseUrl; ?>"
         class="btn btn-transparent card-link">
        <?php _e('Read more', 'sixodp') ?>
      </a>
    </div>
  </div>
</div>
