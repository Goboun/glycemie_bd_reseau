CREATE TYPE notification_preference AS ENUM ('EMAIL', 'SMS');
CREATE TABLE Utilisateur (
    id_utilisateur VARCHAR(100) PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    date_naissance DATE NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    telephone CHAR(10) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(20) NOT NULL CHECK (CHAR_LENGTH(mot_de_passe) > 8),
    date_creation DATE NOT NULL,
    photo_identite BYTEA NOT NULL,
    sexe CHAR(1) NOT NULL CHECK (sexe IN ('H', 'F')),
    preferences_notifs notification_preference NOT NULL
);

CREATE TABLE Capteur (
    id_capteur VARCHAR(100) PRIMARY KEY,
    date_installation DATE NOT NULL,
    duree_vie INT NOT NULL
);

CREATE TYPE blood_group AS ENUM ('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-');
CREATE TABLE Patient (
    id_patient VARCHAR(100) PRIMARY KEY,
    groupe_sanguin blood_group NOT NULL,
    poids FLOAT NOT NULL CHECK (poids > 2.0 AND poids < 500.0),
    taille FLOAT NOT NULL CHECK (taille > 20.0 AND taille < 300.0),
    adresse VARCHAR(255),
    id_capteur VARCHAR(100),
    FOREIGN KEY (id_patient) REFERENCES Utilisateur(id_utilisateur),
    FOREIGN KEY (id_capteur) REFERENCES Capteur (id_capteur)
);

CREATE TABLE Medecin (
    id_medecin VARCHAR(100) PRIMARY KEY,
    specialisation VARCHAR(100) NOT NULL,
    numero_licence VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_medecin) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Dossier (
    id_dossier VARCHAR(100) PRIMARY KEY,
    date_creation DATE NOT NULL,
    date_maj DATE NOT NULL,
    historique_medical TEXT,
    allergies TEXT,
    document_medical BYTEA,
    id_patient VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_patient) REFERENCES Patient (id_patient)
);

CREATE TABLE Mesureur (
    id_mesureur VARCHAR(100) PRIMARY KEY,
    date_fabrication DATE NOT NULL CHECK (date_fabrication <= CURRENT_DATE),
    version_firmware FLOAT NOT NULL CHECK (version_firmware > 0)
);

CREATE TABLE Mesure (
    id_mesure VARCHAR(100) PRIMARY KEY,
    date_mesure TIMESTAMP NOT NULL,
    valeur FLOAT NOT NULL CHECK (valeur >= 0),
    id_mesureur VARCHAR(100) NOT NULL,
    id_patient VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_mesureur) REFERENCES Mesureur (id_mesureur),
    FOREIGN KEY (id_patient) REFERENCES Patient (id_patient)
);

CREATE TABLE Commentaire (
    id_commentaire VARCHAR(100) PRIMARY KEY,
    texte TEXT NOT NULL,
    media BYTEA,
    date_commentaire DATE,
    id_medecin VARCHAR(100) NOT NULL,
    id_patient VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_medecin) REFERENCES Medecin (id_medecin),
    FOREIGN KEY (id_patient) REFERENCES Patient (id_patient)
);

CREATE TYPE type_alerte_enum AS ENUM ('hyperglycemie', 'hypoglycemie');
CREATE TYPE statut_enum AS ENUM ('actif', 'résolu');
CREATE TABLE Alerte (
    id_alerte VARCHAR(100) PRIMARY KEY,
    type_alerte type_alerte_enum NOT NULL,
    statut statut_enum NOT NULL,
    id_mesure VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_mesure) REFERENCES Mesure (id_mesure)
);

CREATE TABLE Prescription (
    id_prescription VARCHAR(100) PRIMARY KEY,
    date_prescription DATE NOT NULL,
    details TEXT NOT NULL,
    id_medecin VARCHAR(100) NOT NULL,
    id_patient VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_medecin) REFERENCES Medecin (id_medecin),
    FOREIGN KEY (id_patient) REFERENCES Patient (id_patient)
);

