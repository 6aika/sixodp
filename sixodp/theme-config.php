<?php
  /**
  * Theme config file for Sixodp Wordpress theme.
  */
?>

<?php
  include('ckan-config.php');

  define( 'DEFAULT_PAGES', array(
    array(
      'locale' => 'fi',
      'code' => 'fi',
      'pages' => array(
        'main' => array( 'post_title' => 'fi', 'post_content' => "This is my post", 'page_template' => 'home.php', 'children' => array(
          'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "This is my post", 'page_template' => 'roadmap.php' ),
          'latest_updates' => array( 'post_title' => 'Ajankohtaista', 'post_content' => "This is my post", 'page_template' => 'ajankohtaista.php' ),
      ) ),
    ) ),
    array(
      'locale' => 'en_GB',
      'code' => 'en',
      'pages' => array(
        'main' => array( 'post_title' => 'en_GB', 'post_content' => "This is my post", 'page_template' => 'home.php', 'children' => array(
          'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "This is my post", 'page_template' => 'roadmap.php' ),
          'latest_updates' => array( 'post_title' => 'Latest updates', 'post_content' => "This is my post", 'page_template' => 'ajankohtaista.php' ),
        ) ),
    ) ),
    array(
      'locale' => 'sv',
      'code' => 'sv',
      'pages' => array(
        'main' => array( 'post_title' => 'sv', 'post_content' => "This is my post", 'page_template' => 'home.php', 'children' => array(
          'roadmap' => array( 'post_title' => 'Roadmap', 'post_content' => "This is my post", 'page_template' => 'roadmap.php' ),
          'latest_updates' => array( 'post_title' => 'Latest updates', 'post_content' => "This is my post", 'page_template' => 'ajankohtaista.php' ),
        ) ),
    ) )
  ) );

  define( 'PRIMARY_MENU_ITEMS_FI', array(
    array('menu-item-title' => 'Etusivu', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'Tietoaineistot', 'menu-item-url' => '/data/fi/dataset'),
    array('menu-item-title' => 'Sovellukset', 'menu-item-url' => '/data/fi/showcase'),
    array('menu-item-title' => 'Aineistokokonaisuudet', 'menu-item-url' => '/data/fi/collection')
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
    array('menu-item-title' => 'Aineistokokonaisuudet', 'menu-item-url' => '/data/fi/collection')
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
    array('menu-item-title' => 'Tili', 'menu-item-url' => '/#'),
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_GB'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'Account', 'menu-item-url' => '/#'),
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_GB'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'Konto', 'menu-item-url' => '/#'),
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en_GB'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
?>
