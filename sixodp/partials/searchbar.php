<?php
/**
* Search -partial
*/
?>

<div class="searchbar">
  <div class="container">
    <div class="row">
      <div class="col-md-5 col-xs-12 searchbar__col">
        <h4>Avointa dataa vapaasti hyödynnettäväksesi</h4>
      </div>
      <div class="col-md-7 col-xs-12 searchbar__col">
        <div class="input-group">
          <div class="input-group-btn">
            <button type="button" class="btn btn-lg btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tietoaineistot <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="#">Tietoaineistot</a></li>
              <li><a href="#">Sovellukset</a></li>
              <li><a href="#">Artikkelit</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#">Muut</a></li>
            </ul>
          </div><!-- /btn-group -->
          <input type="text" class="form-control input-lg" aria-label="...">
        </div><!-- /input-group -->
      </div>
    </div>
  </div>
  <?php include(locate_template('/partials/featured-stats.php')); ?>
</div>
