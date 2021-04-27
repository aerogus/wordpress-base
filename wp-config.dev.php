<?php

/**
 * conf de dev
 */

// Chemins
$scheme = 'http://';
if (!empty($_SERVER['HTTPS']) || php_sapi_name() === 'cli') {
    $scheme = 'https://';
}
define('WP_HOME',        $scheme . 'wordpress.test');
define('WP_SITEURL',     $scheme . 'wordpress.test/wp');
define('WP_CONTENT_URL', $scheme . $_SERVER['HTTP_HOST'] . '/wp-content');
define('WP_CONTENT_DIR', __DIR__ . '/wp-content');

// MySQL
define('DB_NAME',     'wordpress');
define('DB_USER',     'wordpress');
define('DB_PASSWORD', 'changeme');
define('DB_HOST',     'mysql');
define('DB_CHARSET',  'utf8');
define('DB_COLLATE',  '');
$table_prefix  = 'wp_';

// i18n
define('WPLANG', 'fr_FR');

// Keys
// @see https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'SwRWEqKpM|;3:25,S[5-6h~Et-b;+d.[T[?%RD*mxOnK[4wWtfPLHb|@TWQ=?5i7');
define('SECURE_AUTH_KEY',  '4zSUNo:wU+oW4+#N+A(8 94dazu(^n}WP1s=[EUtbvB+2m*/v,&|H|1k3{iiziVR');
define('LOGGED_IN_KEY',    '}IMv|Y;8e:+8b 2.e-c,#>dX*R59Oz$=W:s~!G>ZTQ=DxlvR&u3cI=$3**FL/`CV');
define('NONCE_KEY',        'W{bhvtWF8Xi[21fF[V}|Qm)k{QaY/M1q11g28+3:jK>(%)rQ^P~Ra^Y9 Bg*AX0|');
define('AUTH_SALT',        '954*yD0_on|7^M}U]2(<`wx,RFr[kzWn >UOZ*+)Q|3K/HuPc.o:h+lZqZ{QVO*;');
define('SECURE_AUTH_SALT', '.2{g-XRSO)&>k_A+Vp>{lt1u}?w,@TDY_P921/G`UoMy;E#AR/9^g BxjWQ-9VPM');
define('LOGGED_IN_SALT',   '2n8@tOxJQH4zFZL#5kZtHwd+bO#.-jp6a5UVN(=SK7-HGyzd`H]Uh;k9DT`KA|U4');
define('NONCE_SALT',       'OC}Tf^=;`yt?{K%nHJ<dFTCZEd7BAk9Z6050|#+b:Nqy0eyyVi.jaTrvc|Cr83<U');

// Security
define('DISALLOW_FILE_EDIT', true);

// Errors
define('WP_DEBUG', true);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

