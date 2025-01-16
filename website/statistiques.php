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
$glycemieStats = null; // Statistiques des mesures de glycémie
$glycemieData = []; // Historique des mesures de glycémie
$id_patient = ''; // ID du patient

// Traiter la recherche d'informations du patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_patient = trim($_POST['id_patient'] ?? ''); // Nettoyer l'ID du patient

    if (!empty($id_patient)) {
        try {
            // Récupérer les statistiques des mesures de glycémie
            $stmt = $pdo->prepare("
                SELECT 
                    COUNT(*) AS total_mesures,
                    AVG(valeur) AS moyenne_glycemie,
                    MIN(valeur) AS glycemie_min,
                    MAX(valeur) AS glycemie_max
                FROM Mesure
                WHERE id_patient = :id_patient
            ");
            $stmt->execute(['id_patient' => $id_patient]);
            $glycemieStats = $stmt->fetch();

            // Récupérer l'historique des mesures
            $stmt = $pdo->prepare("
                SELECT 
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
    <title>Statistiques de Glycémie - Gestion Glycémie</title>
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
<h2>Statistiques de Glycémie</h2>

<form method="POST">
    <label for="id_patient">ID du Patient :</label>
    <input type="text" name="id_patient" id="id_patient" value="<?= htmlspecialchars($id_patient) ?>" required>
    <button type="submit">Rechercher</button>
</form>

<div id="result">
<?php if ($glycemieStats && $glycemieStats['total_mesures'] > 0): ?>
    <h3>Statistiques pour le Patient ID : <?= htmlspecialchars($id_patient) ?></h3>
    <ul>
        <li><strong>Nombre de mesures :</strong> <?= $glycemieStats['total_mesures'] ?></li>
        <li><strong>Glycémie moyenne :</strong> <?= number_format((float)$glycemieStats['moyenne_glycemie'], 2) ?> mmol/L</li>
        <li><strong>Glycémie minimale :</strong> <?= $glycemieStats['glycemie_min'] ?> mmol/L</li>
        <li><strong>Glycémie maximale :</strong> <?= $glycemieStats['glycemie_max'] ?> mmol/L</li>
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
    <?php endif; ?>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p style="color:red;">Aucune mesure trouvée pour ce patient.</p>
<?php endif; ?>
</div>
</body>
</html>
