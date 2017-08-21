<?php
  /**
  * Theme config file for Sixodp Wordpress theme.
  */
?>

<?php
  include('ckan-config.php');

  include('disqus-config.php');

  define( 'DEFAULT_PAGES', array(
    array(
      'locale' => 'fi',
      'code' => 'fi',
      'pages' => array(
        'main' => array( 'post_title' => 'fi', 'post_content' => "This is my post", 'page_template' => 'home.php', 'children' => array(
          'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "This is my post", 'page_template' => 'roadmap.php' ),
          'latest_updates' => array( 'post_title' => 'Ajankohtaista', 'post_content' => "This is my post", 'page_template' => 'ajankohtaista.php' ),
          'support' => array( 'post_title' => 'Tuki', 'post_content' => "This is my post", 'page_template' => 'tuki.php' ),
          'data_request_form' => array( 'post_title' => 'Uusi datatoive', 'post_content' => "This is my post", 'page_template' => 'data_request_form.php' ),
          'app_request_form' => array( 'post_title' => 'Uusi sovellustoive', 'post_content' => "This is my post", 'page_template' => 'app_request_form.php' ),
      ) ),
    ) ),
    array(
      'locale' => 'en_GB',
      'code' => 'en',
      'pages' => array(
        'main' => array( 'post_title' => 'en_GB', 'post_content' => "This is my post", 'page_template' => 'home.php', 'children' => array(
          'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "This is my post", 'page_template' => 'roadmap.php' ),
          'latest_updates' => array( 'post_title' => 'Latest updates', 'post_content' => "This is my post", 'page_template' => 'ajankohtaista.php' ),
          'support' => array( 'post_title' => 'Support', 'post_content' => "This is my post", 'page_template' => 'tuki.php' ),
          'data_request_form' => array( 'post_title' => 'New data request', 'post_content' => "This is my post", 'page_template' => 'data_request_form.php' ),
          'app_request_form' => array( 'post_title' => 'New app request', 'post_content' => "This is my post", 'page_template' => 'app_request_form.php' ),
        ) ),
    ) ),
    array(
      'locale' => 'sv',
      'code' => 'sv',
      'pages' => array(
        'main' => array( 'post_title' => 'sv', 'post_content' => "This is my post", 'page_template' => 'home.php', 'children' => array(
          'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "This is my post", 'page_template' => 'roadmap.php' ),
          'latest_updates' => array( 'post_title' => 'Senaste uppdateringarna', 'post_content' => "This is my post", 'page_template' => 'ajankohtaista.php' ),
          'support' => array( 'post_title' => 'Stöd', 'post_content' => "This is my post", 'page_template' => 'tuki.php' ),
          'data_request_form' => array( 'post_title' => 'Ny data begäran', 'post_content' => "This is my post", 'page_template' => 'data_request_form.php' ),
          'app_request_form' => array( 'post_title' => 'Ny app begäran', 'post_content' => "This is my post", 'page_template' => 'app_request_form.php' ),
        ) ),
    ) )
  ) );

  define( 'DEFAULT_CATEGORIES', array(
    array(
      'locale' => 'fi',
      'code' => 'fi',
      'categories' => array(
        'latest_updates' => array( 'cat_name' => 'Ajankohtaista', 'category_description' => '', 'children' => array(
          'blogs' => array ( 'cat_name' => 'Blogit', 'category_description' => '' ),
          'news' => array ( 'cat_name' => 'Uutiset', 'category_description' => '' )
        ) ),
        'support' => array( 'cat_name' => 'Tuki', 'category_description' => '', 'children' => array(
          'how_to_start' => array ( 'cat_name' => 'Kuinka aloitan?', 'category_description' => 'Kootut ohjeet sivun käyttöön' ),
          'data_users' => array ( 'cat_name' => 'Aineiston käyttäjälle', 'category_description' => 'Miten saan eniten irti avoimesta datasta?' ),
          'data_publishers' => array ( 'cat_name' => 'Aineiston julkaisijalle', 'category_description' => 'Kuinka julkaisen dataa?' ),
          'themes' => array( 'cat_name' => 'Teemat', 'category_description' => '', 'children' => array(
            array ( 'cat_name' => 'Työkalut', 'category_description' => '' ),
          ) )
        ) )
    ) ),
    array(
      'locale' => 'en_GB',
      'code' => 'en',
      'categories' => array(
        'latest_updates' => array( 'cat_name' => 'Latest updates', 'category_description' => '', 'children' => array(
          'blogs' => array ( 'cat_name' => 'Blogs', 'category_description' => '' ),
          'news' => array ( 'cat_name' => 'News', 'category_description' => '' )
        ) ),
        'support' => array( 'cat_name' => 'Support', 'category_description' => '', 'children' => array(
          'how_to_start' => array ( 'cat_name' => 'How to start?', 'category_description' => 'Kootut ohjeet sivun käyttöön' ),
          'data_users' => array ( 'cat_name' => 'Data users', 'category_description' => 'Miten saan eniten irti avoimesta datasta?' ),
          'data_publishers' => array ( 'cat_name' => 'Data publishers', 'category_description' => 'Kuinka julkaisen dataa?' ),
          'themes' => array( 'cat_name' => 'Themes', 'category_description' => '', 'children' => array(
            array ( 'cat_name' => 'Tools', 'category_description' => '' ),
          ) )
        ) )
    ) ),
    array(
      'locale' => 'sv',
      'code' => 'sv',
      'categories' => array(
        'latest_updates' => array( 'cat_name' => 'Senaste uppdateringarna', 'category_description' => '', 'children' => array(
          'blogs' => array ( 'cat_name' => 'Bloggar', 'category_description' => '' ),
          'news' => array ( 'cat_name' => 'Nyheter', 'category_description' => '' )
        ) ),
        'support' => array( 'cat_name' => 'Stöd', 'category_description' => '', 'children' => array(
          'how_to_start' => array ( 'cat_name' => 'Hur börjar jag', 'category_description' => 'Kootut ohjeet sivun käyttöön' ),
          'data_users' => array ( 'cat_name' => 'Data till användaren', 'category_description' => 'Miten saan eniten irti avoimesta datasta?' ),
          'data_publishers' => array ( 'cat_name' => 'Data utgivare', 'category_description' => 'Kuinka julkaisen dataa?' ),
          'themes' => array( 'cat_name' => 'Teman', 'category_description' => '', 'children' => array(
            array ( 'cat_name' => 'Verktyg', 'category_description' => '' ),
          ) )
        ) )
    ) ),
  ) );

  define( 'PRIMARY_MENU_ITEMS_FI', array(
    array('menu-item-title' => 'Etusivu', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'Tietoaineistot', 'menu-item-url' => '/data/fi/dataset'),
    array('menu-item-title' => 'Sovellukset', 'menu-item-url' => '/data/fi/showcase'),
    array('menu-item-title' => 'Datakokoelmat', 'menu-item-url' => '/data/fi/collection')
  ) );
  define( 'PRIMARY_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'Frontpage', 'menu-item-url' => '/en_GB'),
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/en_GB/dataset'),
    array('menu-item-title' => 'Showcases', 'menu-item-url' => '/data/en_GB/showcase'),
    array('menu-item-title' => 'Collections', 'menu-item-url' => '/data/en_GB/collection')
  ) );
  define( 'PRIMARY_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'Huvudsida', 'menu-item-url' => '/sv'),
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/sv/dataset'),
    array('menu-item-title' => 'Vitrin', 'menu-item-url' => '/data/sv/showcase'),
    array('menu-item-title' => 'Kollektion', 'menu-item-url' => '/data/sv/collection')
  ) );

  define( 'FOOTER_MENU_ITEMS_FI', array(
    array('menu-item-title' => 'Tietoaineistot', 'menu-item-url' => '/data/fi/dataset'),
    array('menu-item-title' => 'Sovellukset', 'menu-item-url' => '/data/fi/showcase'),
    array('menu-item-title' => 'Datakokoelmat', 'menu-item-url' => '/data/fi/collection')
  ) );
  define( 'FOOTER_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/en_GB/dataset'),
    array('menu-item-title' => 'Showcases', 'menu-item-url' => '/data/en_GB/showcase'),
    array('menu-item-title' => 'Collections', 'menu-item-url' => '/data/en_GB/collection')
  ) );
  define( 'FOOTER_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/sv/dataset'),
    array('menu-item-title' => 'Vitrin', 'menu-item-url' => '/data/sv/showcase'),
    array('menu-item-title' => 'Kollektion', 'menu-item-url' => '/data/sv/collection')
  ) );


  define( 'SECONDARY_MENU_ITEMS_FI', array(
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_GB'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_GB'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_GB'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
?>
