<?php
  /**
  * Featured statistics partial
  */
?>

<div class="container container--home">
  <div class="row">
    <div class="stats-list">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="row">
            <div class="col-md-4 col-sm-12 stats-list-item">
              <div class="item-value"><?php echo get_dataset_count(); ?></div>
              <div class="item-description">Tietoaineistoa</div>
            </div>
            <div class="col-md-4 col-sm-12 stats-list-item">
              <div class="item-value"><?php echo get_showcases_count(); ?></div>
              <div class="item-description">Sovellusta</div>
            </div>
            <div class="col-md-4 col-sm-12 stats-list-item">
              <div class="item-value">1234</div>
              <div class="item-description">Julkaisijaa</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
