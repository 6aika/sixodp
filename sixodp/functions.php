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

    // Check if the menu exists
    $menu_name = 'primary';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    // If it doesn't exist, let's create it.
    if( !$menu_exists){
        $menu_id = wp_create_nav_menu($menu_name);

    	// Set up default menu items
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Datasets', 'sixodp'),
            'menu-item-url' => home_url( '/data/dataset' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Showcase', 'sixodp'),
            'menu-item-url' => home_url( '/data/showcase' ),
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Collections', 'sixodp'),
            'menu-item-url' => home_url( '/data/collection' ),
            'menu-item-status' => 'publish'));

    }

    //then you set the wanted theme  location
    $menu = get_term_by( 'name', $menu_name, 'nav_menu' );
    $locations = get_theme_mod('nav_menu_locations');
    $locations['primary'] = $menu->term_id;
    set_theme_mod( 'nav_menu_locations', $locations );
  }
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'sixodp_theme_setup' );

function my_theme_load_theme_textdomain() {
  load_theme_textdomain( 'sixodp', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'my_theme_load_theme_textdomain' );

function sixodp_scripts() {
    wp_enqueue_script( 'app', get_template_directory_uri() . '/app.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'sixodp_scripts' );


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

function assets_url() {
  return site_url().'/assets';
}
