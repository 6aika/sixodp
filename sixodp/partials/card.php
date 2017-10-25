<?php
  /**
  * Template for a single card
  *
  */
?>
<div class="<?php echo $class; ?>">
  <div class="card" style="background-image: url(<?php echo $imgUrl; ?>);">
    <a href="<?php echo site_url(); ?>"></a>
    <div class="card__content">
      <h4 class="card-title">
        <a class="card__link" href="<?php echo site_url(); ?>">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</a>
      </h4>
      <p>Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      <div class="card__footer">
        <button type="button" class="btn btn-primary"><?php _e('Read more', 'sixodp');?></button>
      </div>
    </div>
  </div>
</div>
