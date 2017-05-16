<?php
  /**
  * Featured posts partial.
  */
?>

<div class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h3 class="heading"><?php _e('Datasets');?> </h3>
        <br><br>
      </div>

      <div class="col-md-5 datasets">
        <h3 class="heading"><?php _e('Latest updates');?> </h3>
        <ul class="dataset__list">
          <?php foreach ( get_recent_content() as $item ) : ?>
            <li class="dataset">
              <div class="dataset__left">
                <div class="dataset__meta">
                  <div class="dataset__meta-wrapper">
                    <span class="dataset__sincedays">30 <?php _e('days');?> <?php /*echo parse_date($item['metadata_modified']);*/ ?></span>
                    <span class="dataset__sincehours">7 <?php _e('hours');?> </span>
                    <span class="dataset__sincetext"><?php _e('ago');?> </span>
                  </div>
                </div>
              </div>
              <div class="dataset__content">
                <span class="dataset__type"><?php echo $item['type']; ?></span>
                <h4 class="dataset__title">
                  <a class="dataset__link" href="<?php echo CKAN_BASE_URL . '/dataset/' . echo $item['name']; ?>">
                    <?php echo $item['title']; ?>
                  </a>
                </h4>
                <div class="dataset__body">
                  <p class="dataset__info"><?php echo get_notes_excerpt($item['notes']); ?>
                    <a href="<?php echo CKAN_BASE_URL . '/dataset/' . echo $item['name']; ?>"><i class="material-icons">arrow_forward</i></a>
                  </p>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
          <li class="dataset">
            <div class="dataset__left">
              <div class="dataset__meta">
                <div class="dataset__meta-wrapper">
                  <span class="dataset__sincedays">30 <?php _e('days');?> </span>
                  <span class="dataset__sincehours">7 <?php _e('hours');?> </span>
                  <span class="dataset__sincetext"><?php _e('ago');?> </span>
                </div>
              </div>
            </div>
            <div class="dataset__content">
              <span class="dataset__type"><?php _e('Article');?> </span>
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
          <?php _e('More updates');?>  <i class="material-icons">arrow_forward</i>
        </button>
      </div>
    </div>
  </div>
</div>
