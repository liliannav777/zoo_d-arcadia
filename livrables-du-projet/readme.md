# Zoo d'Arcadia

Ceci est un site web presentant le site web du zoo d'Arcadia, dans le cadre de mon Examen en Cours de Formation

Assurez-vous d'avoir les éléments suivants installés sur votre système :

## Prérequis 
- PHP 
- Composer
- Yarn 
- Serveur de base de données compatible (MySQL, PostgreSQL, etc.)
- MongoDB 

## Installation 

1. **Clonage du repository dans le dossier souhaité:** 

git clone https://github.com/liliannav777/zoo_d-arcadia

2. **Configuration des variables d'environnement :** 
Créez un fichier .env.local à la racine du projet et configurez les variables d'environnement nécessaires :
DATABASE_URL=mysql://user:password@localhost:3306/nom_de_la_base_de_donnees
MONGODB_URL=mongodb://localhost:27017/nom_de_la_base_mongodb
MAILER_DSN=smtp://localhost:25

3. **Installation des dépendances PHP avec Composer :** 

composer install

4. **Création de la base de données :** 
Assurez-vous que la base de données spécifiée dans DATABASE_URL existe. Si elle n'existe pas, vous pouvez la créer avec la commande suivante :

php bin/console doctrine:database:create

5. **Création et migration des données sur la base de données :**

Générer une migration :

php bin/console make:migration

Appliquer les migrations à la base de données :

php bin/console doctrine:migrations:migrate

6. **Installation des dépendances JavaScript avec Yarn:**

yarn install

yarn dev

7. **Lancement de l'application:**
Démarrez le serveur Symfony : Utilisez la commande suivante pour démarrer le serveur Symfony intégré :

php bin/console server:run

Cela lancera le serveur de développement Symfony. Par défaut, il sera accessible à l'adresse http://localhost:8000 dans votre navigateur.

**Configurations supplémentaires pour avoir accès à toutes les fonctionnalités de l'appli**

Pour créer le compte adminsitrateur, il faut le créer manuellement dans la base de donnnées, en créant un role "ROLE_ADMIN" dans la table role, et en affectant ce role a l'utilisateur crée avec un username et un password. 
Avec cet utilisateur adminsitrateur vous pourrez remplir toutes les sections (services, horaires, habitats et zoo), et accéder au dashboard administrateur.
En ce qui concerne le formulaire de création d'utilisateur qui se trouve sur le dashboard administrateur, il vous faudra au préalable créer 2 rôles, le "ROLE_EMPLOYE" et le "ROLE_VETERINAIRE", ce qui vous permettra de créer des utilisateurs avec un de ces 2 rôles et ainsi accéder aux dashboard employé ou au dahboad vétérinaire.

