<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar-links col-sm-4">
  <?php
    $pages= get_pages(array("parent" => $parent_page->ID));

    if (count($pages) > 0) {
  ?>
    <?php foreach ( $pages as $page ) : 
    $child_pages = get_pages(array('parent' => $page->ID));
    ?>
      <ul>
        <li class="sidebar__item--heading">
          <a href="<?php echo get_permalink($page); ?>">
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
      </ul>
    <?php endforeach; ?>
  <?php } ?>
</div>