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
    <link rel="stylesheet" href="<?php echo site_url(); ?>/wp-content/themes/sixodp/app.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Roboto+Slab:300,400,600" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
	<?php $notifications = get_posts(array('post_type' => 'notification')); ?>
	<?php if ( count($notifications) > 0 && ($notifications[0]->post_title !== '' || $notifications[0]->post_content !== '') ) : ?>
	<div class="notification">
		<p class="notification__text">
			<i class="material-icons">warning</i>
			<?php foreach (get_posts(array('post_type' => 'notification')) as $notification) : ?>
			<span class="bold"><?php echo $notification->post_title; ?></span>
				<?php echo $notification->post_content; ?>
			<?php endforeach; ?>
		</p>
	</div>
	<?php endif; ?>


	<?php require_once('partials/nav.php'); ?>

	<div class="logos">
		<a href="<?php echo site_url(); ?>" class="logo--brand">
			<img src="<?php echo assets_url(); ?>/images/main_logo.png" />
		</a>
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
