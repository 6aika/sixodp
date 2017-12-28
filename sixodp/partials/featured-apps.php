<?php
  /**
  * Featured applications on homepage.
  */
?>

<div class="wrapper--featured">
  <!--
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
  -->

  <div class="container container--heading">
    <h2 class="heading--featured"><?php _e('Latest applications', 'sixodp');?> </h2>
  </div>

  <div class="container">
    <div class="row cards cards--4">
      <?php
        $showcases = get_latest_showcases(4);
        foreach ($showcases as $showcase) {
          $item = array(
            'external_card_class' => 'card-success',
            'image_url' => CKAN_BASE_URL . "/uploads/showcase/".$showcase['featured_image'],
            'title' => get_translated($showcase, 'title'),
            'show_rating' => true,
            'date_updated' => $showcase['date_updated'],
            'notes' => get_translated($showcase, 'notes'),
            'url' => CKAN_BASE_URL . "/showcase/" . $showcase['name'],
            'package_id' => $showcase['id']
          );
          include(locate_template( 'partials/card-image.php' ));
        }
      ?>
    </div>
  </div>

  <div class="container">
    <div class="btn-container">
      <h4 class="heading--small"><?php _e('Do you have a dataset or an application to share?', 'sixodp');?> <?php _e('Share it!', 'sixodp');?></h4>
    </div>
    <div class="row btn-container">
      <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan() ?>/submit-showcase" class="btn btn-app btn--banner-jaa"><?php _e('Submit an application', 'sixodp');?></a>
      <a href="<?php echo get_permalink(get_translated_page_by_title('Uusi sovellusidea')); ?>" class="btn btn-app--inverse btn--banner-jaa"><?php _e('Share an application', 'sixodp');?></a>
    </div>
  </div>
</div>
