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
      'primary' => __( 'Primary Menu', 'twentysixteen' ),
      'social'  => __( 'Social Links Menu', 'twentysixteen' ),
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


add_action( 'customize_register' , 'sixodp_theme_options' );
function sixodp_theme_options( $wp_customize ) {
  // Sections, settings and controls will be added here

  $wp_customize->add_section("colors", array(
    "title" => __("Colors", "customizer_colors_sections"),
    'priority'    => 100,
		'capability'  => 'edit_theme_options',
  ));

  $wp_customize->add_setting( 'primary_color', array(
    'default' => 'rebeccapurple',
    'transport' => 'postMessage'
  ));
  $wp_customize->add_setting( 'complementary_color', array(
    'default' => 'hotpink',
    'transport' => 'postMessage'
  ));

  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'primary_color_control',
    array(
      'label'    => __( 'Primary Color', 'primary_color_text' ),
      'section'  => 'colors',
      'settings' => 'primary_color',
      'priority' => 40,
    )
  ));

  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'complementary_color_control',
    array(
      'label'    => __( 'Complementary Color', 'complementary_color_text' ),
      'section'  => 'colors',
      'settings' => 'complementary_color',
      'priority' => 50,
    )
  ));
}

add_action( 'wp_head' , 'my_dynamic_css' );
function my_dynamic_css() {
	?>
  <style type='text/css'>
    .color-primary {
      color: <?php echo get_theme_mod('primary_color', 'rebeccapurple'); ?> !important;
    }
    .bgcolor-primary {
      background-color: <?php echo get_theme_mod('primary_color', 'rebeccapurple'); ?> !important;
    }
    .border-primary {
      border-color: <?php echo get_theme_mod('primary_color', 'rebeccapurple'); ?> !important;
    }

    .color-complementary {
      color: <?php echo get_theme_mod('complementary_color', 'hotpink'); ?> !important;
    }
    .bgcolor-complementary {
      background-color: <?php echo get_theme_mod('complementary_color', 'hotpink'); ?> !important;
    }
    .border-complementary {
      border-color: <?php echo get_theme_mod('complementary_color', 'hotpink'); ?> !important;
    }

    a {
      color: <?php echo get_theme_mod('primary_color', 'rebeccapurple'); ?> !important;
    }

    a:hover {
      color: <?php echo get_theme_mod('primary_color', 'rebeccapurple'); ?> !important;
      opacity: 0.7;
    }
  </style>
	<?php
}

function sixodp_scripts() {
    wp_enqueue_script( 'app', get_template_directory_uri() . '/app.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'sixodp_scripts' );


function get_ckan_data($url) {
  return json_decode(file_get_contents($url), TRUE);
}

function get_popular_tags() {
  $data = get_ckan_data(CKAN_API_URL.'/package_search?facet.field=["tags"]&facet.limit=5');
  return $data['result']['facets']['tags'];
}

function get_recent_content() {
  $data = get_ckan_data(CKAN_API_URL.'/package_search?sort=date_released%20desc&rows=5');
  return $data['result']['results'];
}

function get_datasets() {
  $data = get_ckan_data(CKAN_API_URL.'/package_search');
  return $data['result'];
}

function get_dataset_count() {
  return get_datasets()['count'];
}


function get_showcases_count() {
  $data = get_ckan_data(CKAN_API_URL.'/ckanext_showcase_list');
  return count($data['result']);
}

function parse_date($date) {
  $d = new DateTime($date);
  return $d->format('d.m.Y');
}
