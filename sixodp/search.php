<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

 get_header(); ?>

 <div id="primary" class="content-area">
 	<main id="main" class="site-main site-main--search" role="main">
    <?php get_template_part('partials/page-hero'); ?>
    <div class="search-form-container">
      <div class="col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-12">
        <?php get_search_form(); ?>
      </div>
    </div>
    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo home_url( $wp->request ) ?>"><?php _e('Search', 'sixodp') ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php _e('Search from site', 'sixodp') ?></h1>
      </div>
    </div>

    <div id="maincontent" class="page-content container">
      <?php if(isset($_GET['datasearch'])) {
          get_template_part( 'partials/search-content-data' );
      } else {
          get_template_part( 'partials/search-content' );
      } ?>
    </div>
 	</main><!-- .site-main -->
 </div><!-- .content-area -->
 <?php get_footer(); ?>
