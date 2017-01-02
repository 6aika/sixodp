<?php

/** 

 * WordPressin perusasetukset.

 *

 * Tämä tiedosto sisältää seuraavat asetukset: MySQL-asetukset, Tietokantataulun etuliite,

 * henkilökohtaiset salausavaimet (Secret Keys), WordPressin kieli, ja ABSPATH. Löydät lisätietoja

 * Codex-sivulta {@link http://codex.wordpress.org/Editing_wp-config.php Editing

 * wp-config.php}. Saat MySQL-asetukset palveluntarjoajaltasi.

 *

 * Automaattinen wp-config.php-tiedoston luontityökalu käyttää tätä tiedostoa

 * asennuksen yhteydessä. Sinun ei tarvitse käyttää web-asennusta, vaan voit 

 * tallentaa tämän tiedoston nimellä "wp-config.php" ja muokata allaolevia arvoja.

 *

 * @link https://codex.wordpress.org/Editing_wp-config.php

 *

 * @package WordPress

 */



// ** MySQL asetukset - Saat nämä tiedot palveluntarjoajaltasi ** //

/** WordPressin käyttämän tietokannan nimi */

define('DB_NAME', 'tietokannan_nimi');



/** MySQL-tietokannan käyttäjätunnus */

define('DB_USER', 'tietokannan_tunnus');



/** MySQL-tietokannan salasana */

define('DB_PASSWORD', 'tietokannan_salasana');



/** MySQL-palvelin */

define('DB_HOST', 'localhost');



/** Tietokantatauluissa käytettävä merkistö. */

define('DB_CHARSET', 'utf8');



/** The Database Collate type. Älä muuta tätä jos et ole varma. */

define('DB_COLLATE', 'utf8_swedish_ci');



/**#@+

 * Authentication Unique Keys and Salts.

 *

 * Muuta nämä omiksi uniikeiksi lauseiksi!

 * Voit luoda nämä käyttämällä {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org palvelua}

 * Voit muuttaa nämä koska tahansa. Kaikki käyttäjät joutuvat silloin kirjautumaan uudestaan.

 *

 * @since 2.6.0

 */

define('AUTH_KEY',         'oma uniikki lauseesi');

define('SECURE_AUTH_KEY',  'oma uniikki lauseesi');

define('LOGGED_IN_KEY',    'oma uniikki lauseesi');

define('NONCE_KEY',        'oma uniikki lauseesi');

define('AUTH_SALT',        'oma uniikki lauseesi');

define('SECURE_AUTH_SALT', 'oma uniikki lauseesi');

define('LOGGED_IN_SALT',   'oma uniikki lauseesi');

define('NONCE_SALT',       'oma uniikki lauseesi');

/**#@-*/



/**

 * WordPressin tietokantataulujen etuliite (Table Prefix).

 *

 * Samassa tietokannassa voi olla useampi WordPress-asennus, jos annat jokaiselle

 * eri tietokantataulujen etuliitteen. Sallittuja merkkejä ovat numerot, kirjaimet

 * ja alaviiva _.

 *

 */

$table_prefix  = 'wp_';



/**

 * Kehittäjille: WordPressin debug-moodi.

 *

 * Muuta tämän arvoksi true jos haluat nähdä kehityksen ajan debug-ilmoitukset

 * Tämä on erittäin suositeltavaa lisäosien ja teemojen kehittäjille.

 *

 * @link https://codex.wordpress.org/Debugging_in_WordPress

 */

define('WP_DEBUG', false);



/* Siinä kaikki, älä jatka pidemmälle! */



/** WordPress absolute path to the Wordpress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');



/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');

