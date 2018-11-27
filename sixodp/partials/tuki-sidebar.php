<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar col-md-3 col-sm-12 col-xs-12">
  <h3 class="heading-sidebar"><?php _e('Tuki', 'sixodp'); ?></h3>
  <?php
    $categories=get_categories(array(
      'parent' => $parent_category->term_id,
      'hide_empty' => false,
      'exclude' => $themes_category->term_id
    ));

    if (count($categories) > 0) {
  ?>
    <?php foreach ( $categories as $cat ) : 
    $child_categories = get_categories(array('parent' => $cat->term_id, 'hide_empty' => false));
    ?>
    <ul>
      <li class="sidebar-item<?php if ($cat->cat_name === $category->name) { echo ' active'; } ?>">
        <a href="<?php echo get_category_link($cat); ?>">
          <span class="sidebar-icon-wrapper">
            <span class="fa fa-long-arrow-right"></span>
          </span>
          <?php echo $cat->cat_name; ?>
        </a>
      </li>
      <?php
      foreach ($child_categories as $child_cat) : 
      ?>
      <li class="sidebar-item child">
        <a href="<?php echo get_category_link($child_cat); ?>">
            <span class="sidebar-icon-wrapper">
              <span class="fa fa-long-arrow-right"></span>
            </span>
          <?php echo $child_cat->name; ?>  
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endforeach; ?>
  <?php } 

  $teemat = get_categories(array(
    'parent' => $themes_category->term_id,
    'hide_empty' => false
  ));
  ?>
  <h3 class="heading-sidebar"><?php _e('Themes', 'sixodp') ?></h3>
  <ul>
  <?php
  foreach ($teemat as $teema_cat) :?>
    <li class="sidebar-item<?php if ($teema_cat->name === $category->name) { echo ' active'; } ?>">
      <a href="<?php echo get_category_link($teema_cat); ?>">
        <span class="sidebar-icon-wrapper">
            <span class="fa fa-long-arrow-right"></span>
          </span>
          <?php echo $teema_cat->name; ?>
      </a>
    </li>
  <?php endforeach; ?>
  </ul>
</div>