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
          <div class="card card-hover card-hover-light" onclick="window.location.href='<?php echo $item['link']?>'">

            <h3 class="card__title">
              <a href="<?php echo $item['link'] ?>">
                <?php echo get_translated($item, 'title'); ?>
              </a>
            </h3>

            <p class="card__description">
              <span class="card__timestamp"><?php echo parse_date($item['date_updated']); ?></span><br />
              <?php echo wp_html_excerpt( render_markdown(get_translated($item, 'notes')), 240, '...'); ?>
            </p>

            <div class="card__meta">
              <?php
                $label = is_array($item['type']) ? $item['type']['label'] : $item['type'];
                switch ($label) {
                  case 'dataset':
                    echo '<span class="fa fa-database"></span>&nbsp;';
                    echo _e('Dataset', 'sixodp');
                    break;
                  case 'showcase':
                    echo '<span><span class="fa fa-line-chart"></span>&nbsp;';
                    echo _e('Showcase', 'sixodp');
                    break;
                  case 'post':
                    echo '<span class="fa fa-address-card"></span>&nbsp;';
                    echo _e('Article', 'sixodp');
                    break;
                  case 'page':
                    echo '<span class="fa fa-file-text"></span>&nbsp;';
                    echo _e('Page', 'sixodp') .'</span>';
                    break;
                  default:
                    echo $label;
                    break;
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
