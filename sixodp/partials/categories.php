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
            Hallinto
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/liikenne.svg">
            Liikenne ja kartat
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/matkailu.svg">
            Matkailu
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/sosiaalipalvelut.svg">
            Opetus
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/rakentaminen.svg">
            Asuminen/rakentaminen
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/talousjaverotus.svg">
            Talous
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/terveys.svg">
            Terveys
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/vaesto.svg">
            Väestö
          </a>
        </div>
        <div class="category">
          <a href="<?php echo site_url(); ?>" class="category__link">
            <img class="category__icon" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/icons/ymparistojaluonto.svg">
            Ympäristö ja liikunta
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php include(locate_template('/partials/featured-stats.php')); ?>
</div>
