<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper">
  <div class="container">
    <div class="row">
      <h3 class="heading">Ajankohtaista</h3>
      <div class="cards">
        <?php
          $i = 0;
          while($i < 4) {
            $imgUrl = site_url()."/wp-content/themes/sixodp/images/article_bg".($i+1).".jpg";
            include(locate_template( 'partials/card.php' ));
            $i++;
          }
        ?>
      </div>
    </div>
  </div>
</div>
