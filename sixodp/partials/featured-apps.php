<?php
  /**
  * Featured applications on homepage.
  */
?>

<div class="wrapper--featured">
  <div class="container container--heading">
    <h2 class="heading--featured"><?php _e('Latest applications', 'sixodp');?> </h2>
  </div>

  <div class="container">
    <?php $showcases = get_latest_showcases_from_cache(); ?>

    <div id="featured-apps-carousel" class="carousel slide mobile-only" data-ride="carousel" data-interval="false">
      <div class="carousel-inner" role="listbox">
        <?php foreach($showcases as $showcase) : ?>
          <?php $extra_classes = $showcase === reset($showcases) ? ' active' : ''; ?>

          <div class="carousel-item<?php echo $extra_classes ?>">
            <?php
            $lang = get_current_locale_ckan();
            $item = array(
              'external_card_class' => 'card-success',
              'image_url' => CKAN_BASE_URL . "/uploads/showcase/".$showcase['featured_image'],
              'title' => get_translated($showcase, 'title'),
              'show_rating' => false,
              'date_updated' => $showcase['metadata_created'],
              'notes' => get_translated($showcase, 'notes'),
              'url' => CKAN_BASE_URL . '/' . $lang . "/showcase/" . $showcase['name'],
              'package_id' => $showcase['id']
            );
            include(locate_template( 'partials/card-image.php' ));
            ?>
          </div>
        <?php endforeach; ?>
      </div>

      <button class="carousel-control-prev" href="#featured-apps-carousel" role="button" data-bs-slide="prev">
        <span class="fa fa-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </button>
      <a class="carousel-control-next" href="#featured-apps-carousel" role="button" data-bs-slide="next">
        <span class="fa fa-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

      <div class="carousel-indicators">
        <?php foreach($showcases as $index => $showcase) : ?>
          <?php $extra_classes = $showcase === reset($showcases) ? 'active' : ''; ?>
          <button data-bs-target="#featured-apps-carousel" data-bs-slide-to="<?php echo $index ?>" class="<?php echo $extra_classes ?>"></button>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="row cards cards--4 desktop-only">
      <?php
        foreach ($showcases as $showcase) {
          $item = array(
            'external_card_class' => 'card-success',
            'image_url' => CKAN_BASE_URL . "/uploads/showcase/".$showcase['featured_image'],
            'title' => get_translated($showcase, 'title'),
            'show_rating' => false,
            'date_updated' => $showcase['metadata_created'],
            'notes' => get_translated($showcase, 'notes'),
            'url' => CKAN_BASE_URL . '/' . $lang . "/showcase/" . $showcase['name'],
            'package_id' => $showcase['id']
          );
          include(locate_template( 'partials/card-image.php' ));
        }
      ?>
    </div>
  </div>

  <div class="container">
    <div class="btn-container">
      <div class="heading--small"><?php _e('Do you have a dataset or an application to share?', 'sixodp');?> <?php _e('Share it!', 'sixodp');?></div>
    </div>
    <div class="row btn-container justify-content-center">
      <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan() ?>/submit-showcase" class="btn btn-app btn--banner"><?php _e('Submit an application', 'sixodp');?></a>
      <a href="<?php echo get_permalink(get_translated_page_by_title('Uusi sovellusidea')); ?>" class="btn btn-app--inverse btn--banner"><?php _e('Share an application', 'sixodp');?></a>
    </div>
  </div>
</div>
