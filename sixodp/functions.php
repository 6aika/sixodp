<?php
require_once(ABSPATH . 'wp-admin/includes/post.php'); 
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
require_once WP_CONTENT_DIR . '/themes/sixodp/vendor/Parsedown.php';

load_theme_textdomain('sixodp');
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
  }
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'sixodp_theme_setup' );

// add tag support to pages
function tags_categories_support_all() {
  register_taxonomy_for_object_type('post_tag', 'page');
  register_taxonomy_for_object_type('category', 'page');
}

// ensure all tags are included in queries
function tags_support_query($wp_query) {
  if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
}

// tag hooks
add_action('init', 'tags_categories_support_all');
add_action('pre_get_posts', 'tags_support_query');


function footer_widgets_init() {
	register_sidebar( array(
		'name'          => 'Footer widget sidebar',
		'id'            => 'footer_widgets',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h4 class="rounded">',
		'after_title'   => '</h4>',
	) );
    register_sidebar( array(
		'name'          => 'Footer content sidebar',
		'id'            => 'footer_content',
		'before_widget' => '<div class="footer-content-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
	) );
}
add_action( 'widgets_init', 'footer_widgets_init' );

if(function_exists("register_field_group"))
{
  register_field_group(array (
    'id' => 'acf_page-fields',
    'title' => 'Page fields',
    'fields' => array (
      array (
        'key' => 'field_58d0f3bd42153',
        'label' => 'Page description',
        'name' => 'page_description',
        'type' => 'text',
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_58d0f3bd42154',
        'label' => 'Frontpage background',
        'name' => 'frontpage_background',
        'type' => 'image',
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      )
    ),
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'page',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}

$image_field = array(
  'return_format' => 'array',
  'preview_size' => 'thumbnail',
  'library' => 'all',
  'min_width' => 0,
  'min_height' => 0,
  'min_size' => 0,
  'max_width' => 0,
  'max_height' => 0,
  'max_size' => 0,
  'mime_types' => '',
);

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
    'supports'      => array( 'title', 'editor', 'custom-fields' ),
    'has_archive'   => false,
    'show_in_rest'  => true
  );

  register_post_type( 'notification', $args );
}
add_action( 'init', 'register_notifications' );


function add_custom_fields_support_for_pages() {
	add_post_type_support( 'page', 'custom-fields' );
}
add_action( 'init', 'add_custom_fields_support_for_pages' );


function create_primary_menus() {
  create_menu_i18n('primary_fi', PRIMARY_MENU_ITEMS_FI, 'primary');
  create_menu_i18n('primary_en_gb', PRIMARY_MENU_ITEMS_EN, 'primary');
  create_menu_i18n('primary_sv', PRIMARY_MENU_ITEMS_SV, 'primary');
}

function create_secondary_menus() {
  create_menu_i18n('secondary_fi', SECONDARY_MENU_ITEMS_FI, 'secondary');
  create_menu_i18n('secondary_en_gb', SECONDARY_MENU_ITEMS_EN, 'secondary');
  create_menu_i18n('secondary_sv', SECONDARY_MENU_ITEMS_SV, 'secondary');
}

function create_footer_menus() {
  create_menu_i18n('footer_fi', FOOTER_MENU_ITEMS_FI, 'footer_menu');
  create_menu_i18n('footer_en_gb', FOOTER_MENU_ITEMS_EN, 'footer_menu');
  create_menu_i18n('footer_sv', FOOTER_MENU_ITEMS_SV, 'footer_menu');
}
function create_social_media_menus() {
  create_menu_i18n('socialmedia',null,null);
}


function create_menu_items($menu_id, $itemsArr, $parent_id = false) {
  foreach ($itemsArr as $item) {
    if ($item['menu-item-type'] == 'post_type') {
      $post = get_posts(array('name' => $item['menu-item-object-slug'], 'post_type' => 'any'))[0];

      $item['menu-item-object-slug'] = '';
      $item['menu-item-object-id'] = $post->ID;
    }

    if ($parent_id) $item['menu-item-parent-id'] = $parent_id;

    $item['menu-item-status'] = 'publish';

    $item_id = wp_update_nav_menu_item($menu_id, 0, $item);

    if (isset($item['children'])) create_menu_items($menu_id, $item['children'], $item_id);
  }
}

