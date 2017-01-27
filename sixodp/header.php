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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto+Slab" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body <?php body_class(); ?>>

<div class="notification">
	<p class="notification__text">
		<i class="material-icons">warning</i>
		<span class="bold">Käyttökatkos</span>
		Dataportaalia päivitetään uusimpaan versioon 24.12.2017 klo 23:00 alkaen. Käyttökatkoksen arvioitu kestoaika on 3 tuntia.
	</p>
</div>

<?php if ( has_nav_menu( 'primary' ) ) : ?>
  <nav class="navbar navbar-default" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
		<div class="container-fluid">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-nav-collapse" aria-expanded="false">
			    <span class="sr-only">Toggle navigation</span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			  </button>
      </div>

			<div class="collapse navbar-collapse" id="top-nav-collapse">
				<ul class="nav navbar-nav">
					<?php
						$menuLocations = get_nav_menu_locations();
						$menuID = $menuLocations['primary'];
						$primaryNav = wp_get_nav_menu_items($menuID);

						foreach ( $primaryNav as $navItem ) {
							echo '<li><a href="'.$navItem->url.'" title="'.$navItem->title.'">'.$navItem->title.'</a></li>';
						}
	        ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
					  <a href="/fi/" class="nav-link active">
					    <span class="hidden-xs">FI</span>
					    <span class="visible-xs">FI<span>
					  </span></span></a>
					</li><li>
					  <a href="/sv/" class="nav-link">
					    <span class="hidden-xs">SV</span>
					    <span class="visible-xs">SV<span>
					  </span></span></a>
					</li><li>
					  <a href="/en_GB/" class="nav-link">
					    <span class="hidden-xs">EN</span>
					    <span class="visible-xs">EN<span>
					  </span></span></a>
					</li>
				</ul>
			</div>
		</div>
  </nav><!-- .main-navigation -->
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
<?php endif; ?>
