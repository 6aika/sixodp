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

if ($_POST['submit']) {
  $types = array(
    'datasets' => $_POST['datasets'] == 'on',
    'showcases' => $_POST['showcases'] == 'on',
    'comments' => $_POST['comments'] == 'on',
    'data_requests' => $_POST['data_requests'] == 'on',
    'app_requests' => $_POST['app_requests'] == 'on',
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

print_r($types);

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
      <form action="" method="POST">
        <input type="checkbox" name="datasets" value="on" <?php if ($types['datasets']) echo 'checked="checked"' ?> /> <?php _e('Datasets','sixodp') ?>
        <input type="checkbox" name="showcases" value="on" <?php if ($types['showcases']) echo 'checked="checked"' ?> /> <?php _e('Applications','sixodp') ?>
        <input type="checkbox" name="comments" value="on" <?php if ($types['comments']) echo 'checked="checked"' ?> /> <?php _e('Comments', 'sixodp') ?>
        <input type="checkbox" name="data_requests" value="on" <?php if ($types['data_requests']) echo 'checked="checked"' ?> /> <?php _e('Data Requests', 'sixodp') ?>
        <input type="checkbox" name="app_requests" value="on" <?php if ($types['app_requests']) echo 'checked="checked"' ?> /> <?php _e('App Requests', 'sixodp') ?>
        <input type="submit" value="Päivitä" name="submit" />
      </form>
    </div>
   
    <div class="container">
      <ul class="items-list">
      <?php
      foreach ( get_latest_updates($types) as $index => $item ) : ?>
        <li class="items-list-content">
          <div class="items-list__content">
            <h4 class="items-list__title">
              <a class="items-list__link" href="<?php echo $item['link']?>">
                <?php echo get_translated($item, 'title'); ?>
              </a>
            </h4>
            <div class="items-list__body">
              <div class="metadata">
                <span class="time">
                  <?php echo get_days_ago($item['date_updated'] ? $item['date_updated'] : $item['date']); ?>
                </span>
              </div>
              <p class="items-list__info"><?php echo get_notes_excerpt(get_translated($item, 'notes')); ?></p>
            </div>
          </div>
        </li>
      <?php
      endforeach; ?>
      </ul>

    </div>
   
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
