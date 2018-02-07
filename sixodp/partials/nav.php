<?php
/**
* Template partial for the main navigation
*
**/
?>
<nav class="navbar navbar-default" id="main-navbar" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'sixodp' ); ?>">
  <div class="container-fluid">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-nav-collapse" aria-expanded="false">
        <span class="sr-only"><?php _e('Toggle navigation', 'sixodp');?> </span>
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
                $class = ' active';
              }
              echo '<li class="has-subnav' . $class.'"><a href="'.$navItem["url"].'" title="'.$navItem["title"].'">'.$navItem["title"].'</a>
              <span class="subnav-toggle"><i class="fa fa-chevron-down"></i></span><ul class="nav navbar-nav subnav">';
              foreach ($navItem["children"] as $sub_nav_item) {
                $class = '';
                if ( isset($sub_nav_item["isActive"]) and $sub_nav_item["isActive"] ) {
                  $class = 'active';
                }
                echo '<li class="'.$class.'"><a href="'.$sub_nav_item["url"].'" title="'.$sub_nav_item["title"].'"><span class="fa fa-long-arrow-right"></span>'.$sub_nav_item["title"].'</a></li>';
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
        <li class="navbar-search">
          <div class="input-group navbar-search-form">
            <span class="input-group-btn">
              <button class="btn btn-secondary navbar-search-submit-btn" type="button"><span class="fa fa-search" aria-hidden="true"></span></button>
            </span>
            <input type="text" class="form-control navbar-search-input" id="navbar-search-q" aria-label="...">
          </div>
          <button class="btn btn-secondary navbar-search-btn" type="button"><span class="fa fa-search" aria-hidden="true"></span></button>
        </li>
        <?php
          foreach ( get_nav_menu_items("secondary") as $navItem ) {
            echo '<li class="language-changer nav-inline"><a href="'.$navItem["url"].'" title="'.$navItem["title"].'" class="nav-link">'.$navItem["title"].'</a></li>';
          }
        ?>
      </ul>
      <div class="navbar-footer">
        <button type="button"
                class="navbar-toggle-footer collapsed"
                data-toggle="collapse"
                data-target="#top-nav-collapse"
                aria-expanded="false">
          <span class="fa fa-chevron-up"></span>
        </button>
      </div>
    </div>
  </div>
</nav><!-- .main-navigation -->
