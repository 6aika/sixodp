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
  <link rel="stylesheet" href="/wp-content/themes/sixodp/app.css">
	<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
</head>

<body <?php body_class(); ?>>

<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'social' ) ) : ?>
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-nav-collapse" aria-expanded="false">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>

  <div id="site-header-menu" class="site-header-menu">
    <?php if ( has_nav_menu( 'primary' ) ) : ?>
      <nav id="site-navigation" class="main-navigation navbar" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
				<?php
					the_custom_logo();
          wp_nav_menu( array(
            'theme_location' => 'primary',
            'menu_class'     => 'primary-menu nav navbar-nav',
           ) );
        ?>
				<ul class="nav navbar-nav navbar-right">


<!-- Snippet home/snippets/sixodp_language-changer.html start -->


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


<!-- Snippet home/snippets/sixodp_language-changer.html end -->


</ul>
      </nav><!-- .main-navigation -->
    <?php endif; ?>
  </div><!-- .site-header-menu -->
<?php endif; ?>
