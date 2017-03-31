<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--featured">
  
  <div class="container">
    <h1 class="heading--featured heading--mleft">Tietoaineistot ja sovellukset</h1>
  </div>

  <div class="container banner">
    <div class="row text-center">
      <div class="col-md-6">
        <h2 class="banner__title">Onko sinulla valmis sovellus tai tietoainesto?</h2>
        <h4 class="banner__subtitle">Laita se jakoon!</h4>
        <div class="banner__buttons">
          <button class="btn btn-transparent btn--banner-jaa">Jaa sovellus <i class="material-icons">arrow_forward</i></button>
          <button class="btn btn-transparent btn--banner-jaa">Jaa aineisto <i class="material-icons">arrow_forward</i></button>
        </div>
      </div>
      <div class="col-md-6">
        <h2 class="banner__title">Onko sinulla valmis sovellus tai tietoainesto?</h2>
        <h4 class="banner__subtitle">Laita se jakoon!</h4>
        <div class="banner__buttons">
          <button class="btn btn-transparent btn--banner-jaa">Jaa sovellus <i class="material-icons">arrow_forward</i></button>
          <button class="btn btn-transparent btn--banner-jaa">Jaa aineisto <i class="material-icons">arrow_forward</i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <h3 class="heading--featured-small">Uusimmat sovellukset</h3>
  </div>

  <div class="container">
    <div class="row cards--showcase">
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

  <div class="container">
    <div class="row text-right">
      <button type="button" class="btn btn-lg btn-secondary btn--sovellukset">
        Kaikki sovellukset <i class="material-icons">arrow_forward</i>
      </button>
    </div>
  </div>

  <div class="container">
    <h3 class="heading--featured-small">Uusimmat tietoaineistot</h3>
  </div>

  <div class="container">
    <div class="row cards">
      <div class="card">
        <h3 class="card__title">Helsingin jalankulun ja pyöräilyn talvihoito</h3>
        <div class="card__meta">
          <span class="card__timestamp">12.8.2016</span>
          *
          <a href="#" class="card__categorylink">Tietoaineisto</a>
        </div>
        <div class="card__body">
          <p>Nullam quis risus eget urna mollis ornare vel eu leo. Cras mattis consectetur purus sit amet fermentum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam.</p>
        </div>
      </div>
      <div class="card">
        <h3 class="card__title">Helsingin jalankulun ja pyöräilyn talvihoito</h3>
        <div class="card__meta">
          <span class="card__timestamp">12.8.2016</span>
          *
          <a href="#" class="card__categorylink">Tietoaineisto</a>
        </div>
        <div class="card__body">
          <p>Nullam quis risus eget urna mollis ornare vel eu leo. Cras mattis consectetur purus sit amet fermentum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam.</p>
        </div>
      </div>
      <div class="card">
        <h3 class="card__title">Helsingin jalankulun ja pyöräilyn talvihoito</h3>
        <div class="card__meta">
          <span class="card__timestamp">12.8.2016</span>
          *
          <a href="#" class="card__categorylink">Tietoaineisto</a>
        </div>
        <div class="card__body">
          <p>Nullam quis risus eget urna mollis ornare vel eu leo. Cras mattis consectetur purus sit amet fermentum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row text-right">
      <button type="button" class="btn btn-lg btn-secondary btn--sovellukset">
        Kaikki tietoaineistot <i class="material-icons">arrow_forward</i>
      </button>
    </div>
  </div>

</div>
