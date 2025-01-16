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
$medecinInfo = null;
$id_patient = '';

// Traiter la recherche d'informations du patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_patient = trim($_POST['id_patient'] ?? '');

    if (!empty($id_patient)) {
        try {
            // Récupérer le médecin traitant du patient à partir de la dernière prescription
            $stmt = $pdo->prepare("
                SELECT 
                    u.nom,
                    u.prenom,
                    u.email,
                    u.telephone,
                    m.specialisation
                FROM Prescription p
                INNER JOIN Medecin m ON p.id_medecin = m.id_medecin
                INNER JOIN Utilisateur u ON m.id_medecin = u.id_utilisateur
                WHERE p.id_patient = :id_patient
                ORDER BY p.date_prescription DESC
                LIMIT 1
            ");
            $stmt->execute(['id_patient' => $id_patient]);
            $medecinInfo = $stmt->fetch();
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
    <title>Contact Médecin - Gestion Glycémie</title>
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
<h2>Contact du Médecin</h2>

<form method="POST">
    <label for="id_patient">ID du Patient :</label>
    <input type="text" name="id_patient" id="id_patient" value="<?= htmlspecialchars($id_patient) ?>" required>
    <button type="submit">Voir le contact</button>
</form>

<div id="result">
<?php if ($medecinInfo): ?>
    <h3>Médecin Traitant</h3>
    <ul>
        <li><strong>Nom :</strong> Dr. <?= htmlspecialchars($medecinInfo['prenom'] . ' ' . $medecinInfo['nom']) ?></li>
        <li><strong>Spécialisation :</strong> <?= htmlspecialchars($medecinInfo['specialisation']) ?></li>
        <li><strong>Email :</strong> <?= htmlspecialchars($medecinInfo['email']) ?></li>
        <li><strong>Téléphone :</strong> <?= htmlspecialchars($medecinInfo['telephone']) ?></li>
    </ul>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p>Aucune information de médecin trouvée pour ce patient.</p>
<?php endif; ?>
</div>
</body>
</html>
