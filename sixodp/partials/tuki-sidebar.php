<?php
  /**
  * Sidebar template
  */
?>

<div class="sidebar col-md-3 col-sm-5 col-xs-12">
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
      <li class="sidebar-item--highlight">
        <a href="<?php echo get_category_link($cat); ?>">
          <?php echo $cat->cat_name; ?>
          <span class="sidebar-icon-wrapper">
            <span class="fa fa-chevron-right"></span>
          </span>
        </a>
      </li>
      <?php
      foreach ($child_categories as $child_cat) : 
      ?>
      <li class="sidebar-item">
        <a href="<?php echo get_category_link($child_cat); ?>">
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
  <h2 class="module-heading"><?php _e('Themes') ?></h2>
  <ul>
  <?php
  foreach ($teemat as $teema_cat) :?>
    <li class="sidebar-item">
      <a href="<?php echo get_category_link($teema_cat); ?>">
        <?php echo $teema_cat->name; ?>
        <span class="sidebar-icon-wrapper">
          <span class="fa fa-chevron-right"></span>
        </span>
      </a>
    </li>
  <?php endforeach; ?>
  </ul>
</div>