function create_menu_i18n($menu_name, $itemsArr, $location) {

  // If it doesn't exist, let's create it.
  if( !wp_get_nav_menu_object( $menu_name )){
      $menu_id = wp_create_nav_menu($menu_name);

    create_menu_items($menu_id, $itemsArr);
    //then you set the wanted theme  location
    $menu = get_term_by( 'name', $menu_name, 'nav_menu' );
    $locations = get_theme_mod('nav_menu_locations');
    $locations[$location] = $menu->term_id;
    set_theme_mod( 'nav_menu_locations', $locations );
  }
}

function create_default_categories() {
  $translated_categories = array();
  foreach( DEFAULT_CATEGORIES as $lang_data ) {
    $translated_categories = insert_categories($lang_data['categories'], $lang_data['locale'], $lang_data['code'], $translated_categories);
  }

  foreach ($translated_categories as $translations) {
    pll_save_term_translations($translations);
  } 
}

function insert_categories($categories, $locale, $code, $translated_categories, $parent_id = null) {
  foreach ($categories as $key => $category) {
    $category_exists = get_categories(array(
      'name' => $category['cat_name'],
      'lang' => '',
      'hide_empty' => false
    ));

    if (sizeof($category_exists) == 0) {
      $defaults = array(
        'taxonomy' => 'category'
      );

      if ($parent_id !== null) $defaults['category_parent'] = $parent_id;

      $category_id = wp_insert_category(array_merge($defaults, $category));
      pll_set_term_language( $category_id, $locale );

      if (!isset($translated_categories[$key])) $translated_categories[$key] = array();
      
      $translated_categories[$key][$code] = $category_id;
    }
    else {
      $category_id = $category_exists[0]->term_id;
    }

    if (isset($category['children'])) $translated_categories = insert_categories($category['children'], $locale, $code, $translated_categories, $category_id);
  }

  return $translated_categories;
} 

function create_default_pages() {
  $translated_pages = array();
  foreach( DEFAULT_PAGES as $lang_data ) {
    $translated_pages = insert_pages($lang_data['pages'], $lang_data['locale'], $lang_data['code'], $translated_pages);
  }

  foreach ($translated_pages as $translations) {
    pll_save_post_translations($translations);
  } 
}

function insert_pages($pages, $locale, $code, $translated_pages, $parent_id = null) {
  foreach ($pages as $key => $page) {
    $page_exists = get_page_by_title($page['post_title']);

    if (!isset($page_exists)) {
      $defaults = array(
        'post_type' => 'page', 
        'post_status' => 'publish'
      );

      if ($parent_id !== null) $defaults['post_parent'] = $parent_id;

      $page_id = wp_insert_post(array_merge($defaults, $page));
      pll_set_post_language( $page_id, $locale );

      if (!isset($translated_pages[$key])) $translated_pages[$key] = array();
      
      $translated_pages[$key][$code] = $page_id;
    }
    else $page_id = $page_exists->ID;

    if (isset($page['children']))  $translated_pages = insert_pages($page['children'], $locale, $code, $translated_pages, $page_id);
  }

  return $translated_pages;
} 

function get_translated_page_by_title ($title) {
  $orig_page = get_page_by_title($title);
  if (!$orig_page) return false;

  $translated_page_id = pll_get_post($orig_page->ID);

  if (!$translated_page_id) return false;

  return get_page($translated_page_id);
}

function get_translated_category_by_slug ($slug, $lang = null) {
  $categories = get_categories(array(
    'slug' => $slug,
    'lang' => '',
    'hide_empty' => false
  ));

  if (sizeof($categories) == 0) return false;
  $orig_category = $categories[0];

  $translated_category_id = pll_get_term($orig_category->term_id, $lang);

  if (!$translated_category_id) return false;

  return get_category($translated_category_id);
}


