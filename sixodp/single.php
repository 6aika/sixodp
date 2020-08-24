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

$categories = get_the_category();
$cat = '';
$cat_parent_id = '';
$cat_parent = '';
if ( ! empty( $categories ) ) {
  $cat = $categories[0];
  $cat_parent_id = get_category_grandparent_id($cat);
  $cat_parent = get_category($cat_parent_id);
}

$post_type = get_post_type_object(get_post_type());

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main wrapper" role="main">

    <?php get_template_part('partials/page-hero'); ?>
    <div class="toolbar-wrapper">
      <div class="toolbar">
        <div class="container">
            <ol class="breadcrumb">
            <li><a href="<?php echo get_home_url() ?>"><?php _e('Home', 'sixodp') ?></a></li>
            <?php if ($cat_parent != $category && $cat_parent->name != $cat->name) : ?>
              <li><a href="<?php echo get_category_link($cat_parent_id) ?>"><?php echo $cat_parent->name ?></a></li>
            <?php endif;?>
            <?php if($cat) : ?>
            <li><a href="<?php echo get_category_link($cat) ?>"><?php echo $cat->name ?></a></li>
            <?php endif; ?>
            <?php if ($post_type->name === 'data_request') : ?>
                <li><a href="<?php echo get_post_type_archive_link($post_type->name) ?>"><?php _e('Data Requests', 'sixodp'); ?></a></li>
            <?php endif; ?>
            <?php if ($post_type->name === 'showcase_idea') : ?>
                <li><a href="<?php echo get_post_type_archive_link($post_type->name) ?>"><?php _e('Showcase ideas', 'sixodp'); ?></a></li>
            <?php endif; ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          </ol>
        </div>
      </div>
      <div class="toolbar--site-subtitle">
        <h1><?php echo $cat->name; ?></h1>
      </div>
    </div>
    <div id="maincontent" class="page-content container">
      <?php
      // Start the loop.
      while ( have_posts() ) : the_post();
        global $post;
        $post_slug=$post->post_name;

        ?>
        <div class="row">
          <div class="sidebar col-md-3 col-sm-5 col-xs-12">
            <h2 class="heading-sidebar"><?php _e('Date', 'sixodp') ?></h2>
              <div class="sidebar-item">
                  <div class="sidebar-item-inner"><?php echo get_the_date('j.n.Y') ?></div>
              </div>
            <?php
            if ($post_type->name !== 'post' && $post_type->name !== 'page') : ?>
              <?php if ($post_type->name === 'data_request' or $post_type->name === 'showcase_idea'): ?>
                <h3 class="heading-sidebar"><?php _e('Categories', 'sixodp') ?></h3>
              <?php endif ?>
              <ul>
                <li class="sidebar-item">
                  <a href="<?php echo get_post_type_archive_link($post_type->name); ?>">
                    <span class="sidebar-icon-wrapper">
                      <span class="fa fa-long-arrow-right"></span>
                    </span>
                  <?php
                  if ($post_type->name === 'data_request') _e('Data Request', 'sixodp');
                  else if ($post_type->name === 'showcase_idea') _e('Showcase Idea', 'sixodp');
                  else echo $post_type->labels->singular_name; 
                  ?>
                </a></li>
              </ul>
            <?php endif; ?>

            <?php
              $categories = get_the_category();
              if (sizeof($categories) > 0) :
            ?>
              <h3 class="heading-sidebar"><?php _e('Categories', 'sixodp') ?></h3>
              <ul>
                <?php
                foreach ($categories as $cat):
                  ?>
                  <li class="sidebar-item">
                    <a href="<?php echo isset($cat->cat_ID) ? get_category_link($cat) : $cat->link ?>">
                      <span class="sidebar-icon-wrapper">
                        <span class="fa fa-long-arrow-right"></span>
                      </span>
                      <?php echo $cat->cat_name; ?>
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
              <h3 class="heading-sidebar"><?php _e('Tags', 'sixodp') ?></h3>
              <ul>
                <?php
                foreach ($tags as $tag):
                  ?>
                  <li class="sidebar-item">
                    <a href="<?php echo get_tag_link($tag); ?>">
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

            if (get_the_author() !== 'admin' && $post_type->name != 'data_request' && $post_type->name != 'showcase_idea') : ?>
              <h3 class="heading-sidebar"><?php _e('Author', 'sixodp') ?></h3>
              <ul>
                <li class="sidebar-item">
                  <span class="sidebar-item-author">
                    <?php echo get_avatar( get_the_author_meta('ID'), 128); ?>
                    <p class="text-center"><strong><?php echo trim(get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name')) ?></strong></p>
                    <p><?php echo wp_html_excerpt(get_the_author_meta('description'), 240, '...'); ?></p>
                  </span>
                </li>
              </ul>
            <?php endif; ?>
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
