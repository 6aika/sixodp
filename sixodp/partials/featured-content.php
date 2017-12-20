<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper wrapper--featured">
  <!--
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
  -->

  <div class="container container--heading">
    <h2 class="heading--featured"><?php _e('Latest datasets', 'sixodp');?> </h2>
  </div>

  <div class="container">
    <div class="row cards">
      <?php foreach ( get_latest_datasets() as $index => $dataset ) : ?>
        <?php if ($index % 3 === 0) echo '</div><div class="row cards">'; ?>
        <div class="card card-hover" onclick="window.location.href='<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan().'/dataset/'.$dataset['name']; ?>'">
          <h3 class="card-title">
            <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan().'/dataset/'.$dataset['name']; ?>">
              <?php echo get_translated($dataset, 'title'); ?>
            </a>
          </h3>

          <p>
            <span class="card-timestamp"><?php echo parse_date($dataset['date_released']); ?></span><br />
            <?php echo wp_html_excerpt( strip_shortcodes(render_markdown(get_translated($dataset, 'notes'))), 240, '...'); ?>
          </p>

          <div class="card-meta">
            <span>
              <span class="fa fa-database"></span>&nbsp;<?php echo _e('Dataset', 'sixodp'); ?>
            </span>
          </div>
        </div><?php
      endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="row btn-container">
      <a href="<?php echo CKAN_BASE_URL; ?>/<?php echo get_current_locale_ckan(); ?>/dataset" class="btn btn-transparent--inverse btn--sovellukset">
        <?php _e('All datasets', 'sixodp');?>
      </a>
    </div>
  </div>

</div>
