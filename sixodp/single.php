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

    <?php get_template_part('partials/page-hero'); ?>

    <div class="page-content page-hero-content container">
      <?php
      // Start the loop.
      while ( have_posts() ) : the_post();
        global $post;
        $post_slug=$post->post_name;

        ?>
        <div class="row">
          <div class="sidebar col-md-3 col-sm-5 col-xs-12">
            <?php 
            $post_type = get_post_type_object(get_post_type());
            if ($post_type->name !== 'post' && $post_type->name !== 'page') :
            ?>
              <ul>
                <li class="sidebar-item--highlight">
                  <a href="<?php echo get_post_type_archive_link($post_type->name); ?>">
                    <i class="material-icons">bookmark</i>
                  <?php
                  if ($post_type->name === 'data_request') _e('Data Request');
                  else if ($post_type->name === 'showcase_idea') _e('Showcase Idea');
                  else echo $post_type->labels->singular_name; 
                  ?>
                </a></li>
              </ul>
            <?php 
            endif; 
            ?>

            <?php
              $categories = get_the_category();
              if (sizeof($categories) > 0) :
            ?>
              <ul>
                <li class="sidebar-item--highlight">
                  <span class="sidebar-item-inner">
                    <i class="material-icons">bookmark</i> <?php _e('Categories') ?>
                  </span>
                </li>
                <?php
                foreach ($categories as $cat):
                  ?>
                  <li class="sidebar-item">
                    <a href="<?php echo isset($cat->cat_ID) ? get_category_link($cat) : $cat->link ?>">
                      <?php echo $cat->cat_name; ?>
                      <span class="sidebar-icon-wrapper">
                        <span class="fa fa-chevron-right"></span>
                      </span>
                    </a>
                  </li>
                  <?php
                endforeach;
                ?>
              </ul>
            <?php
            endif;
            ?>

            <?php
            $tags = get_the_tags();
            if ($tags) :
            ?>
              <ul>
                <li class="sidebar-item--highlight">
                  <span class="sidebar-item-inner">
                    <i class="material-icons">label</i> <?php _e('Tags') ?>
                  </span>
                </li>
                <?php
                foreach ($tags as $tag):
                  ?>
                  <li class="sidebar-item">
                    <a href="<?php echo get_tag_link($tag); ?>">
                      <?php echo $tag->name; ?>
                      <span class="sidebar-icon-wrapper">
                        <span class="fa fa-chevron-right"></span>
                      </span>
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
                <li class="sidebar-item">
                  <span class="sidebar-item-inner">
                    <img src="<?php echo get_avatar_url(get_the_author_meta('id'), ['size' => 128]) ?>" class="avatar" />
                    <p class="text-center"><strong><?php echo trim(get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name')) ?></strong></p>
                    <p><?php echo wp_html_excerpt(get_the_author_meta('description'), 240, '...'); ?></p>
                  </span>
                </li>
              </ul>
            <?php endif; ?>
            <ul>
              <li class="sidebar-item">
                <span class="sidebar-item-inner">
                  <i class="material-icons">event</i> <?php the_date(); ?>
                </span>
              </li>
            </ul>
          </div>
          <div class="col-md-9 col-sm-7 col-xs-12 news-content">
            <h1 class="heading-content"><?php the_title() ?></h1>
            <article class="article"><?php the_content() ?></article>
            
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