INSERT INTO Utilisateur (id_utilisateur, nom, prenom, date_naissance, email, telephone, mot_de_passe, date_creation, photo_identite, sexe, preferences_notifs) 
VALUES
('P435738489', 'Dupont', 'Jean', '1990-05-15', 'jean.dupont@gmail.com', '0623456789', '5e884898da28', '2024-10-14', 'data:image/png;base64,KGBORw0', 'H', 'EMAIL'),
('P435738490', 'Bernard', 'Marie', '1982-03-24', 'marie.bernard@gmail.com', '0609876543', '2bff6141c0c7', '2023-08-20', 'data:image/png;base64,iVBORw0KG', 'F', 'SMS'),
('P435738491', 'Martin', 'Paul', '1985-07-12', 'paul.martin@gmail.com', '0612345678', '8e9d6362dbcf', '2022-01-10', 'data:image/png;base64,XYGBMW0', 'H', 'EMAIL'),
('P435738492', 'Leroy', 'Claire', '1995-02-08', 'claire.leroy@gmail.com', '0645678923', '19b6ef23c1b7', '2021-09-15', 'data:image/png;base64,JPVTJH0', 'F', 'SMS'),
('P435738493', 'Simon', 'Sophie', '2000-11-03', 'sophie.simon@gmail.com', '0698765432', '3b91434ecacd', '2020-12-01', 'data:image/png;base64,OWQMLN0', 'F', 'EMAIL'),
('M465738489', 'Rousseau', 'Anne', '1975-04-22', 'anne.rousseau@gmail.com', '0623456780', 'a1b2c3d4e5f6', '2023-07-14', 'data:image/png;base64,XAJSKDO', 'F', 'EMAIL'),
('M465738490', 'Martin', 'Louis', '1980-11-15', 'louis.martin@gmail.com', '0698765431', 'f6e5d4c3b2a1', '2022-03-10', 'data:image/png;base64,LQWIEUC', 'H', 'SMS'),
('M465738491', 'Dupuis', 'Clara', '1990-06-10', 'clara.dupuis@gmail.com', '0645678926', 'c3b2a1f6e5d4', '2021-09-20', 'data:image/png;base64,OIKWQSD', 'F', 'EMAIL'),
('M465738492', 'Morel', 'Pierre', '1985-02-28', 'pierre.morel@gmail.com', '0612345670', 'd4c3b2a1f6e5', '2020-01-05', 'data:image/png;base64,QOSIXLW', 'H', 'SMS'),
('M465738493', 'Lefèvre', 'Sarah', '1978-12-05', 'sarah.lefevre@gmail.com', '0687654324', 'e5f6d4c3b2a1', '2019-11-15', 'data:image/png;base64,NWIOQPD', 'F', 'EMAIL');

INSERT INTO Capteur (id_capteur, date_installation, duree_vie)
VALUES
('CC465738489', '2023-11-01', 365),
('CC465738490', '2024-01-15', 730),
('CC465738491', '2023-09-10', 180),
('CC465738492', '2024-03-20', 90),
('CC465738493', '2023-06-25', 45);

INSERT INTO Patient (id_patient, groupe_sanguin, poids, taille, adresse, id_capteur)
VALUES
('P435738489', 'O+', 72.5, 180.0, '45 Rue de Paris, Marseille', 'CC465738489'),
('P435738490', 'A-', 65.0, 170.0, '12 Avenue de Lyon, Toulouse', 'CC465738490'),
('P435738491', 'B+', 80.0, 175.0, '78 Boulevard Victor, Lille', 'CC465738491'),
('P435738492', 'AB-', 90.0, 160.0, '23 Place du Capitole, Bordeaux', 'CC465738492'),
('P435738493', 'O-', 55.0, 155.0, '89 Impasse des Fleurs, Nantes', 'CC465738493');

INSERT INTO Medecin (id_medecin, specialisation, numero_licence)
VALUES
('M465738489', 'Endocrinologie', 'LIC123456789'),
('M465738490', 'Cardiologie', 'LIC987654321'),
('M465738491', 'Neurologie', 'LIC192837465'),
('M465738492', 'Dermatologie', 'LIC564738291'),
('M465738493', 'Ophtalmologie', 'LIC847362514');

INSERT INTO Dossier (id_dossier, date_creation, date_maj, historique_medical, allergies, document_medical, id_patient)
VALUES
('D465738489', '2023-01-15', '2024-10-10', 'Antécédents d’asthme, hypertension', 'Pollen, Poils de chat', 'data:application/pdf;base64,XAJSKD1', 'P435738489'),
('D465738490', '2022-05-10', '2024-09-12', 'Diabète de type 2, fracture de la jambe', 'Arachides', 'data:application/pdf;base64,POIUY12', 'P435738490'),
('D465738491', '2021-03-25', '2024-08-18', 'Appendicectomie, rhinite chronique', 'Lactose, Gluten', 'data:application/pdf;base64,MNBVC34', 'P435738491'),
('D465738492', '2020-11-05', '2024-07-01', 'Allergie au pollen, chirurgie cardiaque', 'Antibiotiques', 'data:application/pdf;base64,LKJHGF9', 'P435738492'),
('D465738493', '2019-07-18', '2024-06-23', 'Hyperthyroïdie, anémie', NULL, NULL, 'P435738493');

INSERT INTO Mesureur (id_mesureur, date_fabrication, version_firmware)
VALUES
('MSR465738489', '2022-05-10', 1.2),
('MSR465738490', '2021-11-15', 1.5),
('MSR465738491', '2023-01-05', 2.0),
('MSR465738492', '2020-08-22', 1.8),
('MSR465738493', '2019-03-18', 1.1);

INSERT INTO Mesure (id_mesure, date_mesure, valeur, id_mesureur, id_patient)
VALUES
('MSE465738489', '2024-10-14 10:15:30', 4.1, 'MSR465738489', 'P435738489'),
('MSE465738490', '2024-10-14 11:20:45', 7.5, 'MSR465738490', 'P435738490'),
('MSE465738491', '2024-10-14 12:05:10', 3.2, 'MSR465738491', 'P435738491'),
('MSE465738492', '2024-10-14 13:30:00', 6.8, 'MSR465738492', 'P435738492'),
('MSE465738493', '2024-10-14 14:45:15', 5.4, 'MSR465738493', 'P435738493');

