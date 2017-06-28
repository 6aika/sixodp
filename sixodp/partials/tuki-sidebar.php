<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar">
  <div class="sidebar__wrapper">
    <?php
      $categories=get_categories(array(
        'parent' => $parent_category->term_id,
        'hide_empty' => false,
        'exclude' => $themes_category->term_id
      ));

      if (count($categories) > 0) {
    ?>
    <ul class="sidebar__list--heading">
      <?php foreach ( $categories as $cat ) : 
      $child_categories = get_categories(array('parent' => $cat->term_id, 'hide_empty' => false));
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
        foreach ($child_categories as $child_cat) : 
        ?>
        <li class="sidebar__item">
          <a href="<?php echo get_category_link($child_cat); ?>" class="sidebar__link">
            <?php echo $child_cat->name; ?>  
          </a>
        </li>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </ul>
    <?php } 

    $teemat = get_categories(array(
      'parent' => $themes_category->term_id,
      'hide_empty' => false
    ));
    ?>
    <h2 class="module-heading"><?php _e('Themes') ?></h2>
    <ul class="sidebar__list">
    <?php
    foreach ($teemat as $teema_cat) :?>
      <li class="sidebar__item">
        <a href="<?php echo get_category_link($teema_cat); ?>" class="sidebar__link">
          <?php echo $teema_cat->name; ?>  
        </a>
      </li>
    <?php endforeach; ?>
    </ul>
  </div>
</div>