<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--latest">
  
  <div class="container">
    <h1 class="heading--featured heading--mleft">Viimeisimmät päivitykset</h1>
  </div>

  <div class="container">
    <div class="row cards--latest">
      <?php
        // @TODO: get data from different sources
        foreach ( get_all_recent_data() as $index => $dataset ) : ?>
          <?php if ($index === 4 || $index === 8) echo '</div><div class="row cards--latest">'; ?> 
          <div class="card--latest">
            <div class="card__meta--latest">
              <?php echo get_days_ago($dataset['metadata_created']); ?>
            </div>
            <div class="card__body--latest">
              <a href="<?php echo CKAN_BASE_URL; ?>/data/<?php echo get_current_locale(); ?>/<?php echo $dataset['type']; ?>" class="card__categorylink--latest"><?php echo $dataset['type']; ?></a>
              <h3 class="card__title--latest">
                <?php if ( $dataset['title_translated'][get_current_locale()] ) { ?>
                  <a href="<?php echo CKAN_BASE_URL.'/data/'.get_current_locale().'/dataset/'.$dataset['name']; ?>"><?php echo $dataset["title_translated"][get_current_locale()]; ?></a>
                <?php } else if ( $dataset['title'] ) { ?>
                  <a href="<?php echo CKAN_BASE_URL.'/data/'.get_current_locale().'/dataset/'.$dataset['name']; ?>"><?php echo $dataset["title"]; ?></a>
                <?php } else { ?>
                  <a class="btn btn-transparent--inverse" href="<?php echo CKAN_BASE_URL.'/data/'.get_current_locale().'/dataset/'.$dataset['name']; ?>">Lue lisää <i class="material-icons">arrow_forward</i></a>
                <?php } ?>
              </h3>
              <?php if ( $dataset['notes_translated'][get_current_locale()] ) { ?>
                <p class="card__description"><?php echo get_notes_excerpt($dataset['notes_translated'][get_current_locale()]); ?></p>
              <?php } else if ( $dataset['notes'] ) { ?>
                <p class="card__description"><?php echo get_notes_excerpt($dataset['notes']); ?></p>
              <?php } ?>
            </div>
          </div><?php
        endforeach; ?>
    </div>
  </div>

  <div class="container" style="display: none;">
    <div class="row text-right">
      <a href="/data/<?php echo get_current_locale(); ?>/datasets/" class="btn btn-lg btn-secondary btn--sovellukset">
        Lisää päivityksiä <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

</div>
