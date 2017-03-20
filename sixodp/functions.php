<?php

if ( !function_exists('sixodp_theme_setup') ) :

  function sixodp_theme_setup() {

    /**
    * Include theme configuration constants
    */
    include('theme-config.php');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for custom logo.
     *
     *  @since Twenty Sixteen 1.2
     */
    add_theme_support( 'custom-logo', array(
      'height'      => 40,
      'width'       => 96,
      'flex-height' => true,
    ) );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 1200, 9999 );

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus( array(
      'primary' => __( 'Primary Menu', 'sixodp' ),
      'secondary' => __( 'Secondary Menu', 'sixodp' ),
      'social'  => __( 'Social Links Menu', 'sixodp' ),
      'footer'  => __( 'Footer Menu', 'sixodp' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ) );

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     */
    add_theme_support( 'post-formats', array(
      'aside',
      'image',
      'video',
      'quote',
      'link',
      'gallery',
      'status',
      'audio',
      'chat',
    ) );

    create_primary_menus();
    create_default_pages();
    create_secondary_menus();
    
  }
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'sixodp_theme_setup' );

function register_notifications() {
 
   //labels array added inside the function and precedes args array
 
   $labels = array(
    'name'               => _x( 'Notifications', 'post type general name' ),
    'singular_name'      => _x( 'Notification', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Notification' ),
    'add_new_item'       => __( 'Add New Notification' ),
    'edit_item'          => __( 'Edit Notification' ),
    'new_item'           => __( 'New Notification' ),
    'all_items'          => __( 'All Notifications' ),
    'view_item'          => __( 'View Notification' ),
    'search_items'       => __( 'Search notifications' ),
    'not_found'          => __( 'No notifications found' ),
    'not_found_in_trash' => __( 'No notifications found in the Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Notifications'
  );
 
         // args array
 
   $args = array(
    'labels'        => $labels,
    'description'   => 'Notifications',
    'public'        => true,
    'supports'      => array( 'title', 'editor' ),
    'has_archive'   => false,
    'show_in_rest'  => true
  );
 
  register_post_type( 'notification', $args );
}
add_action( 'init', 'register_notifications' );

function create_primary_menus() {
  create_menu_i18n('primary_fi', PRIMARY_MENU_ITEMS_FI, 'primary');
  create_menu_i18n('primary_en', PRIMARY_MENU_ITEMS_EN, 'primary');
  create_menu_i18n('primary_sv', PRIMARY_MENU_ITEMS_SV, 'primary');
}

function create_secondary_menus() {
  create_menu_i18n('secondary_fi', SECONDARY_MENU_ITEMS_FI, 'secondary');
  create_menu_i18n('secondary_en', SECONDARY_MENU_ITEMS_EN, 'secondary');
  create_menu_i18n('secondary_sv', SECONDARY_MENU_ITEMS_SV, 'secondary');
}

function create_menu_i18n($menu_name, $itemsArr, $location) {

  // If it doesn't exist, let's create it.
  if( !wp_get_nav_menu_object( $menu_name )){
      $menu_id = wp_create_nav_menu($menu_name);

      foreach($itemsArr as $item) {
        wp_update_nav_menu_item($menu_id, 0, array(
          'menu-item-title' =>  $item['menu-item-title'],
          'menu-item-url' => home_url( $item['menu-item-url'] ),
          'menu-item-status' => 'publish'));
      }
    //then you set the wanted theme  location
    $menu = get_term_by( 'name', $menu_name, 'nav_menu' );
    $locations = get_theme_mod('nav_menu_locations');
    $locations[$location] = $menu->term_id;
    set_theme_mod( 'nav_menu_locations', $locations );
  }
}

function create_default_pages() {
  foreach( ['fi', 'en', 'sv'] as $locale ) {
    insert_default_page($locale);
  }
}

function insert_default_page($locale) {
  $page_exists = get_page_by_title($locale);
  if ( !isset( $page_exists ) ) {
    $page = array(
      'post_title'    => $locale,
      'post_content'  => "This is my post",
      'post_type'     => 'page',
      'post_status'   => 'publish',
      'page_template' => 'home.php'
    );

    $page_id = wp_insert_post( $page );

    $roadmap_page = array(
      'post_title'    => 'Roadmap',
      'post_content'  => "This is my post",
      'post_type'     => 'page',
      'post_parent'   => $page_id,
      'post_status'   => 'publish',
      'page_template' => 'roadmap.php'
    );

    $roadmap_page = wp_insert_post( $roadmap_page );
  }
}

function sixodp_scripts() {
    wp_enqueue_script( 'app', get_template_directory_uri() . '/app.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'sixodp_scripts' );


function get_menu_items($page_name) {
  $menuLocations = get_nav_menu_locations();
  $menuID = $menuLocations["primary"];
  return wp_get_nav_menu_items($menuID);
}

function get_nav_menu_items($menu) {
  return wp_get_menu_array($menu . '_' . get_current_locale());
}

function wp_get_menu_array($current_menu) {
 
    $array_menu = wp_get_nav_menu_items($current_menu);
    $menu = array();
    foreach ($array_menu as $m) {
        if (empty($m->menu_item_parent)) {
            $menu[$m->ID] = array();
            $menu[$m->ID]['ID']      =   $m->ID;
            $menu[$m->ID]['title']       =   $m->title;
            $menu[$m->ID]['url']         =   $m->url;
            $menu[$m->ID]['children']    =   array();
        }
    }
    $submenu = array();
    foreach ($array_menu as $m) {
        if ($m->menu_item_parent) {
            $submenu[$m->ID] = array();
            $submenu[$m->ID]['ID']       =   $m->ID;
            $submenu[$m->ID]['title']    =   $m->title;
            $submenu[$m->ID]['url']  =   $m->url;
            $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
        }
    }
    return $menu;
     
}

function is_active_menu_item($menu_item) {
  return ('https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) == ($menu_item["url"].'/');
}

function get_current_locale() {
  $path = explode('/', $_SERVER['REQUEST_URI']);
  return $path[1];
}

function get_ckan_data($url) {
  return json_decode(file_get_contents($url), TRUE);
}

function get_popular_tags() {
  $data = get_ckan_data(CKAN_API_URL.'/action/package_search?facet.field=["tags"]&facet.limit=5');
  return $data['result']['facets']['tags'];
}

function get_recent_content() {
  $data = get_ckan_data(CKAN_API_URL.'/action/package_search?sort=date_released%20desc&rows=5');
  return $data['result']['results'];
}

function get_datasets() {
  $data = get_ckan_data(CKAN_API_URL.'/action/package_search');
  return $data['result'];
}

function get_dataset_count() {
  return get_datasets()['count'];
}

function get_showcases_count() {
  return get_count('ckanext_showcase');
}

function get_organizations_count() {
  return get_count('organization');
}

function get_count($type) {
  $data = get_ckan_data(CKAN_API_URL."/action/".$type."_list");
  return count($data['result']);
}

function get_ckan_categories() {
  $data = get_ckan_data(CKAN_API_URL.'/action/group_list?all_fields=true');
  return $data['result'];
}

/*
* Returns package rating 
*
* Example data below
*
*  {
*    help: "https://generic-qa.dataportaali.com/data/api/3/action/help_show?name=rating_showcase_get",
*    success: true,
*    result: {
*      rating: 0,
*      ratings_count: 0
*    }
*  }
*/
function get_ckan_package_rating($package_id) {
  $data = get_ckan_data(CKAN_API_URL.'/action/rating_showcase_get?package_id='.$package_id);
  $rating = ($package_id*0.5);
  if ( $rating > 5 ) {
    $rating = 5;
  }
  return array('rating' => $rating, 'ratings_count' => ($package_id*2)); //return $data['result'];
}

function get_stars($package_id) {
  $package_rating = get_ckan_package_rating($package_id);
  $count = $package_rating['ratings_count'];
  $rating = $package_rating['rating'];
  
  /*$i = 5;
  while ( $i < 0 ) {
    array_push($i);
  }*/
}

function parse_date($date) {
  $d = new DateTime($date);
  return $d->format('d.m.Y');
}

function get_notes_excerpt($str) {
  return explode(".", $str)[0] . '. ';
}

function assets_url() {
  return site_url().'/assets';
}