function sixodp_scripts() {
    wp_enqueue_script( 'app', get_template_directory_uri() . '/app.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'sixodp_scripts' );

function get_nav_menu_items($menu) {
  return wp_get_menu_array($menu);
}


function wp_get_menu_array($current_menu) {
    //var_dump(!in_array(get_current_locale().'', array("fi", "en_GB", "sv")));
    if ( !in_array(get_current_locale().'', array("fi", "en_gb", "sv")) ) {
      $array_menu = wp_get_nav_menu_items($current_menu . '_fi');
    } else {
      $array_menu = wp_get_nav_menu_items($current_menu . '_' . get_current_locale());
    }

    $menu = array();
    foreach ($array_menu as $m) {
        if (empty($m->menu_item_parent)) {
            $menu[$m->ID] = array();
            $menu[$m->ID]['ID']      =   $m->ID;
            $menu[$m->ID]['title']       =   $m->title;
            $menu[$m->ID]['url']         =   $m->url;
            $menu[$m->ID]['children']    =   array();
            if (is_active_menu_item($m)) {
              $menu[$m->ID]['isActive'] = true;
            } else {
              $menu[$m->ID]['isActive'] = false;
            }
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
            if (is_active_menu_item($m)) {
              $submenu[$m->ID]['isActive']            = true;
              $menu[$m->menu_item_parent]['children'][$m->ID]["isActive"] = true;
              $menu[$m->menu_item_parent]['isActive'] = true;
            } else {
              $submenu[$m->ID]['isActive']            = false;
            }
        }
    }
    return $menu;

}

function is_active_menu_item($menu_item) {
  $req_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $menu_url = $menu_item->url;
  return ( $req_url == $menu_url.'/' || $req_url == $menu_url );
}

function get_tuki_links() {
  // Set up the objects needed
  $my_wp_query = new WP_Query();
  $all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 'posts_per_page' => '-1'));

  $tuki_page          = get_page_by_title('Tuki');
  return get_page_children( $tuki_page->ID, $all_wp_pages );
}

function get_child_pages($page_title, $limit = 6) {
  // Set up the objects needed
  $my_wp_query = new WP_Query();
  $all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 'posts_per_page' => '-1'));

  $parent_page          = get_page_by_title($page_title);
  return array_slice(get_page_children( $parent_page->ID, $all_wp_pages ), 0, $limit);
}

function get_current_locale_ckan() {
  $locale = get_current_locale();

  // Convert second part of locale to upper case
  $locale = substr_replace($locale, strtoupper(substr($locale, 3)), 3);

  return $locale;
}

function get_current_locale() {
  $path = explode('/', $_SERVER['REQUEST_URI']);
  return $path[1];
}

function get_ckan_data($url) {
  $data = json_decode(file_get_contents($url), TRUE);
  return $data;
}

function get_popular_tags() {
  $data = get_ckan_data(CKAN_API_URL.'/action/package_search?facet.field=["tags"]&facet.limit=5');
  return $data['result']['facets']['tags'];
}

function get_disqus_posts($args, $date = false, $cursor = false) {
  $fields = [];

  if ($date) {
    $start_of_week = strtotime($date);
    $end_of_week = strtotime($date . ' + 1 WEEK');
    $fields['since'] = date('Y-m-d\T23:59:59', $end_of_week);
  }

  if ($cursor) $fields['cursor'] = $cursor;

  $posts_data = json_decode(file_get_contents('http://disqus.com/api/3.0/forums/listPosts.json?'. http_build_query(array_merge($args, $fields))), true);

  $cursor_result = $posts_data['cursor'];
  $posts_data = isset($posts_data['response']) ? $posts_data['response'] : [];

  if ($date) {
    for ($i = 0; $i < sizeof($posts_data); $i++) {
      $post_time = strtotime($posts_data[$i]['createdAt']);

      if ($start_of_week > $post_time) break;
    }

    if ($i < sizeof($posts_data)) return array_splice($posts_data, 0, $i);
    else if ($cursor_result['more']) return array_merge($posts_data, get_disqus_posts($args, $date, $cursor_result['next']));
    else return $posts_data;
  }
  else return $posts_data;
}

function get_disqus_threads($args, $threads_query, $cursor = false) {
  $fields = [];

  if ($cursor) $fields['cursor'] = $cursor;

  $threads_data = json_decode(file_get_contents('http://disqus.com/api/3.0/forums/listThreads.json?'. http_build_query(array_merge($args, $fields)) . $threads_query), true);

  $cursor_result = $threads_data['cursor'];
  $threads_data = isset($threads_data['response']) ? $threads_data['response'] : [];

  if ($cursor_result['more']) return array_merge($threads_data, get_disqus_threads($args, $threads_query, $cursor_result['next']));
  else return $threads_data;
}

function get_recent_comments($date = false) {
  $fields = array(
    'api_secret' => DISQUS_SECRET_KEY,
    'forum' => DISQUS_SHORT_NAME,
    'limit' => 100
  );

  $posts_data = get_disqus_posts($fields, $date);

  $threads_query = '&thread=' . implode('&thread=', array_map(function ($row) { return $row['thread']; }, $posts_data ));

  $threads_data = get_disqus_threads($fields, $threads_query);

  $threads = array();

  foreach ($threads_data as $thread) {
    $threads[$thread['id']] = $thread;
  }

  $comments = array_map(function ($row) use ($threads) {
    $thread = $threads[$row['thread']];

    return array(
      'date' => $row['createdAt'],
      'updated_at' => $row['createdAt'],
      'type' => 'comment',
      'link' => $thread['link'],
      'title' => $thread['title'],
      'notes' => $row['raw_message']
    );
  }, $posts_data);

  if(isset($comments)) {
    return $comments;
  }
  return [];
}

