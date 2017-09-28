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
          <div class="col-md-3 sidebar">
            <?php 
            $post_type = get_post_type_object(get_post_type());
            if ($post_type->name !== 'post' && $post_type->name !== 'page') :
            ?>
              <ul>
                <li class="sidebar__item--heading"><a href="<?php echo get_post_type_archive_link($post_type->name); ?>"><i class="material-icons">bookmark</i> 
                  <?php
                  if ($post_type->name === 'data_request') _e('Data Request');
                  if ($post_type->name === 'showcase_idea') _e('Showcase Idea');
                  else echo $post_type->labels->singular_name; 
                  ?>
                </a></li>
              </ul>
            <?php 
            endif; 

            $categories = get_the_category();
            if (sizeof($categories) > 0) :
            ?>
              <ul>
                <li class="sidebar__item--heading"><i class="material-icons">bookmark</i> <?php _e('Categories') ?></li>
                <?php
                foreach ($categories as $cat):
                  ?>
                  <li class="sidebar__item">
                    <a href="<?php echo get_category_link($cat); ?>">
                      <i class="material-icons">settings</i>
                      <?php echo $cat->cat_name; ?>
                    </a>
                  </li>
                  <?php 
                endforeach;
                ?>
              </ul>
            <?php endif;

            $tags = get_the_tags();
            if (sizeof($tags) > 0) :
            ?>
              <ul>
                <li class="sidebar__item--heading"><i class="material-icons">label</i> <?php _e('Tags') ?></li>
                <?php
                foreach ($tags as $tag):
                  ?>
                  <li class="sidebar__item">
                    <a href="<?php echo get_tag_link($tag); ?>">
                      <i class="material-icons">settings</i>
                      <?php echo $tag->name; ?>
                    </a>
                  </li>
                  <?php 
                endforeach;
                ?>
              </ul>
            <?php endif; ?>

            <?php 
            $author = get_the_author();
            if (get_the_author() !== 'admin') : ?>
              <ul>
                <li class="sidebar__item">
                  <img src="<?php echo get_avatar_url(2, ['size' => 128]) ?>" class="avatar" />
                  <p><i class="material-icons">face</i> <?php the_author(); ?></p>
                  <p><?php echo get_the_author_meta('description'); ?></p>
                </li>
                <li class="sidebar__item">
                </li>
              </ul>
            <?php endif; ?>
            <ul>
              <li class="sidebar__item">
                <i class="material-icons">event</i> <?php the_date(); ?>
              </li>
            </ul>
          </div>
          <div class="col-md-9 news-content">
            <h1 class="heading--main"><?php the_title() ?></h1>
            <article class="article"><?php the_content() ?></article>

            <div class="addthis_toolbox">
              <a class="addthis_button_facebook_like at300b"></a>
              <a class="addthis_button_tweet at300b"></a>
            </div>
            
            <?php

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
              echo '<a name="comments"></a>';
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
