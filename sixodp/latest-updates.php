<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Viimeisimmät päivitykset
 *
 * @package WordPress
 * @subpackage Sixodp
 */

$types = array(
  'datasets' => true,
  'showcases' => true,
  'comments' => true,
  'data_requests' => true,
  'app_requests' => true,
);

if ($_GET['date']) {
  $date = strtotime($_GET['date']);

  $date = $date - 86400 * (date('N', $date) - 1);
}
else $date = strtotime("last monday");

$date = date('Y-m-d', $date);

if ($_GET['types']) {
  $types = array(
    'datasets' => in_array('datasets', $_GET['types']),
    'showcases' => in_array('showcases', $_GET['types']),
    'comments' => in_array('comments', $_GET['types']),
    'data_requests' => in_array('data_requests', $_GET['types']),
    'app_requests' => in_array('app_requests', $_GET['types']),
  );
}
else {
  $types = array(
    'datasets' => true,
    'showcases' => true,
    'comments' => true,
    'data_requests' => true,
    'app_requests' => true,
  );
}

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main site-main--home" role="main">
    <?php
      get_template_part('partials/header-logos');
    ?>

    <div class="container">
      <h1 class="heading--main"><?php _e('Latest updates', 'sixodp') ?></h1>
    </div>

    <div class="container">
      <form action="" method="GET">
        <input type="checkbox" value="datasets" name="types[]" <?php if ($types['datasets']) echo 'checked="checked"' ?> /> <?php _e('Datasets','sixodp') ?>
        <input type="checkbox" value="showcases" name="types[]" <?php if ($types['showcases']) echo 'checked="checked"' ?> /> <?php _e('Applications','sixodp') ?>
        <input type="checkbox" value="comments" name="types[]" <?php if ($types['comments']) echo 'checked="checked"' ?> /> <?php _e('Comments', 'sixodp') ?>
        <input type="checkbox" value="data_requests" name="types[]" <?php if ($types['data_requests']) echo 'checked="checked"' ?> /> <?php _e('Data Requests', 'sixodp') ?>
        <input type="checkbox" value="app_requests" name="types[]" <?php if ($types['app_requests']) echo 'checked="checked"' ?> /> <?php _e('App Requests', 'sixodp') ?>
        <input type="submit" value="Päivitä" class="btn btn-secondary" />
      </form>
    </div>
   
    <div class="container">
      <ul class="items-list">
      <?php
      $updates = get_latest_updates($types, $date);

      if (sizeof($updates) == 0) _e('No updates found for selected week.');
      foreach ( $updates as $index => $item ) : ?>
        <li class="items-list-content">
          <div class="items-list__content">
            <div class="items-list__type">
              <?php
              if (is_array($item['type'])) {
                echo '<a href="'. $item['type']['link'] .'">'. $item['type']['label'] .'</a>';
              }
              else {
                echo '<span>'. $item['type'] .'</span>';
              }
              ?>
            </div>
            <h4 class="items-list__title">
              <a class="items-list__link" href="<?php echo $item['link']?>">
                <?php echo get_translated($item, 'title'); ?>
              </a>
            </h4>
            <div class="items-list__body">
              <div class="items-list__metadata">
                <div class="items-list__time">
                  <?php echo get_days_ago($item['date_updated'] ? $item['date_updated'] : $item['date']); ?>
                </div>
              </div>
              <p class="items-list__info"><?php echo get_notes_excerpt(get_translated($item, 'notes')); ?></p>
            </div>
          </div>
        </li>
      <?php
      endforeach; ?>
      </ul>

      <?php
      $args = $_GET;
      $uri = parse_url($_SERVER['REQUEST_URI']);
      ?>

      <div class="paginate">
        <div class="paginate-prev">
          <?php echo '<a href="'. $uri['path'] .'?'. http_build_query(array_merge($args, array('date' => date('Y-m-d', strtotime($date .'- 1 WEEK'))))) .'">« '.__('Previous week') .'</a>'; ?>
        </div>
        <div class="paginate-next">
          <?php if ($date != date('Y-m-d', strtotime('last monday'))) echo '<a href="'. $uri['path'] .'?'. http_build_query(array_merge($args, array('date' => date('Y-m-d', strtotime($date .'+ 1 WEEK'))))) .'">'.__('Next week') .' »</a>'; ?>
        </div>
      </div>
    </div>
   
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