function get_disqus_comment_count($post) {
  $fields = array(
    'api_secret' => DISQUS_SECRET_KEY,
    'forum' => DISQUS_SHORT_NAME,
    'thread' => 'link:'. get_permalink($post)
  );

  $thread_data = json_decode(file_get_contents('http://disqus.com/api/3.0/threads/set.json?'. http_build_query($fields)), true);

  return $thread_data['response'][0]['posts'];
}

function get_recent_content($date = false) {
  if ($date) {
    $data = get_ckan_data(CKAN_API_URL.'/action/package_search?sort=date_updated%20desc&rows=8&q=date_updated:['. date('Y-m-d\T00:00:00', strtotime($date)) .'Z%20TO%20'. date('Y-m-d\T00:00:00', strtotime($date . '+ 1 WEEK')) .'Z]');
  }
  else $data = get_ckan_data(CKAN_API_URL.'/action/package_search?sort=date_updated%20desc&rows=8');
  return $data['result']['results'];
}

function get_latest_datasets() {
  $data = get_ckan_data(CKAN_API_URL.'/action/package_search?sort=date_updated%20desc&rows=6');
  return $data['result']['results'];
}

function get_datasets() {
  $data = get_ckan_data(CKAN_API_URL.'/action/package_search');
  return $data['result'];
}

function get_datasets_search($search) {
  $data = get_ckan_data(CKAN_API_URL.'/action/package_search?q='.$search);
  return $data['result'];
}

function get_dataset_count() {
  $datasets = get_datasets();
  return $datasets['count'] ? $datasets['count'] : 0;
}

function get_items($type) {
  $data = get_ckan_data(CKAN_API_URL."/action/".$type."_list");
  return $data['result'];
}

function get_showcases_count() {
  return get_count('ckanext_showcase');
}

function get_latest_showcases($limit, $date = false) {
  if ($date) {
    $data = get_ckan_data(CKAN_API_URL.'/action/package_search?sort=metadata_created%20desc&fq=dataset_type:showcase&rows='.$limit.'&q=metadata_created:['. date('Y-m-d\T00:00:00', strtotime($date)) .'Z%20TO%20'. date('Y-m-d\T00:00:00', strtotime($date . '+ 1 WEEK')) .'Z]');
  }
  else $data = get_ckan_data(CKAN_API_URL.'/action/package_search?sort=metadata_created%20desc&fq=dataset_type:showcase&rows='.$limit);
  return $data['result']['results'];
}

function get_organizations_count() {
  return get_count('organization');
}

function get_api_count() {
  $api_collection = get_ckan_data(CKAN_API_URL."/action/api_collection_show");

  if($api_collection['result']['package_count']) {
    return $api_collection['result']['package_count'];
  }
  return 0;
}



function get_api_link() {
  $api_collection = get_ckan_data(CKAN_API_URL."/action/api_collection_show");

  if($api_collection['result']['name']) {

    return CKAN_BASE_URL . '/'. get_current_locale_ckan() . '/collection/'. $api_collection['result']['name'];
  }
}

function get_count($type) {
  return count(get_items($type));
}

function get_ckan_categories() {
  $data = get_ckan_data(CKAN_API_URL.'/action/group_list?all_fields=true&include_extras=true&sort=name%20asc');
  return $data['result'];
}

function get_ckan_package_rating($package_id) {
  $data = get_ckan_data(CKAN_API_URL.'/action/rating_showcase_get?package_id='.$package_id);
  $res = $data['result'];
  return array(
    'rating' => $res['rating'],
    'ratings_count' => $res['ratings_count']
  );
}

function parse_date($date) {
  $d = new DateTime($date);
  return $d->format('d.m.Y');
}

function get_days_ago($date) {
  $date = new DateTime($date);
  $now = new DateTime();
  $interval = $now->diff($date);
  return $interval->days . $interval->format(' ' . __('days', 'sixodp') . ', %h ' . __('hours', 'sixodp') . ' ' . __('ago', 'sixodp'));
}

