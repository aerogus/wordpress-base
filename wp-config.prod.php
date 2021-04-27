<?php

/**
 * conf de prod
 */

// Chemins
$scheme = 'http://';
if (!empty($_SERVER['HTTPS']) || php_sapi_name() === 'cli') {
    $scheme = 'https://';
}
define('WP_HOME',        $scheme . 'wordpress.prod');
define('WP_SITEURL',     $scheme . 'wordpress.prod/wp');
define('WP_CONTENT_URL', $scheme . $_SERVER['HTTP_HOST'] . '/wp-content');
define('WP_CONTENT_DIR', __DIR__ . '/wp-content');

// MySQL
define('DB_NAME',     'wordpress');
define('DB_USER',     'wordpress');
define('DB_PASSWORD', 'changeme');
define('DB_HOST',     'localhost');
define('DB_CHARSET',  'utf8');
define('DB_COLLATE',  '');
$table_prefix  = 'wp_';

// i18n
define('WPLANG', 'fr_FR');

// Keys
// @see https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'gh%Hj<8DQV*{C?<WjK;l/I#>](}{Gwf[}ZMWnZ~|?l.zCT&f1G1h,iyPwWI[(WM_');
define('SECURE_AUTH_KEY',  ',gOu:PYME+$ANTFe:$O)rCQr|&Sz6VRx6Y:bZ<T)cL6T,78,1SNd-8^l$t)/~L}<');
define('LOGGED_IN_KEY',    'Owa~vIzzP-1Zt@GQA@-`_qqHIYm;wuhQc;0I4|>YZl;dUHD:y)-O+;$qJioIMK3>');
define('NONCE_KEY',        '_OckM[t@$vHzD|f_-I0%-F|vdvUJs$|Cs_|~QP (>&7>?znZb+E+/g4^4jS6vW/-');
define('AUTH_SALT',        'SliM-y!A)V* ]Dctx$~D|7x;;-OeZ~C}[TH0&-zdO{?YjOh?gDW(#()TCNtH(W7M');
define('SECURE_AUTH_SALT', 'X.(r>Y*WHN-@IF%m*i@W0Lh<|f|Uru=|Dt@LK%h?N)vE,5 :P-]=ngxu1}&]NJwO');
define('LOGGED_IN_SALT',   'MTkR:)R1(H %9gym%}&&7#=?@vRkjh+)0Ru%*ZB9vpitz8>P&oL(x/+]z7f7{*J2');
define('NONCE_SALT',       '*|Gs)l]HJ#l(DQG8:AC|]V?JZ1&dLR!2}COfh514?CJx~x}C !}4@qBKRCswJ9rd');

// Security
define('DISALLOW_FILE_EDIT', true);

// Errors
define('WP_DEBUG', false);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}
