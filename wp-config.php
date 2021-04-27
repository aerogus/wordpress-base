<?php

/**
 * Fichier de configuration de WordPress
 * Dépend de l'environnement
 * @TODO se baser sur une variable d'environnement plutôt que le uname du host
 */

switch (php_uname('n'))
{
    // serveur de prod
	case 'rbx.aerogus.net':
    case 'rbx':
        require_once __DIR__ . '/wp-config.prod.php';
        break;

    // environement docker ou de dev local
    default:
        require_once __DIR__ . '/wp-config.dev.php';
        break;
}

require_once ABSPATH . 'wp-settings.php';