function get_notes_excerpt($str) {
  $truncatedNotes = explode(".", $str)[0];
  if($truncatedNotes) {
    $truncatedNotes = $truncatedNotes.'.';
  }
  return $truncatedNotes;
}

function assets_url() {
  return site_url().'/assets';
}

function sort_results($arr) {
  $temp = array();
  foreach ($arr as $key => $row)
  {
    $temp[$key] = strtotime($row['date_updated'] ? $row['date_updated'] : $row['date']);
  }

  array_multisort($temp, SORT_DESC, $arr);

  return $arr;
}

function format_ckan_row($row) {
  return array(
    'date' => isset($row['date_created']) ? $row['date_created'] : $row['metadata_created'],
    'date_updated' => isset($row['date_updated']) ? $row['date_updated'] : $row['metadata_created'],
    'type' => array('link' => CKAN_BASE_URL .'/'. get_current_locale_ckan() .'/'. $row['type'], 'label' => $row['type']),
    'link' => CKAN_BASE_URL .'/'. get_current_locale_ckan() .'/'. $row['type'] .'/'. $row['name'],
    'title' => $row['title'],
    'title_translated' => isset($row['title_translated']) ? $row['title_translated'] : (object) array('fi' => $row['title'], 'sv' => $row['title'], 'en_GB' => $row['title']),
    'notes_translated' => $row['notes_translated']
  );
}

function get_recent_posts($type, $date = false) {
  $args = array(
    'post_type' => $type
  );

  if ($date) {
    $args['date_query']['before'] = date('Y-m-d', strtotime($date . ' + 1 WEEK'));
    $args['date_query']['after'] = $date;
  }

  $data = array_map(function($row) use ($type) {
    return array(
      'date' => $row->post_date,
      'date_updated' => $row->post_modified,
      'type' => $type,
      'link' => get_permalink($row),
      'title' => $row->post_title,
      'notes' => $row->post_content,
    );
  }, get_posts($args));

  return $data;
}

function get_latest_updates($types = array(), $date = false, $limit = 12) {
  $defaults = array(
    'datasets' => true,
    'showcases' => true,
    'comments' => true,
    'posts' => true,
    'pages' => true,
    'data_requests' => false,
    'showcase_ideas' => false,
  );

  $types = array_merge($defaults, $types);

  $datasets   = $types['datasets'] ? array_map('format_ckan_row', get_recent_content($date)) : [];
  $showcases  = $types['showcases'] ? array_map('format_ckan_row', get_latest_showcases(20, $date)) : [];
  $comments   = $types['comments'] ? get_recent_comments($date) : [];
  $posts = $types['posts'] ? get_recent_posts('post', $date) : [];
  $pages = $types['pages'] ? get_recent_posts('page', $date) : [];
  $data_requests = $types['data_requests'] ? get_recent_posts('data_request', $date) : [];
  $showcase_ideas = $types['showcase_ideas'] ? get_recent_posts('showcase_idea', $date) : [];

  $arr = array_merge($datasets, $showcases, $comments, $posts, $pages, $data_requests, $showcase_ideas);

  if ($limit) return array_slice(sort_results($arr), 0, $limit);
  else return sort_results($arr);
}

const DEFAULT_LANGUAGE = 'fi';
function get_lang() {
  $language= pll_current_language();
  $arr = explode("_", $language, 2);
  if(count($arr) > 0) {
    return strtolower($arr[0]);
  }
  return DEFAULT_LANGUAGE;
}

function get_translated($object, $field) {
  $lang = get_lang();
  if( isset($object[$field . '_translated']) and is_array($object[$field . '_translated']) and $object[$field . '_translated'] ) {
    $translated_value = $object[$field . '_translated'][$lang];

    // Return default language if the translation was missing
    if(empty($translated_value)) {
      return $object[$field . '_translated'][DEFAULT_LANGUAGE];
    }
    return $translated_value;
  }
  return $object[$field];
}

