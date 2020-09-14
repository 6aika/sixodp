<?php
/**
* Template partial for the main navigation
*
**/
?>
<nav class="navbar navbar-default" id="main-navbar" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'sixodp' ); ?>">

    <div class="container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-nav-collapse" aria-expanded="false">
        <span class="sr-only"><?php _e('Toggle navigation', 'sixodp');?> </span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="top-nav-collapse">
        <a class="sr-only sr-only-focusable" href="#maincontent">
            <?php _e( 'Skip to content', 'sixodp' ); ?></a>

        <ul class="nav navbar-nav">
        <?php
          foreach ( get_nav_menu_items("primary") as $navItem ) {
            if ( count($navItem["children"]) > 0 ) {
              $class = '';
              if ( $navItem["isActive"] ) {
                $class = ' active';
              }
              echo '<li class="has-subnav' . $class.'"><a href="'.$navItem["url"].'" title="'.$navItem["title"].'">'.$navItem["title"].'</a>
              <button class="subnav-toggle"><span class="sr-only">' . __('Show submenu for ', 'sixodp') .  $navItem['title'] . '</span><i class="fa fa-chevron-down"></i></button>
              <ul class="nav navbar-nav subnav">';
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
          <button class="btn btn-secondary navbar-search-btn" type="button" aria-label="<?php _e('Search', 'sixodp');?>"><span class="fa fa-search" aria-hidden="true"></span></button>
        </li>
          <li class="language-changer nav-inline"><a href="/fi" title=FI'" class="nav-link" aria-label="<?php _e('In Finnish', 'Sixodp') ?>">FI</a></li>
          <li class="language-changer nav-inline"><a href="/en_gb" title=EN'" class="nav-link" aria-label="<?php _e('In English', 'Sixodp') ?>">EN</a></li>
          <li class="language-changer nav-inline"><a href="/sv" title=SV'" class="nav-link" aria-label="<?php _e('In Swedish', 'Sixodp') ?>">SV</a></li>
      </ul>
      <div class="navbar-footer">
        <button type="button"
                class="navbar-toggle-footer collapsed"
                data-toggle="collapse"
                data-target="#top-nav-collapse"
                aria-expanded="false"
                aria-label="<?php _e('Search', 'sixodp');?>">
          <span class="fa fa-chevron-up"></span>
        </button>
      </div>
    </div>
  </div>
</nav><!-- .main-navigation -->
