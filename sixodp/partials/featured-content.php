<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="container home-highlight container--home container--padded container--centered">
  <h3 class="highlight-header">Ajankohtaista</h3>

  <div class="flex--cards">
    <?php
      $i = 0;
      while($i < get_field("ajankohtaista_count")) { ?>
        <div class="featured-content__item">
          <img src="http://placehold.it/320x280">
          <div class="wrapper">
            <h4>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</h4>
            <p>Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <button type="button" class="btn bgcolor-primary">Lue lisää</button>
          </div>
        </div><?php
        $i++;
      }
    ?>
  </div>
</div>
