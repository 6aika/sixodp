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
		<?php get_template_part('partials/page-hero'); ?>

    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li><a href="<?php echo home_url( $wp->request ) ?>"><?php _e('Support', 'sixodp') ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php _e('Support', 'sixodp') ?></h1>
      </div>
    </div>

        <div id="maincontent">
    <?php
      get_template_part( 'partials/tuki-contentbox' );
      $teema_category = get_translated_category_by_slug('teemat');
    ?>
    <div class="wrapper--morelinks">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h1 class="heading--themes"><?php _e('Themes', 'sixodp') ?></h1>
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
    <?php get_template_part( 'partials/tuki-contactbanner' ); ?>
        </div>
	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
