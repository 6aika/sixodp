<?php
  /**
  * Category list partial.
  */
?>

<div class="wrapper wrapper--categories">
  <div class="container">
    <div class="row">
      <div class="categories">
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/hallinto.svg">
            <span class="category__name">Hallinto</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/liikenne.svg">
            <span class="category__name">Liikenne ja kartat</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/matkailu.svg">
            <span class="category__name">Matkailu</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/sosiaalipalvelut.svg">
            <span class="category__name">Opetus</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/rakentaminen.svg">
            <span class="category__name">Asuminen/rakentaminen</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/talousjaverotus.svg">
            <span class="category__name">Talous</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/terveys.svg">
            <span class="category__name">Terveys</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/vaesto.svg">
            <span class="category__name">Väestö</span>
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/ymparistojaluonto.svg">
            <span class="category__name">Ympäristö ja liikunta</span>
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php include(locate_template('/partials/featured-stats.php')); ?>
</div>
