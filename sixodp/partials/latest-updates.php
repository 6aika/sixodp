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
    <div class="row cards cards--latest">
      <?php
        foreach ( get_latest_updates() as $index => $item ) : ?>
          <?php if ($index === 4 || $index === 8) echo '</div><div class="row cards cards--latest">'; ?>
          <div class="card card-hover card--latest">

            <h3 class="card__title">
              <a href="<?php echo $item['link']?>">
                <?php echo get_translated($item, 'title'); ?>
              </a>
            </h3>
            <div class="card__meta">
              <span class="card__timestamp"><?php echo parse_date($item['date_updated']); ?></span>
              <?php
              if (is_array($item['type'])) {
                echo '<a href="'. $item['type']['link'] .'" class="card__categorylink--latest">'. $item['type']['label'] .'</a>';
              }
              else {

                echo '<span>&bull;</span><span class="card__categorylink--latest">'. $item['type'] .'</span>';
              }
              ?>
            </div>

            <p class="card__description">
              <?php echo get_notes_excerpt(get_translated($item, 'notes')); ?>
            </p>
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
