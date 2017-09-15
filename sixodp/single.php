<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Sixodp
 */

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main wrapper" role="main">
    
    <?php
      get_template_part('partials/header-logos');
    ?>

    <div class="page__hero"></div>
    <div class="page__content container">
      <?php
      // Start the loop.
      while ( have_posts() ) : the_post();
        global $post;
        $post_slug=$post->post_name;

        ?>
        <div class="row">
          <div class="col-md-4 sidebar">
            <div class="sidebar__wrapper">
              <ul class="sidebar__list--heading">
                <?php
                foreach (get_the_category() as $cat):
                  ?>
                  <li class="sidebar__item--heading">
                    <a href="<?php echo get_category_link($cat); ?>" class="sidebar__link--block">
                      <i class="material-icons">settings</i>
                      <?php echo $cat->cat_name; ?>
                      <span class="sidebar__icon-wrapper">
                        <i class="material-icons">arrow_forward</i>
                      </span>
                    </a>
                  </li>
                  <?php 
                endforeach;
                ?>
                <?php 
                $author = get_the_author();

                if (get_the_author() !== 'admin') {
                  ?>
                  <li class="sidebar__item--heading">
                    <?php the_author(); ?>
                  </li>
                  <?php
                }
                ?>
                <li class="sidebar__item--heading">
                  <?php the_date(); ?>
                </li>
              </ul>
              
              <div class="addthis_toolbox">
                <a class="addthis_button_facebook_like at300b"></a>
                <a class="addthis_button_tweet at300b"></a>
              </div>
            </div>        
          </div>
          <div class="col-md-8 news-content">
            <h1 class="heading--main"><?php the_title() ?></h1>
            <article class="article"><?php the_content() ?></article>
            <?php

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;
            ?>
          </div>
        </div>
        <?php
        // End of the loop.
      endwhile;
      ?>

    </div>

  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
