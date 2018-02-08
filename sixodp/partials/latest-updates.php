<?php
  /**
  * Featured content box on homepage.
  */
?>

<div class="wrapper--featured">
  <div class="container container--heading">
    <h2 class="heading--featured"><?php _e('Latest updates', 'sixodp');?> </h2>
  </div>

  <div class="container">
    <div class="row cards cards--4">
      <?php foreach ( get_latest_updates(array(), false, 8) as $index => $updated_item ) : ?>
        <?php if ($index % 4 === 0) echo '</div><div class="row cards cards--4">'; ?>
        <?php
          $label = is_array($updated_item['type']) ? $updated_item['type']['label'] : $updated_item['type'];
          $meta_label = '';
          switch ($label) {
            case 'dataset':
              $meta_label = __('Dataset', 'sixodp');
              break;
            case 'showcase':
              $meta_label = __('Showcase', 'sixodp');
              break;
            case 'post':
              $meta_label = __('Article', 'sixodp');
              break;
            case 'page':
              $meta_label = __('Page', 'sixodp');
              break;
            case 'comment':
              $meta_label = __('Comment', 'sixodp');
              break;
            default:
              $meta_label = $label;
              break;
          }

          $item = array(
            'external_card_class' => 'card-danger',
            'title' => get_translated($updated_item, 'title'),
            'meta' => $meta_label,
            'timestamp' => $updated_item['date_updated'],
            'notes' => get_translated($updated_item, 'notes'),
            'url' => $updated_item['link'],
          );
          include(locate_template( 'partials/card.php' ));
        ?>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="container">
    <div class="row btn-container">
      <a href="<?php echo get_permalink(get_translated_page_by_title('Viimeisimmät päivitykset')); ?>" class="btn btn-latest--inverse btn--sovellukset">
        <?php _e('More updates', 'sixodp');?>
      </a>
    </div>
  </div>

</div>
