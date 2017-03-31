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
        foreach ( get_datasets() as $dataset ) : ?>
          <div class="card--latest">
            <div class="card__meta--latest">
              <?php //echo get_days_ago($dataset['date_released']); ?>
              <span>3</span> päivää, <span>20</span> tuntia sitten
            </div>
            <div class="card__body">
              <h3 class="card__title--latest"><?php echo $dataset["title"]; ?></h3>
              <div class="card__meta">
                <span class="card__timestamp">12.08.2017</span>
                <a href="#" class="card__categorylink">Tietoaineistot</a>
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
