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
    $teema_category = get_translated_category_by_slug('teemat');
        ?>
        <div class="wrapper--morelinks">
          <div class="container">
            <div class="row">
              <div class="col-md-4">
                <h1 class="heading--themes"><?php _e('Themes') ?></h1>
              </div>
              <div class="col-md-8">
                <?php foreach (get_categories(array('parent' => $teema_category->term_id, 'hide_empty' => false)) as $category) : ?>
                  <div class="icon-link morelink">
                    <p class="icon-link__icon">
                      <a class="icon-link__link--round" href="<?php echo get_category_link($category->term_id); ?>">
                        <i class="material-icons">arrow_forward</i>
                      </a>
                    </p>
                    <p class="icon-link__text">
                      <a href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->name; ?></a>
                    </p>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
        <?php
		get_template_part( 'partials/tuki-contactbanner' );

		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
