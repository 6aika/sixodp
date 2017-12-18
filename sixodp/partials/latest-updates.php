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
    <div class="row cards cards--4">
      <?php
        foreach ( get_latest_updates() as $index => $item ) : ?>
          <?php if ($index % 4 === 0) echo '</div><div class="row cards cards--4">'; ?>
          <div class="card" onclick="window.location.href='<?php echo $item['link']?>'">
            <div class="card-meta">
              <?php
              $label = is_array($item['type']) ? $item['type']['label'] : $item['type'];
              switch ($label) {
                case 'dataset':
                  echo _e('Dataset', 'sixodp');
                  break;
                case 'showcase':
                  echo _e('Showcase', 'sixodp');
                  break;
                case 'post':
                  echo _e('Article', 'sixodp');
                  break;
                case 'page':
                  echo _e('Page', 'sixodp') .'</span>';
                  break;
                case 'comment':
                  echo _e('Comment', 'sixodp') .'</span>';
                  break;
                default:
                  echo $label;
                  break;
              }
              ?>
            </div>
            <div class="card-content">
              <div class="card-title"><?php echo get_translated($item, 'title'); ?></div>
              <div class="card-title-secondary"><?php echo parse_date($item['date_updated']); ?></div>
              <div class="card-description">
                <?php echo wp_html_excerpt( strip_shortcodes(render_markdown(get_translated($item, 'notes'))), 240, '...'); ?>
              </div>
              <div class="card-link-wrapper card-link-slide-up">
                <a href="<?php echo $item['link'] ?>"
                   class="btn btn-transparent card-link">
                  <?php _e('Read more', 'sixodp') ?>
                </a>
              </div>
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
