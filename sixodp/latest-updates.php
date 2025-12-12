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

if ($_GET['date']) {
  $date = strtotime($_GET['date']);
}
else $date = strtotime("-1 month");

$date = date('d.m.Y', $date);

if ($_GET['types']) {
  $types = array(
    'datasets' => in_array('datasets', $_GET['types']),
    'showcases' => in_array('showcases', $_GET['types']),
    'posts' => in_array('posts', $_GET['types']),
    'pages' => in_array('pages', $_GET['types']),
    'data_requests' => in_array('data_requests', $_GET['types']),
    'app_requests' => in_array('app_requests', $_GET['types']),
  );
}
else {
  $types = array(
    'datasets' => true,
    'showcases' => true,
    'posts' => true,
    'pages' => true,
    'data_requests' => true,
    'app_requests' => true,
  );
}

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main site-main--home" role="main">

    <?php get_template_part('partials/page-hero'); ?>
    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li class="breadcrumb-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php the_title(); ?></h1>
      </div>
    </div>
      <form action="" method="GET" class="form-container text-center">
          <label>
              <input type="checkbox" value="datasets" name="types[]" <?php if ($types['datasets']) echo 'checked="checked"' ?> /> <?php _e('Datasets','sixodp') ?>
          </label>
          <label>
              <input type="checkbox" value="showcases" name="types[]" <?php if ($types['showcases']) echo 'checked="checked"' ?> /> <?php _e('Applications','sixodp') ?>
          </label>
          <label>
              <input type="checkbox" value="posts" name="types[]" <?php if ($types['posts']) echo 'checked="checked"' ?> /> <?php _e('Posts', 'sixodp') ?>
          </label>
          <label>
              <input type="checkbox" value="pages" name="types[]" <?php if ($types['pages']) echo 'checked="checked"' ?> /> <?php _e('Pages', 'sixodp') ?>
          </label>
          <label>
              <input type="checkbox" value="data_requests" name="types[]" <?php if ($types['data_requests']) echo 'checked="checked"' ?> /> <?php _e('Data Requests', 'sixodp') ?>
          </label>
          <label>
              <input type="checkbox" value="app_requests" name="types[]" <?php if ($types['app_requests']) echo 'checked="checked"' ?> /> <?php _e('App Requests', 'sixodp') ?>
          </label>
              <input type="submit" value="<?php _e('Update', 'sixodp') ?>" class="btn btn-transparent--inverse" />
      </form>
    <div id="maincontent" class="page-content container centered-content">

      <?php
      $updates = get_latest_updates($types, $date, false);
      if (sizeof($updates) == 0) { ?>
        <h2 class="heading-sidebar text-center"><?php echo sprintf(__('No updates found between %1$s and %2$s.', 'sixodp'), $date, date('d.m.Y', strtotime($date ."+1 month"))); ?></h2>
      <?php } else { ?>
          <h2 class="heading-sidebar text-center"><?php echo sprintf(__('Updates between %1$s and %2$s.', 'sixodp'), $date, date('d.m.Y', strtotime($date ."+1 month"))); ?></h2>
      <ul class="items-list">

      <?php foreach ( $updates as $index => $item ) :
        if ($item['link'] === get_permalink()) continue; // Don't show self

        ?>
        <li class="items-list-content">
          <div class="items-list__content">
            <div class="items-list__type">
              <?php
              if (is_array($item['type'])) {
                echo '<a href="'. $item['type']['link'] .'">'. __($item['type']['label'], 'sixodp') .'</a>';
              }
              else {
                echo '<span>'. __($item['type'], 'sixodp') .'</span>';
              }
              ?>
            </div>
            <h3 class="items-list__title">
              <a class="items-list__link" href="<?php echo $item['link']?>">
                <?php echo get_translated($item, 'title'); ?>
              </a>
            </h3>
            <div class="items-list__body">
              <div class="items-list__metadata">
                <div class="items-list__time">
                  <?php echo get_days_ago($item['date_updated'] ? $item['date_updated'] : $item['date']); ?>
                </div>
              </div>
              <p class="items-list__info"><?php echo get_notes_excerpt(strip_tags(get_translated($item, 'notes'))); ?></p>
            </div>
          </div>
        </li>
      <?php
      endforeach; ?>
      </ul>
      <?php } ?>

      <?php
        $args = $_GET;
        $uri = parse_url($_SERVER['REQUEST_URI']);
      ?>

      <div class="navigation pagination">
        <div class="nav-links">
          <?php echo '<a href="'. $uri['path'] .'?'. http_build_query(array_merge($args, array('date' => date('Y-m-d', strtotime($date .'- 1 MONTH'))))) .'" class="next page-numbers"><span class="fa fa-chevron-left" title="Edellinen"></span></a>'; ?>
          <?php if ($date != date('Y-m-d', strtotime('last monday'))) echo '<a href="'. $uri['path'] .'?'. http_build_query(array_merge($args, array('date' => date('Y-m-d', strtotime($date .'+ 1 MONTH'))))) .'" class="prev page-numbers"><span class="fa fa-chevron-right" title="Seuraava"></span></a>'; ?>
        </div>
      </div>
    </div>
   
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
