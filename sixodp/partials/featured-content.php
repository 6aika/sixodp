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
    <?php $datasets = get_latest_updates(array('datasets' => true, 'showcases' => false, 'comments' => false, 'posts' => false, 'pages' => false), false, 4); ?>

    <div id="featured-datasets-carousel" class="carousel slide mobile-only" data-ride="carousel" data-interval="false">
      <div class="carousel-inner" role="listbox">
        <?php foreach($datasets as $dataset) : ?>
          <?php $extra_classes = $dataset === reset($datasets) ? ' active' : ''; ?>

          <div class="item<?php echo $extra_classes ?>">
            <?php
            $item = array(
              'external_card_class' => 'card-danger',
              'title' => get_translated($dataset, 'title'),
              'meta' => __('Dataset', 'sixodp'),
              'timestamp' => $dataset['date_recent'],
              'notes' => get_translated($dataset, 'notes'),
              'url' => CKAN_BASE_URL.'/'.get_current_locale_ckan().'/dataset/'.$dataset['name'],
            );
            include(locate_template( 'partials/card.php' ));
            ?>
          </div>
        <?php endforeach; ?>
      </div>

      <a class="left carousel-control" href="#featured-datasets-carousel" role="button" data-slide="prev">
        <span class="fa fa-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#featured-datasets-carousel" role="button" data-slide="next">
        <span class="fa fa-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

      <ol class="carousel-indicators">
        <?php foreach($datasets as $index => $dataset) : ?>
          <?php $extra_classes = $dataset === reset($datasets) ? 'active' : ''; ?>
          <li data-target="#featured-datasets-carousel" data-slide-to="<?php echo $index ?>" class="<?php echo $extra_classes ?>"></li>
        <?php endforeach; ?>
      </ol>
    </div>

    <div class="row cards cards--4 desktop-only">
      <?php foreach ( $datasets as $index => $dataset ) : ?>
        <?php if ($index % 4 === 0 and $index !== 0) echo '</div><div class="row cards cards--4 desktop-only">'; ?>
        <?php
          $item = array(
            'external_card_class' => 'card-danger',
            'title' => get_translated($dataset, 'title'),
            'meta' => __('Dataset', 'sixodp'),
            'timestamp' => $dataset['date_recent'],
            'notes' => get_translated($dataset, 'notes'),
            'url' => $dataset['link'],
          );
          include(locate_template( 'partials/card.php' ));
        ?>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="btn-container">
      <h3 class="heading--small"><?php _e('Do you have a request for a dataset or an idea for application?', 'sixodp');?> <?php _e('Tell us!', 'sixodp');?></h3>
    </div>
    <div class="row btn-container">
      <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale_ckan() ?>/submit-data" class="btn btn-dataset btn--banner-jaa"><?php _e('Submit a dataset', 'sixodp');?></a>
      <a href="<?php echo get_permalink(get_translated_page_by_title('Uusi datatoive')); ?>" class="btn btn-dataset--inverse btn--banner-jaa"><?php _e('Share an open data idea', 'sixodp');?></a>
    </div>
  </div>

</div>
