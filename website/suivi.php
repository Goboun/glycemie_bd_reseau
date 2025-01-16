<?php
declare(strict_types=1);
session_start(); // Démarrer la session


// Vérifiez si l'utilisateur est authentifié, sinon rediriger vers la page de connexion
if (!isset($_COOKIE['authenticated']) || $_COOKIE['authenticated'] !== 'true') {
    header('Location: login.php');
    exit;
}


// Connexion à la base de données
try {
    $pdo = new PDO(
        'pgsql:host=autorack.proxy.rlwy.net;port=46994;dbname=railway',
        'postgres',
        'JuadqbFAGHKKrDgWyojqALbTaEkNGKin',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("<p style='color:red;'>Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Initialisation des variables
$patientInfo = null; // Informations générales du patient
$glycemieData = []; // Historique des mesures de glycémie

// Traiter la recherche d'informations du patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_patient = trim($_POST['id_patient'] ?? ''); // Nettoyer l'ID du patient

    if (!empty($id_patient)) {
        try {
            // Récupérer les informations du patient
            $stmt = $pdo->prepare("
                SELECT 
                    p.id_patient,
                    u.nom,
                    u.prenom,
                    u.date_naissance,
                    u.email,
                    u.telephone,
                    p.groupe_sanguin,
                    p.poids,
                    p.taille,
                    p.adresse,
                    c.id_capteur,
                    c.date_installation,
                    c.duree_vie
                FROM Patient p
                INNER JOIN Utilisateur u ON p.id_patient = u.id_utilisateur
                LEFT JOIN Capteur c ON p.id_capteur = c.id_capteur
                WHERE p.id_patient = :id_patient
            ");
            $stmt->execute(['id_patient' => $id_patient]);
            $patientInfo = $stmt->fetch();

            // Récupérer l'historique des mesures de glycémie
            $stmt = $pdo->prepare("
                SELECT 
                    id_mesure,
                    date_mesure,
                    valeur
                FROM Mesure
                WHERE id_patient = :id_patient
                ORDER BY date_mesure DESC
            ");
            $stmt->execute(['id_patient' => $id_patient]);
            $glycemieData = $stmt->fetchAll();
        } catch (PDOException $e) {
            die("<p style='color:red;'>Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage()) . "</p>");
        }
    } else {
        echo "<p style='color:red;'>Veuillez fournir un ID de patient valide.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Patient - Gestion Glycémie</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
<header>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="logout.php">Déconnexion</a>
    </nav>
</header>
<h2>Suivi d'un Patient</h2>

<form method="POST">
    <label for="id_patient">ID du Patient :</label>
    <input type="text" name="id_patient" id="id_patient" required>
    <button type="submit">Rechercher</button>
</form>

<div id="result">
<?php if ($patientInfo): ?>
    <h3>Informations du Patient</h3>
    <ul>
        <li><strong>ID :</strong> <?= htmlspecialchars($patientInfo['id_patient']) ?></li>
        <li><strong>Nom :</strong> <?= htmlspecialchars($patientInfo['nom']) ?></li>
        <li><strong>Prénom :</strong> <?= htmlspecialchars($patientInfo['prenom']) ?></li>
        <li><strong>Date de Naissance :</strong> <?= htmlspecialchars($patientInfo['date_naissance']) ?></li>
        <li><strong>Email :</strong> <?= htmlspecialchars($patientInfo['email']) ?></li>
        <li><strong>Téléphone :</strong> <?= htmlspecialchars($patientInfo['telephone']) ?></li>
        <li><strong>Groupe Sanguin :</strong> <?= htmlspecialchars($patientInfo['groupe_sanguin']) ?></li>
        <li><strong>Poids :</strong> <?= htmlspecialchars($patientInfo['poids']) ?> kg</li>
        <li><strong>Taille :</strong> <?= htmlspecialchars($patientInfo['taille']) ?> cm</li>
        <li><strong>Adresse :</strong> <?= htmlspecialchars($patientInfo['adresse']) ?></li>
        <?php if ($patientInfo['id_capteur']): ?>
            <li><strong>Capteur :</strong> <?= htmlspecialchars($patientInfo['id_capteur']) ?></li>
            <li><strong>Date d'installation :</strong> <?= htmlspecialchars($patientInfo['date_installation']) ?></li>
            <li><strong>Durée de vie :</strong> <?= htmlspecialchars((string)$patientInfo['duree_vie']) ?> jours</li>
        <?php else: ?>
            <li><strong>Capteur :</strong> Aucun capteur installé</li>
        <?php endif; ?>
    </ul>

    <h3>Historique des mesures de glycémie</h3>
    <?php if (count($glycemieData) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Valeur (mmol/L)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($glycemieData as $mesure): ?>
                    <tr>
                        <td><?= htmlspecialchars($mesure['date_mesure']) ?></td>
                        <td><?= htmlspecialchars($mesure['valeur']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune mesure enregistrée pour ce patient.</p>
    <?php endif; ?>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p style="color:red;">Aucun patient trouvé avec cet ID.</p>
<?php endif; ?>
</div>
</body>
</html>
