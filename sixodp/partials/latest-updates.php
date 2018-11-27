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
    <?php $updated_items = get_latest_updates(array('comments' => false, 'data_requests' => true, 'showcase_ideas' => true), false, 8); ?>

    <div id="latest-updates-carousel" class="carousel slide mobile-only" data-ride="carousel" data-interval="false">
      <div class="carousel-inner" role="listbox">
        <?php foreach($updated_items as $updated_item) : ?>
          <?php $extra_classes = $updated_item === reset($updated_items) ? ' active' : ''; ?>

          <div class="item<?php echo $extra_classes ?>">
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
                case 'showcase_idea':
                  $meta_label = __('Showcase idea', 'sixodp');
                  break;
                case 'data_request':
                  $meta_label = __('Data request', 'sixodp');
                  break;
                default:
                  $meta_label = $label;
                  break;
              }

              $item = array(
                'external_card_class' => 'card-danger',
                'title' => get_translated($updated_item, 'title'),
                'meta' => $meta_label,
                'timestamp' => isset($updated_item['date_recent']) ? $updated_item['date_recent'] : $updated_item['date'],
                'notes' => get_translated($updated_item, 'notes'),
                'url' => $updated_item['link'],
              );
              include(locate_template( 'partials/card.php' ));
            ?>
          </div>
        <?php endforeach; ?>
      </div>

      <a class="left carousel-control" href="#latest-updates-carousel" role="button" data-slide="prev">
        <span class="fa fa-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#latest-updates-carousel" role="button" data-slide="next">
        <span class="fa fa-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

      <ol class="carousel-indicators">
        <?php foreach($updated_items as $index => $updated_item) : ?>
          <?php $extra_classes = $updated_item === reset($updated_items) ? 'active' : ''; ?>
          <li data-target="#latest-updates-carousel" data-slide-to="<?php echo $index ?>" class="<?php echo $extra_classes ?>"></li>
        <?php endforeach; ?>
      </ol>
    </div>

    <div class="row cards cards--4 desktop-only">
      <?php foreach ( $updated_items as $index => $updated_item ) : ?>
        <?php if ($index % 4 === 0) echo '</div><div class="row cards cards--4 desktop-only">'; ?>
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
            case 'showcase_idea':
              $meta_label = __('Showcase idea', 'sixodp');
              break;
            case 'data_request':
              $meta_label = __('Data request', 'sixodp');
              break;
            default:
              $meta_label = $label;
              break;
          }

          $item = array(
            'external_card_class' => 'card-danger',
            'title' => get_translated($updated_item, 'title'),
            'meta' => $meta_label,
            'timestamp' => isset($updated_item['date_recent']) ? $updated_item['date_recent'] : $updated_item['date'],
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
      <a href="<?php echo get_permalink(get_translated_page_by_slug('viimeisimmat-paivitykset')); ?>" class="btn btn-latest--inverse btn--sovellukset">
        <?php _e('More updates', 'sixodp');?>
      </a>
    </div>
  </div>

</div>
