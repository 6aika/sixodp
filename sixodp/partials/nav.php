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
            if ( count($navItem["children"]) > 0 ) {
              $class = '';
              if ( $navItem["isActive"] ) {
                $class = 'active';
              }
              echo '<li class="'.$class.'"><a href="'.$navItem["url"].'" title="'.$navItem["title"].'">'.$navItem["title"].'</a><ul class="nav navbar-nav subnav">';
              foreach ($navItem["children"] as $sub_nav_item) {
                $class = '';
                if ( $sub_nav_item["isActive"] ) {
                  $class = 'active';
                }
                echo '<li class="'.$class.'"><a href="'.$sub_nav_item["url"].'" title="'.$sub_nav_item["title"].'">'.$sub_nav_item["title"].'</a></li>';
              }
              echo '</ul></li>';
            } else {
              $class = '';
              if ( $navItem["isActive"] ) {
                $class = 'active';
              }
              echo '<li class="'.$class.'"><a href="'.$navItem["url"].'" title="'.$navItem["title"].'">'.$navItem["title"].'</a></li>';
            }
          }
        ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php
          foreach ( get_nav_menu_items("secondary") as $navItem ) {
            $class = '';
            if ( $navItem["title"] === get_current_locale() ) {
              $class = 'active';
            }
            echo '<li class="'.$class.'"><a href="'.$navItem["url"].'" title="'.$navItem["title"].'" class="nav-link">'.$navItem["title"].'</a></li>';
          }
        ?>
      </ul>
    </div>
  </div>
</nav><!-- .main-navigation -->