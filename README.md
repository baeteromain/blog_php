# PHP - P5 Openclassrooms - Créez votre premier blog en PHP

## Installation du projet :building_construction: 
Telechargez directement le projet ou effectuez un ```git clone``` via la commande suite : 

```git clone https://github.com/baeteromain/blog_php.git```

En suivant, effectuez un ```composer install``` à la racine du projet permettant d'installer les dépendances utilisées dans ce projet.

## CSS :lipstick:

Le thème graphique du blog à été entièrement réalisé via le framework **TACHYONS**
( https://tachyons.io/ )

## Base de données :nerd_face: 
Modifiez le fichier ```/config/database.php``` avec vos informations spécifiques à votre base de données, voir l'exemple ci-dessous : 

```
const HOST = ''; // le host de votre projet
const DB_NAME = ''; // le nom de la base de donnée
const CHARSET = 'utf8';
const DB_HOST = 'mysql:host='.HOST.';dbname='.DB_NAME.';charset='.CHARSET;
const DB_USER = ''; // l'identifiant d'accès
const DB_PASS = ''; // le mot de passe d'accès
```

Le projet est fournit avec une base de données déjà pré-remplie d'articles, de commentaires, ainsi qu'un **compte administrateur** :

* Nom d'utilisateur : **admin**
* Mot de passe : **admin**

et d'un **compte utilisateur ( abonné )** :

* Nom d'utilisateur : **utilisateur_test**
* Mot de passe : **azerty**


permettant d'être fonctionnel et de tester le blog.
Libre à vous de supprimer les données et d'ajouter vos propres données par la suite.


:warning: Il est important de garder la structure de la base de données identique à celle du projet. Pour ce faire, vous trouverez dans le dossier ```/config``` un fichier sql nommé ```blog_php_br.sql```. Il ne vous reste plus qu'à importer ce fichier via votre SGBD.

## Mail :e-mail: 

### En développement : :mailbox: 

Dans le dossier ```/config```, vous trouverez le fichier ```mailer_config.php```, celui-ci est pré-rempli pour une utilisation en local, merci de simplement ajouter votre ***EMAIL_USERNAME*** qui correspond à l'adresse email qui enverra les mails depuis le blog : 
```
const EMAIL_HOST = 'localhost';
const EMAIL_PORT = 1025;
const EMAIL_USERNAME = '' // adresse email à compléter;
const EMAIL_PASSWORD = '';
const EMAIL_ENCRYPTION = 'ssl';
```

Si vous voulez tester les mails en local, installer MailDev ( lien vers la documentation https://maildev.github.io/maildev/ ), pour son installation il vous faudra NodeJs au préalable ( https://nodejs.org/en/download/ ) ainsi que npm ( https://www.npmjs.com/get-npm ).

Une fois l'installation faite, lancer MailDev dans un terminal via la commande : 
```maildev```
Vous devriez voir apparaitre un retour vous indiquant l'addresse de la page web permettant de voir les mails

```
MailDev webapp running at http://0.0.0.0:1080
MailDev SMTP Server running at 0.0.0.0:1025
```

Les mails envoyés par le blog sont tous redirigés vers la page web de MailDev ( si vous êtes en localhost à l'adresse suivant : ```http://localhost:1080/```)
### En production : :email: 
Pour l'envoi de mails en production, modifier le fichier ```/config/mailer_config.php``` suivant les informations de votre serveur mail, comme suivant : 
```
const EMAIL_HOST = '' // le host de votre serveur mail;
const EMAIL_PORT = 1025; // à modfier en fonction du port de votre serveur mail
const EMAIL_USERNAME = '' // l'identifiant de votre serveur mail;
const EMAIL_PASSWORD = '' // le password de votre serveur mail;
const EMAIL_ENCRYPTION = 'ssl' // à modifier en fonction de votre serveur mail;
```

Allez au fichier ```/src/core/Mailer.php```, puis décommenter les deux lignes suivantes : 

```
// $this->Username = EMAIL_USERNAME;                    
// $this->Password = EMAIL_PASSWORD; 
```

Les mails sont maintenants reçus et transmis par votre serveur mail.
## Serveur de développement :serious_face_with_symbols_covering_mouth: 
Démarrage du projet en local en pointant sur le dossier racine du projet puis effectuez la commande suivante :

```php -S localhost:8000 -t public``` 

Le blog est maintenant disponible à l'addresse local.

## Contexte 

Ça y est, vous avez sauté le pas ! Le monde du développement web avec PHP est à portée de main et vous avez besoin de visibilité pour pouvoir convaincre vos futurs employeurs/clients en un seul regard. Vous êtes développeur PHP, il est donc temps de montrer vos talents au travers d’un blog à vos couleurs.

## Description du besoin

Le projet est donc de développer votre blog professionnel. Ce site web se décompose en deux grands groupes de pages :

* les pages utiles à tous les visiteurs ;
* les pages permettant d’administrer votre blog.

Voici la liste des pages qui devront être accessibles depuis votre site web :

* la page d'accueil ;
* la page listant l’ensemble des blog posts ;
* la page affichant un blog post ;
* la page permettant d’ajouter un blog post ;
* la page permettant de modifier un blog post ;
* les pages permettant de modifier/supprimer un blog post ;
* les pages de connexion/enregistrement des utilisateurs.
* Vous développerez une partie administration qui devra être accessible uniquement aux utilisateurs inscrits et validés.

Les pages d’administration seront donc accessibles sur conditions et vous veillerez à la sécurité de la partie administration.

Commençons par les pages utiles à tous les internautes.

Sur la page d’accueil, il faudra présenter les informations suivantes :

* votre nom et votre prénom ;
* une photo et/ou un logo ;
* une phrase d’accroche qui vous ressemble (exemple : “Martin Durand, le développeur qu’il vous faut !”) ;
* un menu permettant de naviguer parmi l’ensemble des pages de votre site web ;
* un formulaire de contact (à la soumission de ce formulaire, un e-mail avec toutes ces informations vous sera envoyé) avec les champs suivants :
    * nom/prénom,
    * e-mail de contact,
    * message,
    * un lien vers votre CV au format PDF ;
    * et l’ensemble des liens vers les réseaux sociaux où l’on peut vous suivre (GitHub, LinkedIn, Twitter…).

Sur la page listant tous les blogs posts (du plus récent au plus ancien), il faut afficher les informations suivantes pour chaque blog post :

* le titre ;
* la date de dernière modification ;
* le châpo ;
* et un lien vers le blog post.

Sur la page présentant le détail d’un blog post, il faut afficher les informations suivantes :

* le titre ;
* le chapô ;
* le contenu ;
* l’auteur ;
* la date de dernière mise à jour ;
* le formulaire permettant d’ajouter un commentaire (soumis pour validation) ;
* les listes des commentaires validés et publiés.

Sur la page permettant de modifier un blog post, l’utilisateur a la possibilité de modifier les champs titre, chapô, auteur et contenu.

Dans le footer menu, il doit figurer un lien pour accéder à l’administration du blog.

## Contraintes

Cette fois-ci, nous n’utiliserons pas WordPress. Tout sera développé par vos soins. Les seules lignes de code qui pourront provenir d’ailleurs seront celles du thème Bootstrap, que vous prendrez grand soin de choisir. La présentation, ça compte ! Il est également autorisé d’utiliser une ou plusieurs librairies externes à condition qu’elles soient intégrées grâce à Composer.

Attention, votre blog doit être navigable aisément sur un mobile (téléphone mobile, phablette, tablette…). C’est indispensable ! C’est indispensable :D
Nous vous conseillons vivement d’utiliser un moteur de templating tel que Twig, mais ce n’est pas obligatoire.

Sur la partie administration, vous veillerez à ce que seules les personnes ayant le droit “administrateur” aient l’accès ; les autres utilisateurs pourront uniquement commenter les articles (avec validation avant publication).

**Important** : Vous vous assurerez qu’il n’y a pas de failles de sécurité (XSS, CSRF, SQL Injection, session hijacking, upload possible de script PHP…).

Votre projet doit être poussé et disponible sur GitHub. Je vous conseille de travailler avec des pull requests. Dans la mesure où la majorité des communications concernant les projets sur GitHub se font en anglais, il faut que vos commits soient en anglais.

Vous devrez créer l’ensemble des issues (tickets) correspondant aux tâches que vous aurez à effectuer pour mener à bien le projet.

Veillez à bien valider vos tickets pour vous assurer que ceux-ci couvrent bien toutes les demandes du projet. Donnez une estimation indicative en temps ou en points d’effort (si la méthodologie agile vous est familière) et tentez de tenir cette estimation.

L’écriture de ces tickets vous permettra de vous accorder sur un vocabulaire commun. Il est fortement apprécié qu’ils soient écrits en anglais !

## Nota Bene

Votre projet devra être suivi via SymfonyInsight, ou Codacy pour la qualité du code. Vous veillerez à obtenir une médaille d'argent au minimum (pour SymfonyInsight). En complément, le respect des PSR est recommandé afin de proposer un code compréhensible et facilement évolutif.

Si vous n’arrivez pas à vous décider sur le thème Bootstrap, en voici un qui pourrait vous convenir http://bit.ly/2emOTxY (source : startbootstrap.com).

Dans le cas où une fonctionnalité vous semblerait mal expliquée ou manquante, parlez-en avec votre mentor afin de prendre une décision ensemble concernant les choix que vous souhaiteriez faire. Ce qui doit prévaloir doit être les délais.
