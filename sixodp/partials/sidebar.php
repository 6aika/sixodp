<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar">
  <div class="sidebar__wrapper">
    <ul class="sidebar__list">
      <li class="sidebar__item--heading">
        <?php echo get_the_category()[0]->cat_name; ?>
      </li>
      <li class="sidebar__item">
        <a href="#" class="sidebar__link--active"><?php the_title(); ?></a>
      </li>
      <?php

        $product_page_args = array(
            'post_type' => 'page',
            'posts_per_page' => '-1',
            'child_of' => get_page_by_title('Tuki')->ID,
            'category_name' => get_the_category()[0]->cat_name,
            'post__not_in' => array($post->ID)
        );

        $my_wp_query = new WP_Query();
        $product_pages = $my_wp_query->query($product_page_args);

        foreach ($product_pages as $product_page){ ?>
          <li class="sidebar__item--heading">
            <a href="#" class="sidebar__link">
              <?php echo $product_page->post_title; ?>  
            </a>
          </li><?php
        }
        wp_reset_query();
      ?>
    </ul>

    <?php
      $categories=get_categories(array(
        'parent' => get_cat_ID('tuki'), 
        'exclude' => array(get_the_category()[0]->term_id)
      ));

      if (count($categories) > 0) {
    ?>
    <ul class="sidebar__list--heading">
      <?php foreach ( $categories as $cat ) : ?>
        <li class="sidebar__item--heading">
          <a href="<?php echo get_category_link($cat); ?>" class="sidebar__link--block">
            <i class="material-icons">settings</i>
            <?php echo $cat->cat_name; ?>
            <span class="sidebar__icon-wrapper">
              <i class="material-icons">arrow_forward</i>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php } ?>
  </div>
</div>