<?php
  /**
  * More links template
  */
?>

<div class="col-md-9 col-sm-12 col-xs-12 news-content">
  <h1 class="heading-content"><?php the_title(); ?></h1>
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
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
      comments_template();
    endif;
  ?>
</div>
