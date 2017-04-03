<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--featured">
  
  <div class="container">
    <h1 class="heading--featured heading--mleft">Tietoaineistot ja sovellukset</h1>
  </div>

  <div class="container banner">
    <div class="row text-center">
      <div class="col-md-6">
        <h2 class="banner__title">Onko sinulla valmis sovellus tai tietoainesto?</h2>
        <h4 class="banner__subtitle">Laita se jakoon!</h4>
        <div class="banner__buttons">
          <button class="btn btn-transparent btn--banner-jaa">Jaa sovellus <i class="material-icons">arrow_forward</i></button>
          <button class="btn btn-transparent btn--banner-jaa">Jaa aineisto <i class="material-icons">arrow_forward</i></button>
        </div>
      </div>
      <div class="col-md-6">
        <h2 class="banner__title">Onko sinulla valmis sovellus tai tietoainesto?</h2>
        <h4 class="banner__subtitle">Laita se jakoon!</h4>
        <div class="banner__buttons">
          <button class="btn btn-transparent btn--banner-jaa">Jaa sovellus <i class="material-icons">arrow_forward</i></button>
          <button class="btn btn-transparent btn--banner-jaa">Jaa aineisto <i class="material-icons">arrow_forward</i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <h3 class="heading--featured-small">Uusimmat sovellukset</h3>
  </div>

  <div class="container">
    <div class="row cards--showcase">
      <?php
        $showcases = get_items('ckanext_showcase');
        foreach ($showcases as $showcase) {
          $imgUrl = "https://generic-qa.dataportaali.com/data/uploads/showcase/".$showcase['extras'][6]['value'];
          $packageId = $showcase['id'];
          foreach ( $showcase['extras'] as $extra ) {
            if ( $extra['key'] == 'icon' ) {
              $imgUrl = "https://generic-qa.dataportaali.com/data/uploads/showcase/".$extra['value'];
            } else if ( $extra['key'] == 'notes_translated' ) {
              $notes = json_decode($extra['value'], true)[get_current_locale()];
            }
          }
          include(locate_template( 'partials/showcase.php' ));
        }
      ?>
    </div>
  </div>

  <div class="container">
    <div class="row text-right">
      <a href="<?php echo CKAN_BASE_URL; ?>/data/showcase" class="btn btn-lg btn-secondary btn--sovellukset">
        Kaikki sovellukset <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

  <div class="container">
    <h3 class="heading--featured-small">Uusimmat tietoaineistot</h3>
  </div>

  <div class="container">
    <div class="row cards">
      <?php foreach ( get_recent_datasets() as $dataset ) : ?>
        <div class="card">
          <h3 class="card__title"><?php echo $dataset["title_translated"][get_current_locale()]; ?></h3>
          <div class="card__meta">
            <span class="card__timestamp"><?php echo parse_date($dataset['metadata_created']); ?></span>
            *
            <a href="#" class="card__categorylink"><?php echo $dataset['type']; ?></a>
          </div>
          <div class="card__body">
            <p><?php echo get_notes_excerpt($dataset['notes_translated'][get_current_locale()]); ?></p>
          </div>
        </div><?php
      endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="row text-right">
      <a href="<?php echo CKAN_BASE_URL; ?>/data/<?php echo get_current_locale(); ?>/dataset" class="btn btn-lg btn-secondary btn--sovellukset">
        Kaikki tietoaineistot <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

</div>
