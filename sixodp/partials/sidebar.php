<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar col-md-3 col-sm-5 col-xs-12">
  <?php
    $pages= get_pages(array("parent" => $parent_page->ID));

    if (count($pages) > 0) {
  ?>
    <?php foreach ( $pages as $page ) : 
      $child_pages = get_pages(array('parent' => $page->ID, 'sort_column' => 'menu_order'));
    ?>
      <ul>
        <li class="sidebar-item--highlight<?php if (is_page($page->post_name)) echo ' active'; ?>">
          <a href="<?php echo get_permalink($page); ?>">
            <span class="sidebar-icon-wrapper">
              <span class="fa fa-long-arrow-right"></span>
            </span>
            <?php echo $page->post_title; ?>
          </a>
        </li>
        <?php
        foreach ($child_pages as $child_page) : 
        ?>
        <li class="sidebar-item child<?php if (is_page($child_page->post_name)) echo ' active'; ?>">
          <a href="<?php echo get_permalink($child_page); ?>">
            <span class="sidebar-icon-wrapper">
              <span class="fa fa-long-arrow-right"></span>
            </span>
            <?php echo $child_page->post_title; ?>  
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  <?php } ?>
</div>