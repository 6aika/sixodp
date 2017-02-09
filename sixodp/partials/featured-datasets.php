<?php
  /**
  * Featured posts partial.
  */
?>

<div class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <h3 class="heading">Tietoaineistot</h3>
      </div>

      <div class="col-md-4">
        <h3 class="heading">Viimeisimmät päivitykset</h3>
        <ul class="datasets">
          <?php foreach ( get_recent_content() as $item ) : ?>
            <li class="dataset">
              <h4 class="dataset__title">
                <a class="dataset__link" href="/dataset/tampereen-rakennukset-alueina">
                  <?php echo $item['title']; ?>
                </a>
              </h4>
              <div class="dataset__meta">
                <span class="dataset__timestamp"><?php echo parse_date($item['metadata_modified']); ?></span>
                <span class="dataset__type"><?php echo $item['type']; ?></span>
              </div>
              <div class="dataset__body">
                <p class="dataset__info"><?php echo $item['notes']; ?></p>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
