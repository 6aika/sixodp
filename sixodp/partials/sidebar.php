<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar">
  <div class="sidebar__wrapper">
    <?php
      $pages= get_pages(array("parent" => $parent_page->ID));

      if (count($pages) > 0) {
    ?>
    <ul class="sidebar__list--heading">
      <?php foreach ( $pages as $page ) : 
      $child_pages = get_pages(array('parent' => $page->ID));
      ?>
        <li class="sidebar__item--heading">
          <a href="<?php echo get_permalink($page); ?>" class="sidebar__link--block">
            <i class="material-icons">settings</i>
            <?php echo $page->post_title; ?>
            <span class="sidebar__icon-wrapper">
              <i class="material-icons">arrow_forward</i>
            </span>
          </a>
        </li>
        <?php
        foreach ($child_pages as $child_page) : 
        ?>
        <li class="sidebar__item">
          <a href="<?php echo get_permalink($child_page); ?>" class="sidebar__link">
            <?php echo $child_page->post_title; ?>  
          </a>
        </li>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </ul>
    <?php } ?>
  </div>
</div>