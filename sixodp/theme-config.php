<?php
  /**
  * Theme config file for Sixodp Wordpress theme.
  */
?>

<?php
  define( 'CKAN_API_URL', 'http://localhost/data/api' );
  define( 'PRIMARY_MENU_ITEMS_FI', array(
    array('menu-item-title' => 'Etusivu', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'Tietoaineistot', 'menu-item-url' => '/data/fi/dataset'),
    array('menu-item-title' => 'Sovellukset', 'menu-item-url' => '/data/fi/showcase'),
    array('menu-item-title' => 'Aineistokokonaisuudet', 'menu-item-url' => '/data/fi/collection')
  ) );
  define( 'PRIMARY_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'Frontpage', 'menu-item-url' => '/en'),
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/dataset'),
    array('menu-item-title' => 'Showcases', 'menu-item-url' => '/data/showcase'),
    array('menu-item-title' => 'Collections', 'menu-item-url' => '/data/collection')
  ) );
  define( 'PRIMARY_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'Huvudsida', 'menu-item-url' => '/sv'),
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/sv/dataset'),
    array('menu-item-title' => 'Vitrin', 'menu-item-url' => '/data/sv/showcase'),
    array('menu-item-title' => 'Kollektion', 'menu-item-url' => '/data/sv/collection')
  ) );

  define( 'SECONDARY_MENU_ITEMS_FI', array(
    array('menu-item-title' => 'Tili', 'menu-item-url' => '/#'),
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_EN', array(
    array('menu-item-title' => 'Account', 'menu-item-url' => '/#'),
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
  define( 'SECONDARY_MENU_ITEMS_SV', array(
    array('menu-item-title' => 'Konto', 'menu-item-url' => '/#'),
    array('menu-item-title' => 'fi', 'menu-item-url' => '/fi'),
    array('menu-item-title' => 'en', 'menu-item-url' => '/en'),
    array('menu-item-title' => 'sv', 'menu-item-url' => '/sv')
  ) );
?>
