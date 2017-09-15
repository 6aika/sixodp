<?php
  /**
  * Morelinks template
  */
?>

<div class="wrapper--morelinks">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h1 class="heading--themes"><?php echo $morelinks_title; ?></h1>
      </div>
      <div class="col-md-8">
        <?php foreach ($links as $link) : ?>
          <div class="icon-link morelink">
            <p class="icon-link__icon">
              <a class="icon-link__link--round" href="<?php echo $link->post_name; ?>">
                <i class="material-icons">arrow_forward</i>
              </a>
            </p>
            <p class="icon-link__text">
              <a href="<?php echo get_permalink($link) ?>"><?php echo $link->post_title; ?></a>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>