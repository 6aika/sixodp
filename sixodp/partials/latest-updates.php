<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--latest">

  <div class="container">
    <h1 class="heading--featured"><?php _e('Latest updates', 'sixodp');?> </h1>
  </div>

  <div class="container">
    <div class="row cards">
      <?php
        foreach ( get_latest_updates() as $index => $item ) : ?>
          <?php if ($index % 3 === 0) echo '</div><div class="row cards">'; ?>
          <div class="card card-hover">

            <h3 class="card__title">
              <a href="<?php echo $item['link']?>">
                <?php echo get_translated($item, 'title'); ?>
              </a>
            </h3>

            <p class="card__description">
              <span class="card__timestamp"><?php echo parse_date($item['date_updated']); ?></span><br />
              <?php echo get_notes_excerpt(get_translated($item, 'notes')); ?>
            </p>

            <div class="card__meta">
              <?php
                if (is_array($item['type'])) {
                  echo '<a href="'. $item['type']['link'] .'" class="card__categorylink">'. $item['type']['label'] .'</a>';
                }
                else {
                  echo '<span>&bull;</span><span class="card__categorylink">'. $item['type'] .'</span>';
                }
              ?>
            </div>
          </div><?php
        endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="row latest-btn-container">
      <a href="<?php echo get_permalink(get_translated_page_by_title('Viimeisimmät päivitykset')); ?>" class="btn btn-secondary btn--sovellukset">
        <?php _e('More updates', 'sixodp');?>  <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

</div>