function new_subcategory_hierarchy() { 
    $category = get_queried_object();

    $templates = array();

    $languages = array_diff(pll_languages_list(array('fields' => 'slug')), array(pll_current_language()));


    $templates[] = "category-{$category->slug}.php";
    $templates[] = "category-{$category->term_id}.php";

    foreach ($languages as $lang) {
      $translated_category_id = pll_get_term($category->term_id, $lang);

      if (!$translated_category_id) continue;

      $translated_category = get_category(pll_get_term($category->term_id, $lang));

      $templates[] = "category-{$translated_category->slug}.php";
      $templates[] = "category-{$translated_category->term_id}.php";
    }

    $parent_id = $category->category_parent;

    while ($parent_id != 0) {
      $category = get_category($parent_id);

      $templates[] = "category-{$category->slug}.php";
      $templates[] = "category-{$category->term_id}.php";

      foreach ($languages as $lang) {
        $translated_category_id = pll_get_term($category->term_id, $lang);

        if (!$translated_category_id) continue;

        $translated_category = get_category(pll_get_term($category->term_id, $lang));

        $templates[] = "category-{$translated_category->slug}.php";
        $templates[] = "category-{$translated_category->term_id}.php";
      }

      $parent_id = $category->category_parent;
    }

    $templates[] = 'category.php'; 

    return locate_template( $templates );
}

add_filter( 'category_template', 'new_subcategory_hierarchy' );

function get_post_grandparent_id($post_ID) {
  $parent_ID = wp_get_post_parent_id($post_ID);

  if ($parent_ID != 0) {
    $result = get_post_grandparent_id($parent_ID);

    return $result;
  }
  else return $post_ID;
}

function get_category_grandparent_id($category) {
  
  if (is_numeric($category)) $category = get_category($category);

  if (!$category INSTANCEOF WP_Term) return false;

  $parent_ID = $category->parent;

  if ($parent_ID != 0) {
    $result = get_category_grandparent_id($parent_ID);

    return $result;
  }
  else return $category->term_id;
}

function create_form_results() {

  register_post_type( 'data_request', array(
    'label'         => "Data Requests",
    'description'   => 'Data Requests results',
    'public'        => true,
    'supports'      => array( 'title', 'editor', 'custom-fields', 'comments' ),
    'has_archive'   => true,
    'show_in_rest'  => true
  ) );

  register_post_type( 'showcase_idea', array(
    'label'         => "Showcase ideas",
    'description'   => 'Showcase ideas results',
    'public'        => true,
    'supports'      => array( 'title', 'editor', 'custom-fields', 'comments' ),
    'has_archive'   => true,
    'show_in_rest'  => true
  ) );

  if(function_exists("register_field_group"))
  {
    register_field_group(array (
      'id' => 'acf_form-result-fields',
      'title' => 'Form result fields',
      'fields' => array (
        array (
          'key' => 'field_v39jt81uh8792',
          'label' => 'Name',
          'name' => 'name',
          'type' => 'text',
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'formatting' => 'html',
          'maxlength' => '',
        ),
        array (
          'key' => 'field_7ldql03xmqqfu',
          'label' => 'Email',
          'name' => 'email',
          'type' => 'text',
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'formatting' => 'html',
          'maxlength' => '',
        ),
      ),
      'location' => array (
        array (
          array (
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'data_request',
            'order_no' => 0,
            'group_no' => 0,
          ),
        ),
        array(
          array (
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'showcase_idea',
            'order_no' => 0,
            'group_no' => 0,
          ),
        ),
      ),
      'options' => array (
        'position' => 'normal',
        'layout' => 'no_box',
        'hide_on_screen' => array (
        ),
      ),
      'menu_order' => 0,
    ));
  }
}
add_action( 'init', 'create_form_results' );

function custom_category_query($query) {
  if ($query->is_category) {
    $category = get_queried_object();

    $expected_anchestor = get_translated_category_by_slug('tuki', pll_get_term_language($category->term_id));
   
    if ($category->term_id == $expected_anchestor->term_id or cat_is_ancestor_of($expected_anchestor, $category)) {
      $query->set('post_type', 'page');
    }
    $query->set('posts_per_page', 9);
  }

  return $query;
}

add_action( 'pre_get_posts', 'custom_category_query' );

function render_markdown($markdown) {
  $Parsedown = new Parsedown();
  return $Parsedown->text($markdown);
}

function excerpt_count_js(){

if ('page' != get_post_type()) {

      echo '<script>jQuery(document).ready(function(){
jQuery("#post-status-info #wp-word-count .word-count").after(" Character count: <span id=\"character_counter\"></span>");
     jQuery("span#character_counter").text(jQuery("#content").val().length);
     jQuery("#content").keyup( function() {
       jQuery("span#character_counter").text(jQuery("#content").val().length);
   });
});</script>';
}
}
add_action( 'admin_head-post.php', 'excerpt_count_js');
add_action( 'admin_head-post-new.php', 'excerpt_count_js');
