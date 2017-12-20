<?php
  /**
  * Featured applications on homepage.
  */
?>

<div class="wrapper wrapper--featured">
  <div class="container container--heading">
    <h2 class="heading--featured"><?php _e('Latest applications', 'sixodp');?> </h2>
  </div>

  <div class="container">
    <div class="row cards cards--4 cards--image cards--image--dark">
      <?php
        $showcases = get_latest_showcases(4);
        foreach ($showcases as $showcase) {
          $showcaseUrl = CKAN_BASE_URL . "/showcase/" . $showcase['name'];
          $imgUrl = CKAN_BASE_URL . "/uploads/showcase/".$showcase['featured_image'];
          $packageId = $showcase['id'];
          $notes = get_translated($showcase, 'notes');
          include(locate_template( 'partials/showcase.php' ));
        }
      ?>
    </div>
  </div>

  <div class="container">
    <div class="row btn-container">
      <a href="<?php echo CKAN_BASE_URL; ?>/showcase" class="btn btn-transparent--inverse btn--sovellukset">
        <?php _e('All applications', 'sixodp');?>
      </a>
    </div>
  </div>
</div>
