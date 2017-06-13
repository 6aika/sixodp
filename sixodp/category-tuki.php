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

get_header(); 

$category = get_queried_object();

$posts = get_posts(array('category' => $category->term_id, 'post_type' => 'page'));
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">
    <div class="wrapper">
      <div class="container">

        <div class="headingbar">
          <h1 class="heading--main">
            <?php _e('Support');?> 
            <i class="material-icons">navigate_next</i>
            <span style="font-size: 2.2rem;">
              <?php echo $category->name ?>
            </span>
          </h1>
        </div>

        <?php get_template_part( 'partials/tuki-sidebar' ); ?>

        <div class="article__wrapper">
          <ul class="items-list">
            <?php 
            foreach ( $posts as $post ) : setup_postdata( $post ) ?>

              <li class="items-list-content">
                <div class="items-list__content">
                  <h4 class="items-list__title">
                    <a class="items-list__link" href="<?php the_permalink(); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h4>
                  <div class="items-list__body">
                    <div class="metadata">
                        <span class="time">
                            <?php echo get_the_date();?>
                        </span>
                    </div>
                    <p class="items-list__info"><?php the_excerpt(); ?></p>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>
    </div>
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
