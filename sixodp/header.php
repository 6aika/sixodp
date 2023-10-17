<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
	<script src="https://use.fontawesome.com/4f2a9d3d3d.js"></script>
	<link rel="stylesheet" href="<?php echo site_url(); ?>/wp-content/themes/sixodp/app.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script type="text/javascript">
		var locale = '<?php echo get_current_locale() ?>';
		var locale_ckan = '<?php echo get_current_locale_ckan() ?>';
	</script>

    <?php include('matomo-config.php'); ?>
    <?php include('cookiehub-config.php'); ?>
</head>

<body <?php body_class(); ?> id="wordpress-indicator">
  <div id="mobile-indicator"></div>
  <?php $notifications = get_posts(array('post_type' => 'notification')); ?>
  <?php $extra_classes = count($notifications) > 0 ? ' has-notification' : '' ?>
  <div class="nav-wrapper<?php echo $extra_classes ?>">

      <?php $notifications = get_posts(array('post_type' => 'notification')); ?>
    <?php if ( count($notifications) > 0 && ($notifications[0]->post_title !== '' || $notifications[0]->post_content !== '') ) : ?>
      <?php
        $type = get_post_meta( $notifications[0]->ID, 'type', true );
        // Check if the custom field has a value.
        $notificationclass = '';
        if ( ! empty( $type ) ) {
            $notificationclass = $type;
        }
      ?>

      <div class="notification <?php echo $notificationclass; ?>">
        <p class="notification-content">
          <i class="fa fa-exclamation-circle"></i>
          <?php foreach (get_posts(array('post_type' => 'notification')) as $notification) : ?>
          <span class="bold"><?php echo $notification->post_title; ?></span>
            <?php echo $notification->post_content; ?>
          <?php endforeach; ?>
        </p>
      </div>
    <?php endif; ?>

    <?php require_once('partials/nav.php'); ?>
  </div>
  <div class="logos" role="banner">
      <img src="<?php echo assets_url(); ?>/images/hero_logo.png" alt="<?php _e('Home', 'sixodp') ?>" />
    <div class="logos--eu">
      <div class="logo--erdf">
        <img src="<?php echo assets_url(); ?>/images/EU_ERDF_FI.png" alt="European Regional Development Fund logo">
      </div>
      <div class="logo--eu">
        <img src="<?php echo assets_url(); ?>/images/LeverageEU_FI.png" alt="Leverage with EU logo">
      </div>
      <div class="flag-ends"></div>
    </div>
  </div>