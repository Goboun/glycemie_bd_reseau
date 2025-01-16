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
$commentaires = [];
$id_patient = '';

// Traiter la recherche d'informations du patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_patient = trim($_POST['id_patient'] ?? '');

    if (!empty($id_patient)) {
        try {
            // Récupérer les commentaires du médecin
            $stmt = $pdo->prepare("
                SELECT 
                    c.texte,
                    c.date_commentaire,
                    u.nom AS nom_medecin,
                    u.prenom AS prenom_medecin
                FROM Commentaire c
                INNER JOIN Medecin m ON c.id_medecin = m.id_medecin
                INNER JOIN Utilisateur u ON m.id_medecin = u.id_utilisateur
                WHERE c.id_patient = :id_patient
                ORDER BY c.date_commentaire DESC
            ");
            $stmt->execute(['id_patient' => $id_patient]);
            $commentaires = $stmt->fetchAll();
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
    <title>Conseils du Médecin - Gestion Glycémie</title>
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
<h2>Conseils du Médecin</h2>

<form method="POST">
    <label for="id_patient">ID du Patient :</label>
    <input type="text" name="id_patient" id="id_patient" value="<?= htmlspecialchars($id_patient) ?>" required>
    <button type="submit">Voir les conseils</button>
</form>

<div id="result">
<?php if (count($commentaires) > 0): ?>
    <?php foreach ($commentaires as $commentaire): ?>
        <div class="commentaire">
            <p><strong><?= htmlspecialchars($commentaire['prenom_medecin'] . ' ' . $commentaire['nom_medecin']) ?> - <?= htmlspecialchars($commentaire['date_commentaire']) ?> :</strong></p>
            <p><?= nl2br(htmlspecialchars($commentaire['texte'])) ?></p>
        </div>
    <?php endforeach; ?>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p>Aucun conseil trouvé pour ce patient.</p>
<?php endif; ?>
</div>
</body>
</html>
