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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Roboto+Slab:300,400,600" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script type="text/javascript">
		var locale = '<?php echo get_current_locale() ?>';
		var locale_ckan = '<?php echo get_current_locale_ckan() ?>';
	</script>
</head>

<body <?php body_class(); ?> id="wordpress-indicator">
  <div id="mobile-indicator"></div>
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
  <div class="nav-wrapper">
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