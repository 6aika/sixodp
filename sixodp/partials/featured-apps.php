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
    <?php $showcases = get_latest_showcases(4); ?>

    <div id="featured-apps-carousel" class="carousel slide mobile-only" data-ride="carousel" data-interval="false">
      <div class="carousel-inner" role="listbox">
        <?php foreach($showcases as $showcase) : ?>
          <?php $extra_classes = $showcase === reset($showcases) ? ' active' : ''; ?>

          <div class="item<?php echo $extra_classes ?>">
            <?php
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
            ?>
          </div>
        <?php endforeach; ?>
      </div>

      <a class="left carousel-control" href="#featured-apps-carousel" role="button" data-slide="prev">
        <span class="fa fa-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#featured-apps-carousel" role="button" data-slide="next">
        <span class="fa fa-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

      <ol class="carousel-indicators">
        <?php foreach($showcases as $index => $showcase) : ?>
          <?php $extra_classes = $showcase === reset($showcases) ? 'active' : ''; ?>
          <li data-target="#featured-apps-carousel" data-slide-to="<?php echo $index ?>" class="<?php echo $extra_classes ?>"></li>
        <?php endforeach; ?>
      </ol>
    </div>

    <div class="row cards cards--4 desktop-only">
      <?php
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
