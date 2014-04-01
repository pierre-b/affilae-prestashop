Module Prestashop Affilae
================================

Le module Affilae pour Prestashop vous permet d'intégrer les scripts de tracking sans avoir à faire appel à un développeur.

Il vous permet également de pouvoir appliquer des Règles de Commission par catégories de produits.


Installation
-------------------------

1.  Téléchargez le module au format .zip ici: [https://github.com/pierre-b/affilae-prestashop/archive/master.zip](https://github.com/pierre-b/affilae-prestashop/archive/master.zip)

2.  Une fois connecté sur votre backoffice, rendez-vous sur l'onglet "Modules", 
    puis cliquez sur le bouton "Ajouter un module" et sélectionnez le fichier .zip 
    que vous venez de télécharger.

3.  Cliquez sur le bouton "Installer" du module Affilae (Publicité & marketing)

4.  Cliquez sur le bouton "Configurer" du module Affilae

5.  Copiez-collez l'intégralité du script de tracking présent sur la "Config." 
    de votre programme d'affiliation et validez.

6.  Ajoutez une nouvelle "Règle de commissionnement" au module Prestashop 
    et copiez-collez la clé fournie sur la page "Code de conversion" 
    (onglet Config.) de votre programme d'affiliation.
    Répétez cette étape pour chaque Règle de commission que vous avez.


Mise à jour
-------------------------
1.  Téléchargez le module au format .zip ici: [https://github.com/pierre-b/affilae-prestashop/archive/master.zip](https://github.com/pierre-b/affilae-prestashop/archive/master.zip)

2.  Désinstallez le module Affilae de Prestashop

3.  Réinstallez le nouveau module Affilae

4.  Reconfigurez le module (script de tracking + règles)


Changelog
-------------------------

1.4  Le bug de commissionnement par catégorie de produit a été fixé

1.3  Le paramètre "customer" est désormais remonté dans les conversions pour permettre le "Revenue Share"

1.2  Le javascript est désormais affiché dans le footer ({$HOOK_FOOTER}).
     Le bug "Undefined variable: trackings" a été fixé.

1.1  Prise en charge des nouvelles clés des Règles de Commission (Affilae v2).