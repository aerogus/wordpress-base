# WordPress

Voici ma méthode d'architecture pour rendre WordPress plus maintenable.

- On se base sur `docker` pour la phase de développement
- On utilise `composer` le gestionnaire de dépendances php. Le coeur de WordPress sera lui même une dépendance. Les plugins et thèmes externes seront aussi des dépendances
- On utilise `git` pour versionner tout le code source (uniquement celui métier, qu'on a produit)

## Nouvelle installation 

- Installer composer, dans le path
- Installer docker et lancer le daemon sur la machine de développement

installation des dépendances

```
composer install
```

vérifiez que les paquets sont à jour

```
composer outdated
```

éditez au besoin le `compose.json` et exécutez `composer upgrade`, ceci mettra à jour les dépendances dans `vendor` ainsi que le fichier `composer.lock`

Éditez le fichier `/etc/hosts` (MacOS ou Linux) et ajouter y la ligne :

```
wordpress.test 127.0.0.1
```

Lancer les containers docker (nginx + php + mysql)

```
docker-compose up
```

puis avec un navigateur allez sur `http://wordpress.test`

La page d'installation doit se lancer

## Avantages

En cas d'attaque (ça peut arriver !), un `git status` pourra mettre en évidence si des fichiers sources ont été modifiés

Dans le répertoire de base, faire un `rm -Rf vendor && composer install` refera une install clean des dépendances

