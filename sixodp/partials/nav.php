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
          foreach ( wp_get_nav_menu_items("primary_$pagename") as $navItem ) {
            echo '<li><a href="'.$navItem->url.'" title="'.$navItem->title.'">'.$navItem->title.'</a></li>';
          }
        ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php
          foreach(get_posts(array('post_type' => "page")) as $page) :
            $id = $page->post_title;
            $class = '';
            if ( $id === $pagename ) {
              $class = 'active';
            }
            echo '<li class="'.$class.'"><a href="/'.$id.'/" class="nav-link">'.$id.'</a></li>';
          endforeach;
        ?>
      </ul>
    </div>
  </div>
</nav><!-- .main-navigation -->