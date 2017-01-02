<?php
/**
* Search -partial
*/
?>

<div class="searchbar bgcolor-primary">
  <div class="wrapper">
    <div class="row">
      <div class="col-md-5 col-xs-12 searchbar__col">
        <h4>Avointa dataa vapaasti hyödynnettäväksesi</h4>
      </div>
      <div class="col-md-5 col-xs-12 searchbar__col">
        <div class="input-group">
          <div class="input-group-btn">
            <button type="button" class="btn bgcolor-complementary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tietoaineistot <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="#">Tietoaineistot</a></li>
              <li><a href="#">Sovellukset</a></li>
              <li><a href="#">Artikkelit</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#">Muut</a></li>
            </ul>
          </div><!-- /btn-group -->
          <input type="text" class="form-control" aria-label="...">
        </div><!-- /input-group -->
      </div>
      <div class="col-md-2 pull-right searchbar__col">
        <button type="button" class="btn bgcolor-complementary">Kaikki aineistot</button>
      </div>
    </div>

    <?php get_template_part( 'partials/featured-stats' ); ?>

  </div>
</div>
