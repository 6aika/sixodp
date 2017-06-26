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
              <?php echo get_days_ago($item['date_updated'] ? $item['date_updated'] : $item['metadata_modified']); ?>
            </div>
            <div class="card__body--latest">
              <a href="<?php echo CKAN_BASE_URL; ?>/<?php echo get_current_locale(); ?>/<?php echo $item['type']; ?>" class="card__categorylink--latest"><?php echo $item['type']; ?></a>
              <h3 class="card__title--latest">
                <a href="<?php echo CKAN_BASE_URL.'/'.get_current_locale().'/'.$item['type'].'/'.$item['name']; ?>">
                  <?php
                    $itemTitle = (isset($item["title_translated"][get_current_locale()]) ? $item["title_translated"][get_current_locale()] : $item["title"]);
                    echo $itemTitle
                  ?>
                </a>
              </h3>
              <p class="card__description">
                <?php
                  $itemNotes = (isset($item["notes_translated"][get_current_locale()]) ? $item["notes_translated"][get_current_locale()] : null);
                  echo get_notes_excerpt($itemNotes);
                ?>
              </p>
            </div>
          </div><?php
        endforeach; ?>
    </div>
  </div>

  <div class="container" style="display: none;">
    <div class="row text-right">
      <a href="/data/<?php echo get_current_locale(); ?>/datasets/" class="btn btn-lg btn-secondary btn--sovellukset">
        <?php _e('More updates', 'sixodp');?>  <i class="material-icons">arrow_forward</i>
      </a>
    </div>
  </div>

</div>
