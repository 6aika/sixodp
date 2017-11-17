<?php
  /**
  * Page content partial.
  */
?>

<div class="page-breadcrumb-wrapper">
  <ul class="page-breadcrumb">
    <li>Ajankohtaista</li>
    <li>Blogit</li>
  </ul>
</div>

<div class="page-body">
  <h1 class="heading-main"><?php the_title(); ?></h1>
  <div class="article"><?php the_content(); ?></div>
  <div class="article__footer">
    <div class="author">
      <div class="author__img">
        <?php echo get_avatar( get_the_author_meta('user_email'), $size = '50'); ?>
      </div>
      <div class="author__body">
        <p class="author__text"><?php _e('Author', 'sixodp');?> </p>
        <p class="author__name">
          <?php the_author(); ?>
        </p>
        <p class="post__meta">
          <?php the_date(); ?>
        </p>
      </div>
    </div>
  </div>
  <div class="article__tags">
    <?php
      if(get_the_tag_list()) {
        echo get_the_tag_list('<ul><li>','</li><li>','</li></ul>');
      }
    ?>
  </div>
</div>
