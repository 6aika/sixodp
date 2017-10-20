<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar col-sm-3">
  <?php
    $pages= get_pages(array("parent" => $parent_page->ID));

    if (count($pages) > 0) {
  ?>
    <?php foreach ( $pages as $page ) : 
    $child_pages = get_pages(array('parent' => $page->ID));
    ?>
      <ul>
        <li class="sidebar-item--highlight">
          <a href="<?php echo get_permalink($page); ?>">
            <?php echo $page->post_title; ?>
          </a>
        </li>
        <?php
        foreach ($child_pages as $child_page) : 
        ?>
        <li class="sidebar-item">
          <a href="<?php echo get_permalink($child_page); ?>">
            <?php echo $child_page->post_title; ?>  
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  <?php } ?>
</div>