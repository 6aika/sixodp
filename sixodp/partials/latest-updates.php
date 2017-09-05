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
    <div class="row cards--latest">
      <?php
        foreach ( get_latest_updates() as $index => $item ) : ?>
          <?php if ($index === 4 || $index === 8) echo '</div><div class="row cards--latest">'; ?>
          <div class="card--latest">
            <div class="card__meta--latest">
              <?php echo get_days_ago($item['date_updated'] ? $item['date_updated'] : $item['date']); ?>
            </div>
            <div class="card__body--latest">
              <?php
              if (is_array($item['type'])) {
                echo '<a href="'. $item['type']['link'] .'" class="card__categorylink--latest">'. $item['type']['label'] .'</a>';
              }
              else {
                echo '<span class="card__categorylink--latest">'. $item['type'] .'</span>';
              }
              ?>
              <h3 class="card__title--latest">

                <a href="<?php echo $item['link']?>">
                  <?php echo get_translated($item, 'title'); ?>
                </a>
              </h3>
              <p class="card__description">
                <?php echo get_notes_excerpt(get_translated($item, 'notes')); ?>
              </p>
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
