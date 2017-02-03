<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--posts">
  <div class="container">
    <div class="row">
      <div class="cards">
        <?php
          $i = 0;
          while($i < 4) {
            $imgUrl = site_url()."/wp-content/themes/sixodp/images/article_bg".($i+1).".jpg";
            if ($i==2) {
              $class = 'card__container card__container--active';
            } else {
              $class = 'card__container';
            }
            include(locate_template( 'partials/card.php' ));
            $i++;
          }
        ?>
      </div>
    </div>
  </div>
</div>
