<?php
/**
* Template partial for the main navigation
*
**/
?>
<nav class="navbar navbar-default" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
  <div class="container-fluid">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-nav-collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="top-nav-collapse">
      <ul class="nav navbar-nav">
        <?php
          foreach ( get_nav_menu_items("primary") as $navItem ) {
            //var_dump($navItem);
            $children = get_pages( array( 'child_of' => $navItem->object_id ) );
            //var_dump(count($children));
            if ( count($children) > 0 ) {
              var_dump($children[0]);
              $sub_nav_item = $children[0];
              echo '<li><a href="'.$navItem->url.'" title="'.$navItem->title.'">'.$navItem->title.'</a><ul class="nav navbar-nav subnav">';
              echo '<li><a href="'.$sub_nav_item->url.'" title="'.$sub_nav_item->title.'">'.$sub_nav_item->title.'</a></li></ul></li>';
            } else {
              echo '<li><a href="'.$navItem->url.'" title="'.$navItem->title.'">'.$navItem->title.'</a></li>';
            }
          }
        ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php
          foreach ( get_nav_menu_items("secondary") as $navItem ) {
            $class = '';
            if ( $navItem->title === get_current_locale() ) {
              $class = 'active';
            }
            echo '<li class="'.$class.'"><a href="'.$navItem->url.'" title="'.$navItem->title.'" class="nav-link">'.$navItem->title.'</a></li>';
          }
        ?>
      </ul>
    </div>
  </div>
</nav><!-- .main-navigation -->