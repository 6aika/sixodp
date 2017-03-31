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
        foreach ( get_recent_content() as $index => $dataset ) : ?>
          <?php if ($index === 4) echo '</div><div class="row cards--latest">'; ?> 
          <div class="card--latest">
            <div class="card__meta--latest">
              <?php echo get_days_ago($dataset['metadata_created']); ?>
            </div>
            <div class="card__body">
              <a href="#" class="card__categorylink">Tietoaineistot</a>
              <h3 class="card__title--latest"><?php echo $dataset["title_translated"][get_current_locale()]; ?></h3>
              <div class="card__meta">
                <span class="card__timestamp"><?php echo parse_date($dataset['metadata_created']); ?></span>
              </div>
            </div>
          </div><?php
        endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="row text-right">
      <button type="button" class="btn btn-lg btn-secondary btn--sovellukset">
        Kaikki tietoaineistot <i class="material-icons">arrow_forward</i>
      </button>
    </div>
  </div>

</div>
