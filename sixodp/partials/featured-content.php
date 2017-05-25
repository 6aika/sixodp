<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--featured">

  <div class="container">
    <h1 class="heading--featured"><?php _e('Datasets and applications');?> </h1>
  </div>

  <div class="container banner">
    <div class="row text-center">
      <div class="col-md-6">
        <h2 class="banner__title"><?php _e('Do you have a dataset or an application to share?');?> </h2>
        <h4 class="banner__subtitle"><?php _e('Share it!');?> </h4>
        <div class="banner__buttons">
          <a class="btn btn-transparent btn--banner-jaa"><?php _e('Submit an application');?>  <i class="material-icons">arrow_forward</i></a>
          <a href="/data/submit-data" class="btn btn-transparent btn--banner-jaa"><?php _e('Submit a dataset');?>  <i class="material-icons">arrow_forward</i></a>
        </div>
      </div>
      <div class="col-md-6">
        <h2 class="banner__title"><?php _e('Do you have a request for a dataset or an idea for application?');?> </h2>
        <h4 class="banner__subtitle"><?php _e('Tell us!');?> </h4>
        <div class="banner__buttons">
          <a class="btn btn-transparent btn--banner-jaa"><?php _e('Share an open data idea');?>  <i class="material-icons">arrow_forward</i></a>
          <a class="btn btn-transparent btn--banner-jaa"><?php _e('Share an application');?>  <i class="material-icons">arrow_forward</i></a>
        </div>
      </div>
    </div>
  </div>

  <div class="container container--heading">
    <h3 class="heading--featured-small"><?php _e('Latest applications');?> </h3>
  </div>

  <div class="container">
    <div class="row cards--showcase">
      <?php
        $showcases = get_recent_showcases(4);
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
    <div class="row featured-btn-container">
      <a href="<?php echo CKAN_BASE_URL; ?>/showcase" class="btn btn-lg btn-secondary btn--sovellukset">
        <?php _e('All applications');?>  <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

  <div class="container container--heading">
    <h3 class="heading--featured-small"><?php _e('Latest datasets');?> </h3>
  </div>

  <div class="container">
    <div class="row cards">
      <?php foreach ( get_recent_datasets() as $dataset ) : ?>
        <div class="card">
          <h3 class="card__title">
            <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale().'/dataset/'.$dataset['name']; ?>">
              <?php echo $dataset["title_translated"][get_current_locale()]; ?>
            </a>
          </h3>
          <div class="card__meta">
            <span class="card__timestamp"><?php echo parse_date($dataset['metadata_created']); ?></span>
            <span style="margin-left: 2px; margin-right: 2px;">&bull;</span>
            <a href="<?php echo CKAN_BASE_URL; ?>/<?php echo get_current_locale(); ?>/<?php echo $dataset['type']; ?>" class="card__categorylink"><?php echo $dataset['type']; ?></a>
          </div>
          <div class="card__body">
            <p><?php echo get_notes_excerpt(get_translated($dataset, 'notes')); ?></p>
          </div>
        </div><?php
      endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="row featured-btn-container">
      <a href="<?php echo CKAN_BASE_URL; ?>/<?php echo get_current_locale(); ?>/dataset" class="btn btn-lg btn-secondary btn--sovellukset">
        <?php _e('All datasets');?>  <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

</div>
