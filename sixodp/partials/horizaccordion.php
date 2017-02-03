<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--horizaccordion">
  <div class="container">
    <div class="row">
      <div class="horizaccordion">
        <?php
          $i = 0;
          while($i < 4) {
            $imgUrl = site_url()."/wp-content/themes/sixodp/images/article_bg".($i+1).".jpg"; ?>

            <div class="horizaccordion__container">
              <div class="horizaccordion__item" style="background-image: url(<?php echo $imgUrl; ?>);">
                <h4 class="horizaccordion__title">
                  <a class="horizaccordion__link" href="<?php echo site_url(); ?>">
                    Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
                  </a>
                </h4>
                <p class="horizaccordion__text">Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <div class="horizaccordion__footer">
                  <button type="button" class="btn btn-secondary">Lue lisää</button>
                </div>
              </div>
            </div><?php
            $i++;
          }
        ?>
      </div>
    </div>
  </div>
</div>
