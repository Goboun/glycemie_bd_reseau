<?php
declare(strict_types=1);
session_start(); // Démarrer la session

// Vérifiez si l'utilisateur est authentifié
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
$recommandations = '';
$id_patient = '';

// Traiter la recherche d'informations du patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_patient = trim($_POST['id_patient'] ?? '');

    if (!empty($id_patient)) {
        try {
            // Récupérer la dernière mesure de glycémie
            $stmt = $pdo->prepare("
                SELECT valeur
                FROM Mesure
                WHERE id_patient = :id_patient
                ORDER BY date_mesure DESC
                LIMIT 1
            ");
            $stmt->execute(['id_patient' => $id_patient]);
            $mesure = $stmt->fetch();

            if ($mesure) {
                $niveau_glycemie = $mesure['valeur'];

                // Déterminer les recommandations
                if ($niveau_glycemie < 4.0) {
                    $recommandations = "Votre glycémie est basse. Consommez des glucides rapides comme du jus de fruit ou des bonbons.";
                } elseif ($niveau_glycemie >= 4.0 && $niveau_glycemie <= 7.8) {
                    $recommandations = "Votre glycémie est normale. Continuez votre alimentation équilibrée.";
                } else {
                    $recommandations = "Votre glycémie est élevée. Limitez les sucres rapides et consultez votre médecin.";
                }
            } else {
                $recommandations = "Aucune mesure disponible pour ce patient.";
            }
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
    <title>Recommandations Alimentaires - Gestion Glycémie</title>
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
<h2>Recommandations Alimentaires</h2>

<form method="POST">
    <label for="id_patient">ID du Patient :</label>
    <input type="text" name="id_patient" id="id_patient" value="<?= htmlspecialchars($id_patient) ?>" required>
    <button type="submit">Obtenir les recommandations</button>
</form>

<div id="result">
<?php if (!empty($recommandations)): ?>
    <p><?= htmlspecialchars($recommandations) ?></p>
<?php endif; ?>
</div>
</body>
</html>
