# WordPress

Voici ma méthode de développement et d'architecture pour rendre WordPress un peu plus maintenable.

- On se base sur `docker` pour la phase de développement
- On utilise `composer` le gestionnaire de dépendances `php`. Le cœur de `WordPress` sera lui même une dépendance. Les plugins et thèmes externes seront aussi des dépendances
- On utilise `git` pour versionner le code source qu'on a produit, mais uniquement celui métier

## Nouvelle installation 

- Installer `composer` il doit être accessible dans le path

```bash
$ composer --version
Composer version 2.1.3 2021-06-09 16:31:20
```

- Installer `docker` et lancer le daemon sur la machine de développement

- Récupérer ce présent dépôt et se positionner dans le répertoire, avec le terminal

- Installer les dépendances

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

On peut vérifier si certains paquets ont été mis à jour

```bash
$ composer outdated
Color legend:
- patch or minor release available - update recommended
- major release available - update possible
composer/installers       v1.10.0 v2.0.1 A multi-framework Composer library installer
johnpbloch/wordpress      5.7.1   5.8.1  WordPress is open source software you can use to create a beautiful website, blog, or app.
johnpbloch/wordpress-core 5.7.1   5.8.1  WordPress is open source software you can use to create a beautiful website, blog, or app.
```

C'est le cas dans l'exemple ci-dessus. Si on veut monter de version, on peut éditer le fichier `composer.json` en précisant les versions des dépendances désireées. On exécute `composer upgrade` pour mettre à jour les fichier dans le répertoire `vendor` ainsi que le fichier `composer.lock` de la racine.

Maintenant configurons le nom d'hôte. Supposons que notre site de test s'appelle `wordpress.test`. Il faut dire au système qu'il s'agit de notre machine de travail. Éditons le fichier `/etc/hosts` (MacOS ou Linux) pour y ajouter la ligne :

```bash
127.0.0.1   wordpress.test
::1         wordpress.test
```

Note: le domaine de premier niveau [.test](https://fr.wikipedia.org/wiki/.test) est réservé à ce type d'usage et n'entrera pas en conflit avec le DNS. Attention, n'utilisez pas/plus le TLD [.dev](https://en.wikipedia.org/wiki/.dev) pour vos développement locaux ! Il appartient désormais à Google, des sites publics l'utilisent, et le mécanisme [HSTS](https://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security) a été activé pour l'ensemble des sous-domaines.

Adapter le `.env` à partir du `.env.dist` pour définir l'environnement (dev|prod), settons le en `dev`.

```bash
cp .env.dist .env
```


## Architecture

Dans l'architecture `docker` proposée, il y a 3 containers :

- nginx : le serveur web qui traite les requêtes http(s) sur les ports 80 et 443
- php : le langage de programmation, qui tournera en fpm sur le port 9000
- mariadb : le système de gestion de base de données qui tournera sur le port 3306

Pour lancer en parallèle ces containers docker on exécute :

```bash
docker-compose up
```

Après quelques secondes, notre environnement devrait être opérationnel. On peut vérifier que nos 3 conteneurs sont opérationnels.

```bash
$ docker ps
CONTAINER ID   IMAGE                COMMAND                  CREATED          STATUS          PORTS                                                                      NAMES
3ade313227d9   wordpress-base_php   "docker-php-entrypoi…"   24 seconds ago   Up 22 seconds   9000/tcp                                                                   wordpress_php
5baca0a6b6c2   nginx:1.21.3         "/docker-entrypoint.…"   24 seconds ago   Up 23 seconds   0.0.0.0:80->80/tcp, :::80->80/tcp, 0.0.0.0:443->443/tcp, :::443->443/tcp   wordpress_nginx
ac3f2a207e59   mariadb              "docker-entrypoint.s…"   24 seconds ago   Up 23 seconds   3306/tcp                                                                   wordpress_mariadb
```

Le serveur web devrait répondre à l'url `http://wordpress.test` et la page d'installation s'afficher.

Une fois le formulaire d'inscription saisi, votre user est admin de l'instance et peut se connecter au tableau de bord.

Il n'y a pas de serveur FTP d'installé donc pas possible d'installer thèmes/plugins par ce biais.

## Avantages

En cas d'attaque (ça peut arriver !), un `git status` pourra mettre en évidence si des fichiers sources ont été modifiés.

Et dans le doute, si on veut refaire une installation propre des dépendances, à partir du répertoire de base, on peut exécuter `rm -Rf vendor && composer install` (mini coupure de service à prévoir).
