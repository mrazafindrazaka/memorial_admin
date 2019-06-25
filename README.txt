Admin du site memorial.nancy.fr

Modification de fonctionnalité dans le système d'import-export

Avant:

	- Export de données désorganisées (Impossible d'importer les données exportées)
	- Importation de csv générer par excel 2003
	- Accumulation à l'importation
	- Retour à l'ancienne version de base manuellement
	- Erreur de code

Après:

	- Export de données organisées (Possible d'importer les données exportées)
	- Importation de csv générer par LibreOffice
	- Sauvegarde de la base actuelle + vider avant l'importation
	- Retour à l'ancienne version de base plus facile grâce à la sauvegarde lors de l'importation
	- Correction d'incohérence dans le code

Pour mettre en place cette nouvelle fonctionnalité:

     	- Par mesure de sécurité, faire une sauvegarde du dossier src/AppBundle/ et du fichier app/config/config.yml
     	- Remplacer le dossier src/AppBundle/ par le dossier AppBundle
     	- Remplacer le fichier app/config/config.yml par le fichier config.yml
