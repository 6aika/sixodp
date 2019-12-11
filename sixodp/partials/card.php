<?php
  /**
  * Template for a single card
  *
  */
?>
<div class="card<?php if (isset($item['external_card_class'])) echo ' ' . $item['external_card_class'] ?>"
  onclick="window.location.href='<?php echo $item['url'] ?>'">
  <?php if ($item['meta']) : ?>
    <div class="card-meta"><?php echo $item['meta'] ?></div>
  <?php endif ?>
  <div class="card-content">
    <div class="card-title"><?php echo get_translated($item, 'title'); ?></div>
    <div class="card-title-secondary"><?php echo parse_date($item['timestamp']); ?></div>
    <div class="card-description">
      <?php echo wp_html_excerpt( strip_shortcodes(render_markdown(get_translated($item, 'notes'))), 300, '...'); ?>
    </div>
    <div class="card-link-wrapper">
      <a href="<?php echo $item['url'] ?>"
         class="btn btn-transparent card-link card-link-slide-up">
        <?php _e('Read more', 'sixodp') ?>
      </a>
    </div>
  </div>
</div>
