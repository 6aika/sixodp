<?php
  /**
  * Featured posts partial.
  */
?>

<div class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h3 class="heading"><?php _e('Datasets', 'sixodp');?> </h3>
        <br><br>
      </div>

      <div class="col-md-5 datasets">
        <h3 class="heading"><?php _e('Latest updates', 'sixodp');?> </h3>
        <ul class="dataset__list">
          <?php foreach ( get_recent_content() as $item ) : ?>
            <li class="dataset">
              <div class="dataset__left">
                <div class="dataset__meta">
                  <div class="dataset__meta-wrapper">
                    <span class="dataset__sincedays">30 <?php _e('days', 'sixodp');?> <?php /*echo parse_date($item['metadata_modified']);*/ ?></span>
                    <span class="dataset__sincehours">7 <?php _e('hours', 'sixodp');?> </span>
                    <span class="dataset__sincetext"><?php _e('ago', 'sixodp');?> </span>
                  </div>
                </div>
              </div>
              <div class="dataset__content">
                <span class="dataset__type"><?php echo $item['type']; ?></span>
                <h4 class="dataset__title">
                  <a class="dataset__link" href="<?php echo CKAN_BASE_URL . '/dataset/' . $item['name']; ?>">
                    <?php echo get_translated($item, 'title'); ?>
                  </a>
                </h4>
                <div class="dataset__body">
                  <p class="dataset__info"><?php echo get_notes_excerpt(get_translated($item, 'notes')); ?>
                    <a href="<?php echo CKAN_BASE_URL . '/dataset/' . $item['name']; ?>"><i class="material-icons">arrow_forward</i></a>
                  </p>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn btn-secondary pull-right">
          <?php _e('More updates', 'sixodp');?>  <i class="material-icons">arrow_forward</i>
        </button>
      </div>
    </div>
  </div>
</div>