INSERT INTO Commentaire (id_commentaire, texte, media, date_commentaire, id_medecin, id_patient)
VALUES
('C465738489', 'Instabilité détectée. Rendez-vous recommandé.', 'data:image/jpg;base64,ABC123', '2024-10-14', 'M465738489', 'P435738489'),
('C465738490', 'Analyse des données incohérente. Consultation urgente.', NULL, '2024-10-12', 'M465738490', 'P435738490'),
('C465738491', 'Résultat stable. Aucune action nécessaire.', 'data:image/jpg;base64,DEF456', '2024-09-20', 'M465738491', 'P435738491'),
('C465738492', 'Suspicion de problème récurrent. Nouvelle analyse requise.', NULL, '2024-10-01', 'M465738492', 'P435738492'),
('C465738493', 'Tout semble normal. Continuez le suivi habituel.', 'data:image/jpg;base64,GHI789', '2024-09-30', 'M465738493', 'P435738493');

INSERT INTO Alerte (id_alerte, type_alerte, statut, id_mesure)
VALUES
('A465738489', 'hyperglycemie', 'actif', 'MSE465738489'),
('A465738490', 'hypoglycemie', 'résolu', 'MSE465738490'),
('A465738491', 'hyperglycemie', 'actif', 'MSE465738491'),
('A465738492', 'hypoglycemie', 'actif', 'MSE465738492'),
('A465738493', 'hyperglycemie', 'résolu', 'MSE465738493');

INSERT INTO Prescription (id_prescription, date_prescription, details, id_medecin, id_patient)
VALUES
('PR465738489', '2024-09-01', '10 unités d’insuline à prendre chaque matin', 'M465738489', 'P435738489'),
('PR465738490', '2024-08-20', '5 mg de Bisoprolol par jour', 'M465738490', 'P435738490'),
('PR465738491', '2024-07-15', '500 mg de Metformine matin et soir', 'M465738491', 'P435738491'),
('PR465738492', '2024-06-30', '2 comprimés de Levothyrox à jeun', 'M465738492', 'P435738492'),
('PR465738493', '2024-05-10', '1 ampoule de vitamine D par semaine', 'M465738493', 'P435738493');

--PARTIE SELECT

-- Sélectionner les noms et prénoms de tous les utilisateurs de sexe masculin (sexe = 'H') présents dans la table Utilisateur.
SELECT nom, prenom 
FROM Utilisateur 
WHERE sexe = 'H';

-- Trouver les noms et prénoms des utilisateurs qui sont soit de sexe masculin (sexe = 'H'), soit dont la préférence de notification est par SMS (preferences_notifs = 'SMS').
SELECT nom, prenom 
FROM Utilisateur 
WHERE sexe = 'H' OR preferences_notifs = 'SMS';

-- Récupérer les identifiants (id_mesure) et les dates (date_mesure) des mesures, et les trier par date en ordre décroissant, c’est-à-dire de la plus récente à la plus ancienne.
SELECT id_mesure, date_mesure 
FROM Mesure 
ORDER BY date_mesure DESC;

-- Identifier les patients ayant à la fois le groupe sanguin O+ et un poids supérieur à 70 kg en utilisant une intersection des deux conditions.
SELECT id_patient 
FROM Patient 
WHERE groupe_sanguin = 'O+'
INTERSECT
SELECT id_patient 
FROM Patient 
WHERE poids > 70;

-- Calculer la somme totale des valeurs (valeur) de toutes les mesures enregistrées dans la table Mesure.
SELECT SUM(valeur) AS total_mesures 
FROM Mesure;

-- Trouver les patients (id_patient) et leurs groupes sanguins pour lesquels un dossier médical existe dans la table Dossier.
SELECT id_patient, groupe_sanguin 
FROM Patient 
WHERE EXISTS (
    SELECT 1 
    FROM Dossier 
    WHERE Dossier.id_patient = Patient.id_patient
);

-- Lister les identifiants des patients dont la taille est inférieure ou égale à 160 cm.
SELECT id_patient 
FROM Patient 
WHERE taille <= 160;

-- Récupérer les identifiants des patients et leurs historiques médicaux en combinant les données des tables Patient et Dossier, en utilisant la clé commune id_patient.
SELECT id_patient, historique_medical 
FROM Patient 
JOIN Dossier USING (id_patient);

-- Compter le nombre total de patients pour chaque groupe sanguin (groupe_sanguin) en regroupant les patients par leur groupe sanguin.
SELECT groupe_sanguin, COUNT(*) AS total_patients 
FROM Patient 
GROUP BY groupe_sanguin;

-- Calculer le poids moyen (AVG(poids)) pour chaque groupe sanguin, et ne retenir que les groupes sanguins où le poids moyen dépasse 60 kg.
SELECT groupe_sanguin, AVG(poids) AS avg_poids 
FROM Patient 
GROUP BY groupe_sanguin 
HAVING AVG(poids) > 60;