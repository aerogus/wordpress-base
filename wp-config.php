<?php

/**
 * Fichier de configuration de WordPress
 * Dépend de l'environnement
 */

switch (getenv('TARGET'))
{
    case 'prod':
        require_once __DIR__ . '/wp-config.prod.php';
        break;
    case 'dev':
        require_once __DIR__ . '/wp-config.dev.php';
        break;
    default:
        die('unknown env');
        break;
}

require_once ABSPATH . 'wp-settings.php';
