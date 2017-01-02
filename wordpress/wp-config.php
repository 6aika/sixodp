<?php


// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'mysql');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         'qNe;*]+g7mr#s{+mR4C}`XW2SjMhl(|cDjYj|Q-]]Pf!;RPKyiNP<}t(0Wvb}?sf');
define('SECURE_AUTH_KEY',  'o-}72:&}mSz?4F!X5s(Zm63!)U%gN]v+<: =/9KZ+mB`j#k/Hq-J1?ZutFzU~cpp');
define('LOGGED_IN_KEY',    '@*Q^SI$Wy(ppMPvbTx.}bio0|i|j%K>`o+$/2x?Jl1Vt!  x.y/E>{/C|M9<a!VY');
define('NONCE_KEY',        '{bk# <GS4Pbf QsN6S&H|nU%R.^vLc:>T_+k}q(&3{>-7j.I+vfD$#m4I.--*+mq');
define('AUTH_SALT',        '/taRz0}SQfV&+SU66QNq&E[QaYosNrK)IVf->S~$vQlgY(3CSlvQEq1(s:Z;YVVN');
define('SECURE_AUTH_SALT', 'rA#48aHwmb!gT?G7Z_5Vg!|KkF3hw:|waE%=<eQt`hO-@bqHZMN$B~3rmL@Tr|>Z');
define('LOGGED_IN_SALT',   'lMHCfHyT(;1aNmi@Ac8{MYS!mKbM+Q+%EKhnYp@0egjwHWS%`.8=V[|K<G)WG--Q');
define('NONCE_SALT',       'Ga3{>U$SHi+az=X+ob61beCcDI{:]y,6ek!mD?m,}iL}-,{Z~U$oQA%]sUR+ wZr');


$table_prefix = 'wp_';





/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
