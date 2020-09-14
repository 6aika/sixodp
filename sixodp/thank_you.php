<?php
/**
 * The template for displaying thank you
 *
 *
 * Template Name: Kiitos
 *
 * @package WordPress
 * @subpackage Sixodp
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main wrapper" role="main">

        <?php get_template_part('partials/page-hero'); ?>

        <div id="maincontent" class="page-content container">
            <div class="article"><?php the_content(); ?></div>
        </div>

    </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
