<?php
  /**
  * Featured posts partial.
  */
?>

<div class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h3 class="heading">Tietoaineistot</h3>
        <br><br>
        <!-- TEMP SHIT -->
        <img src="/assets/images/datapie.png" style="width: 80%; height: auto;">
        <!-- END TEMP SHIT -->
      </div>

      <div class="col-md-5 datasets">
        <h3 class="heading">Viimeisimmät päivitykset</h3>
        <ul class="dataset__list">
          <?php foreach ( get_recent_content() as $item ) : ?>
            <li class="dataset">
              <div class="dataset__left">
                <div class="dataset__meta">
                  <div class="dataset__meta-wrapper">
                    <span class="dataset__sincedays">30 päivää<?php /*echo parse_date($item['metadata_modified']);*/ ?></span>
                    <span class="dataset__sincehours">7 tuntia</span>
                    <span class="dataset__sincetext">sitten</span>
                  </div>
                </div>
              </div>
              <div class="dataset__content">
                <span class="dataset__type"><?php echo $item['type']; ?></span>
                <h4 class="dataset__title">
                  <a class="dataset__link" href="/dataset/tampereen-rakennukset-alueina">
                    <?php echo $item['title']; ?>
                  </a>
                </h4>
                <div class="dataset__body">
                  <p class="dataset__info"><?php echo $item['notes']; ?></p>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
          <li class="dataset">
            <div class="dataset__left">
              <div class="dataset__meta">
                <div class="dataset__meta-wrapper">
                  <span class="dataset__sincedays">30 päivää</span>
                  <span class="dataset__sincehours">7 tuntia</span>
                  <span class="dataset__sincetext">sitten</span>
                </div>
              </div>
            </div>
            <div class="dataset__content">
              <span class="dataset__type">Artikkeli</span>
              <h4 class="dataset__title">
                <a class="dataset__link" href="/dataset/tampereen-rakennukset-alueina">
                  Tampereen rakennukset alueina
                </a>
              </h4>
              <div class="dataset__body">
                <p class="dataset__info">Kartta Tampereen rakennuksista aluepohjaisesti</p>
              </div>
            </div>
          </li>
        </ul>
        <button type="button" class="btn btn-lg btn-secondary pull-right">
          Lisää päivityksiä <i class="material-icons">arrow_forward</i>
        </button>
      </div>
    </div>
  </div>
</div>
