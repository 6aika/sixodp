<?php
  /**
  * Featured statistics partial
  */
?>

<div class="container">
  <div class="row">
    <div class="stats">
      <div class="stat__wrapper">
        <div class="stat">
          <div class="stat__value"><?php echo get_dataset_count(); ?></div>
          <div class="stat__description">Tietoaineistoa</div>
        </div>
      </div>
      <div class="stat__wrapper">
        <div class="stat">
          <div class="stat__value"><?php echo get_showcases_count(); ?></div>
          <div class="stat__description">Sovellusta</div>
        </div>
      </div>
      <div class="stat__wrapper">
        <div class="stat">
          <div class="stat__value"><?php echo get_organizations_count(); ?></div>
          <div class="stat__description">Julkaisijaa</div>
        </div>
      </div>
      <div class="stat__wrapper">
        <button type="button" class="btn btn-lg btn-secondary">Kaikki aineistot</button>
      </div>
    </div>
  </div>
</div>
