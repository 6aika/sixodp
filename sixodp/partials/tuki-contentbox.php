<?php
  /**
  * Featured content box on Tuki frontpage.
  */
?>

<?php $bg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'page')[0]; ?>

<div class="wrapper--contentbox" style="background-image: url(<?php echo $bg; ?>);">
  <div class="wrapper--inner-contentbox">
    <div class="container">
      <div class="row"><?php
        $tuki_category = get_translated_category_by_slug('tuki');
        $teema_category = get_translated_category_by_slug('teemat');
        foreach ( get_categories(array('parent' => $tuki_category->term_id, 'exclude' => $teema_category->term_id, 'hide_empty' => false)) as $category ) : ?>
          <div class="col-md-4 contentbox">
            <h1 class="heading-content">
              <a class="contentbox__link" href="<?php echo get_category_link($category->term_id);; ?>">
                <?php echo $category->name; ?>
              </a>
            </h1>
            <?php
            $thumb = '';
            if (function_exists('category_image_src')){
                $thumb = category_image_src(array(
                    'term_id' => $category->term_id
                ));
            }
             ?>
            <div class="contentbox__img-wrapper">
              <img src="<?php echo $thumb; ?>" alt="thumb">
            </div>
            <div class="contentbox__body icon-link">
              <p class="icon-link__text">
                <a class="contentbox__link" href="<?php echo get_category_link($category->term_id); ?>">
                  <?php echo $category->description; ?>
                </a>
              </p>
              <p class="icon-link__icon">
                <a class="icon-link__link--round" href="<?php echo get_category_link($category->term_id); ?>">
                  <i class="material-icons">arrow_forward</i>
                </a>
              </p>
            </div>    
          </div><?php
        endforeach; ?>
      </div>
    </div>
  </div>
</div>