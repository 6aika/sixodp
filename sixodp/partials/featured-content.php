<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--featured">

  <div class="container banner">
    <div class="row text-center">
      <div class="col-md-6">
        <h3 class="banner__title"><?php _e('Do you have a dataset or an application to share?', 'sixodp');?> </h3>
        <h4 class="banner__subtitle"><?php _e('Share it!', 'sixodp');?> </h4>
        <div class="banner__buttons">
          <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan() ?>/submit-showcase" class="btn btn-transparent btn--banner-jaa"><?php _e('Submit an application', 'sixodp');?>  <i class="material-icons">arrow_forward</i></a>
          <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan() ?>/submit-data" class="btn btn-transparent btn--banner-jaa"><?php _e('Submit a dataset', 'sixodp');?>  <i class="material-icons">arrow_forward</i></a>
        </div>
      </div>
      <div class="col-md-6">
        <h3 class="banner__title"><?php _e('Do you have a request for a dataset or an idea for application?', 'sixodp');?> </h3>
        <h4 class="banner__subtitle"><?php _e('Tell us!', 'sixodp');?> </h4>
        <div class="banner__buttons">
          <a href="<?php echo get_permalink(get_translated_page_by_title('Uusi datatoive')); ?>" class="btn btn-transparent btn--banner-jaa"><?php _e('Share an open data idea', 'sixodp');?>  <i class="material-icons">arrow_forward</i></a>
          <a href="<?php echo get_permalink(get_translated_page_by_title('Uusi sovellusidea')); ?>" class="btn btn-transparent btn--banner-jaa"><?php _e('Share an application', 'sixodp');?>  <i class="material-icons">arrow_forward</i></a>
        </div>
      </div>
    </div>
  </div>

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
    <div class="row featured-btn-container">
      <a href="<?php echo CKAN_BASE_URL; ?>/showcase" class="btn btn-secondary btn--sovellukset">
        <?php _e('All applications', 'sixodp');?>  <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

  <div class="container container--heading">
    <h2 class="heading--featured"><?php _e('Latest datasets', 'sixodp');?> </h2>
  </div>

  <div class="container">
    <div class="row cards cards--4">
      <?php foreach ( get_latest_datasets() as $index => $dataset ) : ?>
        <?php if ($index % 4 === 0) echo '</div><div class="row cards cards--4">'; ?>
        <?php
          $item = array(
            'external_card_class' => 'card-danger',
            'title' => get_translated($dataset, 'title'),
            'meta' => __('Dataset', 'sixodp'),
            'timestamp' => $dataset['date_released'],
            'notes' => get_translated($dataset, 'notes'),
            'url' => CKAN_BASE_URL.'/'.get_current_locale_ckan().'/dataset/'.$dataset['name'],
          );
          include(locate_template( 'partials/card.php' ));
        ?>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="row featured-btn-container">
      <a href="<?php echo CKAN_BASE_URL; ?>/<?php echo get_current_locale_ckan(); ?>/dataset" class="btn btn-secondary btn--sovellukset">
        <?php _e('All datasets', 'sixodp');?>  <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

</div>
