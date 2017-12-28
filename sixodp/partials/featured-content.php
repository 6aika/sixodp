<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--featured">
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
    <div class="btn-container">
      <h4 class="heading--small"><?php _e('Do you have a request for a dataset or an idea for application?', 'sixodp');?> <?php _e('Tell us!', 'sixodp');?></h4>
    </div>
    <div class="row btn-container">
      <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan() ?>/submit-data" class="btn btn-dataset btn--banner-jaa"><?php _e('Submit a dataset', 'sixodp');?></a>
      <a href="<?php echo get_permalink(get_translated_page_by_title('Uusi datatoive')); ?>" class="btn btn-dataset--inverse btn--banner-jaa"><?php _e('Share an open data idea', 'sixodp');?></a>
    </div>
  </div>

</div>
