# Projet : Système de Mesure de Glycémie Connecté

## Description
Ce projet, réalisé dans le cadre de la troisième année de Licence Informatique à **CY Cergy Paris Université**, vise à développer un système complet de gestion des données de glycémie des patients atteints de diabète. Il combine capteurs connectés, base de données relationnelle et échanges réseau sécurisés pour faciliter le suivi médical.

## Fonctionnalités
- **Stockage sécurisé des données des patients** dans une base PostgreSQL.
- **Transmission en temps réel** des mesures entre capteurs et serveur via des sockets.
- **Interface web dynamique** pour les médecins, permettant :
  - Consultation des mesures des patients.
  - Ajout de commentaires médicaux.
  - Gestion des préférences et notifications.
- **Alertes automatiques** en cas de valeurs de glycémie critiques.

## Architecture
1. **Capteurs connectés** : Mesurent en continu la glycémie des patients.
2. **Sockets client-serveur** : Assurent la transmission des données.
3. **Base de données relationnelle PostgreSQL** : Centralise et structure les informations.
4. **Site web dynamique** : Fournit une interface intuitive pour les utilisateurs (patients et médecins).

## Structure du Projet
- **Code client (Python)** : Communication avec le serveur pour l’envoi des mesures.
- **Code serveur (Java)** : Traitement des données et interface avec la base PostgreSQL.
- **Base de données** : Conception complète (MCD/MLD) avec tables pour patients, médecins, mesures, alertes, etc.
- **Site web dynamique** : Gestion des sessions, affichage des données patients et contrôle des alertes.

## Prérequis
- **Langages** : Python 3.x, Java 8+
- **Base de données** : PostgreSQL (pgAdmin4 recommandé)
- **Environnement web** : Node.js ou équivalent pour le backend du site.
- **Librairies utilisées** :
  - Python : `socket`, `requests`
  - Java : `java.net`, `java.sql`
  - PostgreSQL : Types personnalisés, clés étrangères

## Installation
### 1. Clonez le dépôt :
```bash
git clone https://github.com/votre-utilisateur/projet-glycemie.git
cd projet-glycemie
```

### 2. Configurez la base de données :
- Importez le fichier SQL de création des tables fourni dans le dossier `database`.
- Configurez les identifiants de connexion dans `Server.java`.

### 3. Lancez le serveur :
Compilez et exécutez le fichier `Server.java`.

```bash
javac Server.java
java Server
```

### 4. Démarrez le client :
Exécutez le script Python `client.py`.

```bash
python client.py
```

### 5. Accédez au site web :
Démarrez le backend du site et accédez à l'interface via `http://localhost:3000`.

## Contributions
- **BANDOIS–CERVEAU Henri-Emmanuel**
- **BENCHOUBANE Sid-Ali**
- **HUANG Yupan**

## Améliorations futures
- Optimisation de la sécurité des données.
- Extension des fonctionnalités du site web.
- Support multilingue pour une utilisation internationale.

## Licence
[HEBC License](HEBC)
