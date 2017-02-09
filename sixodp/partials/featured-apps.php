<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--showcase">
  <div class="container">
    <div class="row">
      <h3 class="heading">Sovellukset</h3>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="showcase_list">
        <?php
          $i = 0;
          while($i < 4) {
            $imgUrl = site_url()."/wp-content/themes/sixodp/images/article_bg".($i+1).".jpg";
            $packageId = (rand(1, 4))*3;
            include(locate_template( 'partials/showcase.php' ));
            $i++;
          }
        ?>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row text-right">
      <button type="button" class="btn btn-lg btn-secondary btn--sovellukset">
        Sovellukset <i class="material-icons">arrow_forward</i>
      </button>
    </div>
  </div>
</div>
