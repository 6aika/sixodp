<?php
  /**
  * More links template
  */
?>

<div class="col-md-9 col-sm-12 col-xs-12 news-content">
  <article class="article" role="article">
    <?php the_content(); ?>
  </article>
  <div class="article__footer">
    <div class="author">
      <div class="author__img">
        <?php echo get_avatar( get_the_author_meta('user_email'), $size = '50'); ?>
      </div>
      <div class="author__body">
        <p class="author__text"><?php _e('Author', 'sixodp');?></p>
        <p class="author__name">
          <?php the_author(); ?>
        </p>
        <p class="post__meta">
          <?php the_date(); ?>
        </p>
      </div>
    </div>
  </div>
  <?php
    if (is_page_template(get_page_template_slug($post))) :
        $morelinks_title = "Lisää aiheesta";

        $args = array(
          'cat' => array_map(function($category) { return $category->term_id; }, get_the_category()),
          'post_type' => 'page',
          'exclude' => get_the_id(),
          'posts_per_page' => 4
        );

        $links = get_posts($args);

        include(locate_template( 'partials/morelinks.php' ));
    endif;

    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
      comments_template();
    endif;
  ?>
</div>
