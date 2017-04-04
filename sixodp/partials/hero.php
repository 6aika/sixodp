<?php
/**
* Hero -partial
*/
?>

<div class="hero" style="background-image: url(<?php echo get_field('frontpage_background')['url']; ?>);">
  <div class="hero__inner">
    <div class="logos">
      <a href="<?php echo site_url(); ?>" class="logo--brand">
        <img src="<?php echo assets_url(); ?>/images/main_logo.png" />
      </a>
      <div class="logos--eu">
        <div class="logo--erdf">
          <img src="<?php echo assets_url(); ?>/images/EU_ERDF_FI.png" alt="European Regional Development Fund logo">
        </div>
        <div class="logo--eu">
          <img src="<?php echo assets_url(); ?>/images/LeverageEU_FI.png" alt="Leverage with EU logo">
        </div>
        <div class="flag-ends"></div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <h1 class="heading--main text-center">6Aika Open Data Portal</h1>
        <h3 class="subheading text-center">Avointa dataa vapaasti hyödynnettäväksesi</h3>  

        <div class="col-md-8 col-md-offset-2">
          <div class="input-group">
            <div class="input-group-btn">
              <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span id="selected-domain" data-value="/data/dataset">Tietoaineistot</span> <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li><a data-value="/data/dataset">Tietoaineistot</a></li>
                <li><a data-value="/data/showcase">Sovellukset</a></li>
                <li><a data-value="/data/collection">Aineistokokonaisuudet</a></li>
                <li><a data-value="/posts">Artikkelit</a></li>
                <li role="separator" class="divider"></li>
                <li><a>Muut</a></li>
              </ul>
            </div><!-- /btn-group -->
            <input type="text" id="q" class="form-control input-lg" aria-label="...">
            <span class="input-group-btn">
              <button id="search" class="btn btn-lg btn-primary" type="button">Hae</button>
            </span>
          </div><!-- /input-group -->    
        </div>

      </div>
    </div>
