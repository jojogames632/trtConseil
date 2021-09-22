## Avertissement
***
Cette application n'a pas été conçue en suivant le modèle "mobile first".
Son créateur a pris conscience de cette faute, et ne manquera pas d'y penser pour ses futurs projets.
En conséquence, nous considérons que cette application est destinée uniquement pour les ordinateurs.
## Création d'un compte admin
***
Pour créer un compte admin vous avez besoin de télécharger le logiciel MySQLWorkbench (https://www.mysql.com/fr/products/workbench/)
Une fois téléchargé, veuillez remplir les champs suivants :
> Hostname
> Username
> Password
Avec respectivement ces valeurs :
> nnsgluut5mye50or.cbetxkdyhwsb.us-east-1.rds.amazonaws.com
> bn27qdmzezzdsgm2
> ctoi8kyk89cl52mz
Cliquez ensuite sur "Test connection" et vérifiez que vous êtes bien connecté à la base.
### Création d'un mot de passe crypté
***
Allez sur le site bcrypt (https://www.bcrypt.fr/)
Saisissez le mot de passe désiré dans l'encart "Texte à hasher"
Cliquez sur le bouton "Convertir avec bcrypt!"
Votre mot de passe est maintenant hashé, ne fermez pas la page. Vous aurez besoin de ce hash pour créer votre compte admin
### Insertion d'un compte admin en base de données
***
Sur le logiciel MySQLWorkbench, cliquez sur l'onglet "Query 1".
Copiez/collez cette instruction en remplaçant l'email et le mot de passe (le hash créé précédemment) : 
> INSERT INTO user (email, password, is_active, roles) VALUES ('EMAIL', 'MOT DE PASSE HASHÉ', true, '["ROLE_ADMIN"]');
Cliquez sur le premier logo éclair (à droite de la disquette pour enregistrer) afin d'exécuter la requête.
Votre admin est créé !
