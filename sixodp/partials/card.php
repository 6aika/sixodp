<?php
  /**
  * Template for a single card
  *
  */
?>
<div class="card__container">
  <div class="card">
    <a href="<?php echo site_url(); ?>" class="card__img--link">
      <img class="card__img" src="<?php echo $imgUrl; ?>" alt="thumbnail">
    </a>
    <div class="card__content">
      <h4 class="card__title">
        <a class="card__link" href="<?php echo site_url(); ?>">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</a>
      </h4>
      <p>Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      <div class="card__footer">
        <button type="button" class="btn btn-primary">Lue lisää</button>
      </div>
    </div>
  </div>
</div>
