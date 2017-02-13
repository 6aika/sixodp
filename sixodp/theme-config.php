<?php
  /**
  * Theme config file for Sixodp Wordpress theme.
  */
?>

<?php
  define( 'CKAN_API_URL', 'https://generic-qa.dataportaali.com/data/api' );
  define( 'MENU_ITEMS_FI', array(
    array('menu-item-title' => 'Tietoaineistot', 'menu-item-url' => '/data/dataset'),
    array('menu-item-title' => 'Sovellukset', 'menu-item-url' => '/data/showcase'),
    array('menu-item-title' => 'Aineistokokonaisuudet', 'menu-item-url' => '/data/collection')
  ) );
  define( 'MENU_ITEMS_EN', array(
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/dataset'),
    array('menu-item-title' => 'Showcases', 'menu-item-url' => '/data/showcase'),
    array('menu-item-title' => 'Collections', 'menu-item-url' => '/data/collection')
  ) );
  define( 'MENU_ITEMS_SV', array(
    array('menu-item-title' => 'Datasets', 'menu-item-url' => '/data/dataset'),
    array('menu-item-title' => 'Vitrin', 'menu-item-url' => '/data/showcase'),
    array('menu-item-title' => 'Kollektion', 'menu-item-url' => '/data/collection')
  ) );
?>
