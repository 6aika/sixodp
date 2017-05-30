<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Tuki
 *
 * @package WordPress
 * @subpackage Sixodp
 */


get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main site-main--home" role="main">
		<?php

		get_template_part('partials/header-logos');

		echo '<h1 class="heading--tuki">Tuki</h1>';
		get_template_part( 'partials/tuki-contentbox' );

		$morelinks_title  = "Teemat";
		$tuki_page = get_page_by_title('Tuki');
		$links = array_slice(array_filter(get_pages(['child_of' => $tuki_page->ID, 'sort_order' => 'asc', 'sort_column' => 'menu_order']), function ($page) use ($tuki_page) { 
			return $page->post_parent != $tuki_page->ID; 
		}), 0, 4);

		include(locate_template( 'partials/morelinks.php' ));

		get_template_part( 'partials/tuki-contactbanner' );

		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
