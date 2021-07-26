# Formation Symfony 5 Klee - Stack docksal et WSL2

# Guide d'installation & d'utilisation

## - Pré-requis -

Si vous disposez déjà d'un environnement sous une distribution Linux avec Docksal d'installé, vous pouvez passer directement à la partie **| Lancer le projet Docksal** ci-dessous. 

Sinon suivez chaque étape pour paramétrer votre VM en WSL2 une fois ceci fait, vous n'aurez plus à le faire pour récupérer et lancer d'autres projets sous Docksal à l'avenir. Il vous suffira de les cloner à côté de celui-ci. 

En cas de pépin n'hésitez pas à solliciter l'équipe sur Teams.

## | Installation d'une VM locale en WSL2 + Docker Desktop + Docksal

### 1. WSL2
#### a.	Activation du WSL2
**Fini virtual box et l'hyper V, vous pouvez désormais activer le tout à fait viable WSL2 sur votre windows**

En résumé, vérifiez que Windows est à jour, ouvrez le terminal de commande (CMD) en admin windows puis :
* i.	Tapez :

  ``dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart``
* ii.	Ensuite :

  ``dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart``
* iii.	Téléchargez et installez à la main :
  https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi

* iv.	Et enfin :

  ``wsl --set-default-version 2``

*(ce qui correspond aux step 1 à 5 de https://docs.microsoft.com/en-us/windows/wsl/install-win10)*

#### b.	Installez l'app Ubuntu depuis le store
Fermez le terminal de windows définitivement, et installez maintenant l’app Ubuntu depuis le Windows Store ( https://www.microsoft.com/en-us/p/ubuntu-1804-lts/9n9tngvndl3q?activetab=pivot:overviewtab ), cliquez sur « non merci » dans la popup lors du dl, il se lancera quand même

### 2.	Docker for windows :
#### a.	Installation
Téléchargez et installez le : https://www.docker.com/products/docker-desktop (si vous ne l’avez pas déjà)

#### b. Exposer le daemon
Dans Settings > Général cochez : Expose daemon on localhost2375 without TLS et cliquez sur Apply & restart

#### c. Sélectionnez le storage
Ensuite spécifiez la distribution à laquelle vous souhaitez intégrer docker for windows (cochez ubuntu) :

### 3.	Paramétrer la VM

#### a. Installation de Docksal sur votre VM locale
* Histoire d’être sereins, redémarrez le pc puis lancez docker windows
* Lancez ensuite le shell Ubuntu depuis votre pc, vous devriez être connecté en tant que root@NOMDEVOTREPC dans le /home fournit par le docker for windows
* Installez Docksal :

  ``DOCKER_NATIVE=1 bash <(curl -fsSL https://get.docksal.io)``

* Et lancez les confs suivantes :
```
fin config set --global DOCKSAL_VHOST_PROXY_IP="0.0.0.0"
fin system reset
```

Les fichiers de votre VM sont disponibles depuis votre explorateur windows au chemin : \\wsl$\Ubuntu-18.04\home

## | Lancer le projet Docksal
Partie projet Symfony sous Docksal
- Récupérez mon projet (clonez le par exemple) et collez le dans votre **/home** (ou racine de vhost) :
- Allez dans le repo cloné *"/formation-sf5"*, tapez ``fin init``
- Changez la valeur de DATABASE_URL dans le fichier **.env** (racine) ``DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"``
- Faites un ``fin p restart``
- Accédez à l’url du projet


**Pensez à versionner votre projet dans votre propre répertoire distant**

## | Utiliser le projet Docksal

Pour aller plus loin dans votre exploration de Docksal :
Quick tips :

**Utiliser composer** : ```fin composer COMMANDE_COMPOSER```

**Utiliser le CLI Symfony** : ```fin symfony COMMANDE_COMPOSER```

**Utiliser la DB** : ```fin db COMMANDE_DB```

**Utiliser NodejS** : ```fin run npm COMMANDE_NPM (globalement, run permet d’executer des commandes dans le CLI)```

**Utiliser PhpUnit** : ```fin phpunit```

**Entrer dans le container CLI directement pour taper ses commandes à la main (non recommandé en terme de BP)** : ```fin bash```

## - En cas d'erreur -

Voici quelques erreurs que nous avons eu à traiter :

* **Erreur au démarrage du projet Docksal :**
    * **Cas 1** : Le projet ne démarre pas :
        * Lors du premier usage du projet, pensez à faire un ``fin init`` depuis le répertoire cloné, si suite à celui-ci le site n'est pad accessible, faites y un ``fin p start``
        * Vous avez déjà démarré le projet avant, tentez un ``fin system start`` pour démarrer les 3 containers système de Docksal (DNS, vhost-proxy, ssh-agent)
    * **Cas 2** : Le container DNS ne démarre pas ce qui résulte en ``Error 500 - Ports are not available: listen tcp 192.168.64.100:34 can't bind on the specified endpoint"``
      Vérifiez si vous avez des mises à jour Windows, dans tous les cas redémarrez le pc et réessayez ensuite
* **Le projet semble démarré mais j'ai une timeout en accédant à son url sur le navigateur**
    * Ajoutez l'url du projet pointant vers 127.0.0.1 dans votre fichier **hosts**
* **Soucis de connexion à la DB :**
    * Vérifiez que vous avez bien ``DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"`` comme valeur dans votre **.env** (racine)
    
En cas de besoin n'hésitez pas à solliciter l'équipe sur Teams.