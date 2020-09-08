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
        'main' => array( 'post_title' => 'fi', 'post_content' => "", 'page_template' => 'home.php' ),
        'roadmap' => array( 'post_title' => 'Työn alla', 'post_content' => "", 'page_template' => 'roadmap.php' ),
        'latest_updates' => array( 'post_title' => 'Ajankohtaista', 'post_content' => "", 'page_template' => 'ajankohtaista.php' ),
        'support' => array( 'post_title' => 'Tuki', 'post_content' => "", 'page_template' => 'tuki.php' ),
        'data_request_form' => array( 'post_title' => 'Uusi datatoive', 'post_content' => "", 'page_template' => 'data_request_form.php' ),
        'showcase_idea_form' => array( 'post_title' => 'Uusi sovellusidea', 'post_content' => "", 'page_template' => 'showcase_idea_form.php' ),
        'latest-updates-search' => array( 'post_title' => 'Viimeisimmät päivitykset', 'post_content' => "", 'page_template' => 'latest-updates.php' ),
        'service' => array( 'post_title' => 'Palvelu', 'post_content' => "", 'page_template' => 'page_with_menu.php' ),
        'thank_you' => array('post_title' => 'Kiitos', 'post_content' => "", 'page_template' => 'thank_you.php'),
    ) ),
    array(
      'locale' => 'en_GB',
      'code' => 'en',
      'pages' => array(
        'main' => array( 'post_title' => 'en_GB', 'post_content' => "", 'page_template' => 'home.php' ),
        'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "", 'page_template' => 'roadmap.php' ),
        'latest_updates' => array( 'post_title' => 'Latest updates', 'post_content' => "", 'page_template' => 'ajankohtaista.php' ),
        'support' => array( 'post_title' => 'Support', 'post_content' => "", 'page_template' => 'tuki.php' ),
        'data_request_form' => array( 'post_title' => 'New data request', 'post_content' => "", 'page_template' => 'data_request_form.php' ),
        'showcase_idea_form' => array( 'post_title' => 'New showcase idea', 'post_content' => "", 'page_template' => 'showcase_idea_form.php' ),
        'latest-updates-search' => array( 'post_title' => 'Latest updates', 'post_content' => "", 'page_template' => 'latest-updates.php' ),
        'service' => array( 'post_title' => 'Service', 'post_content' => "", 'page_template' => 'page_with_menu.php' ),
        'thank_you' => array('post_title' => 'Thank you', 'post_content' => "", 'page_template' => 'thank_you.php')
    ) ),
    array(
      'locale' => 'sv',
      'code' => 'sv',
      'pages' => array(
        'main' => array( 'post_title' => 'sv', 'post_content' => "", 'page_template' => 'home.php' ),
        'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "", 'page_template' => 'roadmap.php' ),
        'latest_updates' => array( 'post_title' => 'Senaste uppdateringarna', 'post_content' => "", 'page_template' => 'ajankohtaista.php' ),
        'support' => array( 'post_title' => 'Stöd', 'post_content' => "", 'page_template' => 'tuki.php' ),
        'data_request_form' => array( 'post_title' => 'Ny data begäran', 'post_content' => "", 'page_template' => 'data_request_form.php' ),
        'showcase_idea_form' => array( 'post_title' => 'Ny app begäran', 'post_content' => "", 'page_template' => 'showcase_idea_form.php' ),
        'latest-updates-search' => array( 'post_title' => 'Senaste uppdateringarna', 'post_content' => "", 'page_template' => 'latest-updates.php' ),
        'service' => array( 'post_title' => 'Tjänst', 'post_content' => "", 'page_template' => 'page_with_menu.php' ),
        'thank_you' => array('post_title' => 'Tack', 'post_content' => "", 'page_template' => 'thank_you.php')
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
    array('menu-item-title' => 'Etusivu', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'fi', 'menu-item-object' => 'page'),
    array('menu-item-title' => 'Tietoaineistot', 'menu-item-url' => '/data/fi/dataset/', 'children' => array(
      array('menu-item-title' => 'Datahaku', 'menu-item-url' => '/data/fi/dataset/'),
      array('menu-item-title' => 'Työn alla', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'tyon-alla', 'menu-item-object' => 'page'),
      array('menu-item-title' => 'Datakokoelmat', 'menu-item-url' => '/data/fi/collection/'),
      array('menu-item-title' => 'Kategoriat', 'menu-item-url' => '/data/fi/group/'),
      array('menu-item-title' => 'Organisaatiot', 'menu-item-url' => '/data/fi/organization/'),
      array('menu-item-title' => 'Datatoiveet', 'menu-item-url' => '/data_request/'),
      array('menu-item-title' => 'Uusi datatoive', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'uusi-datatoive', 'menu-item-object' => 'page'),
    )),
    array('menu-item-title' => 'Sovellukset', 'menu-item-url' => '/data/fi/showcase', 'children' => array(
      array('menu-item-title' => 'Ilmoita sovellus', 'menu-item-url' => '/data/fi/submit-showcase/'),
      array('menu-item-title' => 'Sovellusideat', 'menu-item-url' => '/showcase_idea/'),
      array('menu-item-title' => 'Uusi sovellusidea', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'uusi-sovellusidea', 'menu-item-object' => 'page'),
    )),
    array('menu-item-title' => 'Ajankohtaista', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'ajankohtaista', 'menu-item-object' => 'page', 'children' => array(
      array('menu-item-title' => 'Kaikki', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'ajankohtaista', 'menu-item-object' => 'page'),
      array('menu-item-title' => 'Uutiset', 'menu-item-url' => '/fi/category/ajankohtaista/uutiset/'),
      array('menu-item-title' => 'Blogit', 'menu-item-url' => '/fi/category/ajankohtaista/blogit/'),
      array('menu-item-title' => 'Viimeisimmät päivitykset', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'viimeisimmat-paivitykset', 'menu-item-object' => 'page')
    )),
    array('menu-item-title' => 'Ohjeet', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'tuki', 'menu-item-object' => 'page'),
    array('menu-item-title' => 'Analytiikka', 'menu-item-url' => '/data/fi/statistics/', 'children' => array(
      array('menu-item-title' => 'Dashboard', 'menu-item-url' => '/data/fi/statistics/'),
      array('menu-item-title' => 'Raportit', 'menu-item-url' => '/data/fi/reports/'),
    )),
    array('menu-item-title' => 'Palvelu', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'palvelu', 'menu-item-object' => 'page'),
  ) );

  define( 'PRIMARY_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'Frontpage', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'en_gb', 'menu-item-object' => 'page'),
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/en_GB/dataset/', 'children' => array(
      array('menu-item-title' => 'Roadmap', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'roadmap', 'menu-item-object' => 'page'),
      array('menu-item-title' => 'New data request', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'new-data-request', 'menu-item-object' => 'page'),
      array('menu-item-title' => 'Collections', 'menu-item-url' => '/data/en_GB/collection/'),
    )),
    array('menu-item-title' => 'Showcases', 'menu-item-url' => '/data/en_GB/showcase', 'children' => array(
      array('menu-item-title' => 'Submit showcase', 'menu-item-url' => '/data/fi/submit-showcase/'),
      array('menu-item-title' => 'New app request', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'new-app-request', 'menu-item-object' => 'page'),
    )),
    array('menu-item-title' => 'Latest updates', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'latest-updates', 'menu-item-object' => 'page'),
    array('menu-item-title' => 'Support', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'support', 'menu-item-object' => 'page'),
  ) );

  define( 'PRIMARY_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'Huvudsida', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'en_gb', 'menu-item-object' => 'page'),
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/sv/dataset/', 'children' => array(
      array('menu-item-title' => 'Roadmap', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'roadmap', 'menu-item-object' => 'page'),
      array('menu-item-title' => 'Ny data begäran', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'ny-data-begaran', 'menu-item-object' => 'page'),
      array('menu-item-title' => 'Kollektion', 'menu-item-url' => '/data/sv/collection/'),
    )),
    array('menu-item-title' => 'Vitrin', 'menu-item-url' => '/data/en_GB/showcase'),
    array('menu-item-title' => 'Senaste uppdateringarna', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'senaste-uppdateringarna', 'menu-item-object' => 'page'),
    array('menu-item-title' => 'Stöd', 'menu-item-type' => 'post_type', 'menu-item-object-slug' => 'stod', 'menu-item-object' => 'page'),
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
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_gb'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_gb'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_gb'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
?>
