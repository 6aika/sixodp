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
              <a href="#" class="card__categorylink--latest"><?php echo $dataset['type']; ?></a>
              <h3 class="card__title--latest"><?php echo $dataset["title_translated"][get_current_locale()]; ?></h3>
              <p class="card__description"><?php echo get_notes_excerpt($dataset['notes_translated'][get_current_locale()]); ?></p>
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
