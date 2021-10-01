# WordPress

Voici ma méthode de développement et d'architecture pour rendre WordPress un peu plus maintenable.

- On se base sur `docker` pour la phase de développement
- On utilise `composer` le gestionnaire de dépendances `php`. Le cœur de `WordPress` sera lui même une dépendance. Les plugins et thèmes externes seront aussi des dépendances
- On utilise `git` pour versionner le code source qu'on a produit (mais uniquement celui métier)

## Nouvelle installation

- Installer [composer](https://getcomposer.org/) il doit être accessible dans le path

```bash
$ composer --version
Composer version 2.1.3 2021-06-09 16:31:20
```

- Installer [docker](https://docs.docker.com/desktop/mac/install/) et lancer le service sur la machine de développement, un MacOS dans mon cas

- Récupérer ce présent [dépôt](https://github.com/aerogus/wordpress-base) et se positionner dans le répertoire avec le terminal

- Installer les dépendances composer :

```bash
$ composer install
Installing dependencies from lock file (including require-dev)
Verifying lock file contents can be installed on current platform.
Package operations: 4 installs, 0 updates, 0 removals
  - Downloading composer/installers (v1.10.0)
  - Downloading johnpbloch/wordpress-core-installer (2.0.0)
  - Downloading johnpbloch/wordpress-core (5.7.1)
  - Downloading johnpbloch/wordpress (5.7.1)
  - Installing composer/installers (v1.10.0): Extracting archive
  - Installing johnpbloch/wordpress-core-installer (2.0.0): Extracting archive
  - Installing johnpbloch/wordpress-core (5.7.1): Extracting archive
  - Installing johnpbloch/wordpress (5.7.1): Extracting archive
Generating autoload files
1 package you are using is looking for funding.
Use the `composer fund` command to find out more!
```

On peut vérifier si certains paquets proposent des versions plus récentes

```bash
$ composer outdated
Color legend:
- patch or minor release available - update recommended
- major release available - update possible
composer/installers       v1.10.0 v2.0.1 A multi-framework Composer library installer
johnpbloch/wordpress      5.7.1   5.8.1  WordPress is open source software you can use to create a beautiful website, blog, or app.
johnpbloch/wordpress-core 5.7.1   5.8.1  WordPress is open source software you can use to create a beautiful website, blog, or app.
```

C'est le cas dans l'exemple ci-dessus. Si on veut monter de version, on édite le fichier `composer.json` en précisant les versions des dépendances désirées. On exécute `composer upgrade` ce qui mettra à jour le fichier `composer.lock` de la racine ainsi que le répertoire `vendor`.

Maintenant configurons le nom d'hôte. Supposons que notre site de test s'appelle `wordpress.test`. Il faut dire au système qu'il s'agit de notre machine de travail. Éditons le fichier `/etc/hosts` (MacOS ou Linux) ou `C:\Windows\System32\drivers\etc\hosts` (Windows) pour y ajouter la ligne :

```bash
127.0.0.1   wordpress.test
::1         wordpress.test
```

Note: le domaine de premier niveau [.test](https://fr.wikipedia.org/wiki/.test) est réservé à ce type d'usage et n'entrera pas en conflit avec le DNS. Attention, n'utilisez plus le TLD [.dev](https://en.wikipedia.org/wiki/.dev) pour vos développements locaux. Il appartient désormais à Google, la résolution DNS se fera et le mécanisme [HSTS](https://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security) a été activé pour l'ensemble des sous-domaines.

Adapter le `.env` à partir du `.env.dist` pour définir l'environnement ( "dev" ou "prod"), settons le en `dev`.

```bash
cp .env.dist .env
```

## Architecture

Dans l'architecture `docker` proposée, il y a 3 conteneurs :

- **nginx** : le serveur web qui traite les requêtes http(s) sur les ports 80 et 443
- **php** : le langage de programmation, qui tournera en fpm sur le port 9000
- **mariadb** : le système de gestion de base de données qui tournera sur le port 3306

Pour démarrer en parallèle ces 3 conteneurs on exécute :

```bash
docker-compose up
```

Après quelques secondes, notre environnement doit être opérationnel. On peut vérifier que les conteneurs tournent bien avec la commande suivante :

```bash
$ docker ps
CONTAINER ID   IMAGE                COMMAND                  CREATED          STATUS          PORTS                                                                      NAMES
3ade313227d9   wordpress-base_php   "docker-php-entrypoi…"   24 seconds ago   Up 22 seconds   9000/tcp                                                                   wordpress_php
5baca0a6b6c2   nginx:1.21.3         "/docker-entrypoint.…"   24 seconds ago   Up 23 seconds   0.0.0.0:80->80/tcp, :::80->80/tcp, 0.0.0.0:443->443/tcp, :::443->443/tcp   wordpress_nginx
ac3f2a207e59   mariadb              "docker-entrypoint.s…"   24 seconds ago   Up 23 seconds   3306/tcp                                                                   wordpress_mariadb
```

Le serveur web doit répondre à l'url `http://wordpress.test` et la page d'installation s'afficher.

Une fois le formulaire d'inscription WordPress saisi, votre user est admin de l'instance et peut se connecter au tableau de bord.

Note: Pour que le front marche, il faut au moins un thème. Vous pouvez importer un thème par défaut

```bash
cp -R ./wp/wp-content/themes/twentytwentyone ./wp-content/themes
```

Mais l'idée est plutôt de développer son propre thème dans le répertoire `wp-content/themes/mon-theme`
et de désactiver l'exclusion de versionnement de ce chemin en l'ajoutant dans le `.gitignore` (cf. les commentaires)

## Description de l'arborescence

- **conf** : fichiers de configuration des conteneurs docker
- **log** : les conteneurs écriront leur log ici
- **vendor** : stockage des dépendances composer
- **wp** : le coeur de WordPress s'installe ici
- **wp-content** : vos thèmes, extensions seront ici
- **.env** : le contexte d'exécution
- **.gitignore** : exclusion des fichiers "user-generated" et des dépendances
- **composer.json** : la liste des dépendances php
- **docker-compose.yml** : la description des conteneurs
- **index.php** : le lanceur custom de WordPress qui indique d'utiliser le coeur dans le sous répertoire wp
- **LICENSE** : c'est libre, vous pouvez réutiliser ces scripts
- **README.md** : le présent fichier
- **wp-config.php** : suivant le contexte, chargera le bon fichier des paramètres

## Installer un plugin

Supposons que l'on veuille installer l'extension `disable-emojis`. Le prérequis est qu'il soit référencé dans [wpackagist](https://wpackagist.org/).

```bash
$ composer require wpackagist-plugin/disable-emojis
Using version ^1.7 for wpackagist-plugin/disable-emojis
./composer.json has been updated
Running composer update wpackagist-plugin/disable-emojis
Loading composer repositories with package information
Updating dependencies
Lock file operations: 1 install, 0 updates, 0 removals
  - Locking wpackagist-plugin/disable-emojis (1.7.3)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 1 install, 0 updates, 0 removals
  - Downloading wpackagist-plugin/disable-emojis (1.7.3)
  - Installing wpackagist-plugin/disable-emojis (1.7.3): Extracting archive
Generating autoload files
1 package you are using is looking for funding.
Use the `composer fund` command to find out more!
```

Cette commande a fait les choses suivantes :
- ajout d'une ligne dans `composer.json` (que vous devrez versionner/commiter)
- téléchargement/installation du plugin dans `wp-content/plugins/disable-emojis` (le répertoire ne sera pas versionné)

## Déinstaller un plugin

Mécanisme inverse :

```bash
$ composer remove wpackagist-plugin/disable-emojis
./composer.json has been updated
Running composer update wpackagist-plugin/disable-emojis
Loading composer repositories with package information
Updating dependencies
Lock file operations: 0 installs, 0 updates, 1 removal
  - Removing wpackagist-plugin/disable-emojis (1.7.3)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 0 installs, 0 updates, 1 removal
  - Removing wpackagist-plugin/disable-emojis (1.7.3)
Deleting /Users/gus/workspace/wordpress-base/wp-content/plugins/disable-emojis - deleted
Generating autoload files
1 package you are using is looking for funding.
Use the `composer fund` command to find out more!
```

## Avantages

Meilleure séparation du code métier des dépendances. Moins lourd aussi dans le dépôt git.

En cas d'attaque (ça peut arriver !), un `git status` pourra mettre en évidence si des fichiers sources ont été modifiés.

Dans le doute, si on veut refaire une installation propre des dépendances, à partir du répertoire de base, on peut exécuter :

```bash
rm -Rf vendor
composer install
```

## Inconvénients

Il n'y a pas de serveur FTP d'installé donc pas possible d'installer thèmes/plugins par ce biais. C'est une architecture orienté développeur 🤓.

Ça ne remplace pas un système de sauvegarde.
