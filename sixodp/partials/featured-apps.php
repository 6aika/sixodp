<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper">
  <div class="container">
    <div class="row">
      <h3 class="heading">Sovellukset</h3>
    </div>
  </div>
</div>

<div class="wrapper">
  <div class="">
    <div class="row">
      <div class="showcase_list">
        <?php
          $i = 0;
          while($i < 4) {
            $imgUrl = site_url()."/wp-content/themes/sixodp/images/article_bg".($i+1).".jpg";
            include(locate_template( 'partials/showcase.php' ));
            $i++;
          }
        ?>
      </div>
    </div>
  </div>
</div>
