<?php
declare(strict_types=1);

// Connexion à la base de données PostgreSQL avec les informations fournies
try {
    $pdo = new PDO('pgsql:host=autorack.proxy.rlwy.net;port=46994;dbname=railway', 'postgres', 'JuadqbFAGHKKrDgWyojqALbTaEkNGKin', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérification de l'authentification (si l'utilisateur est déjà connecté)
$authenticated = $_COOKIE['authenticated'] ?? false;

if (isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);  // Le mot de passe en clair pour la vérification

    // Requête pour vérifier les informations dans la table Utilisateur
    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email");
    $stmt->execute([
        'email' => $email
    ]);
    $user = $stmt->fetch();

    if ($user) {
        // Si l'utilisateur existe, on vérifie le mot de passe
        if ($user['mot_de_passe'] === $password) {
            // Authentification réussie, on crée un cookie pour maintenir la session
            setcookie('authenticated', 'true', time() + 3600 * 24, '/', '', true, true); // Cookie valide pendant 24h
            header("Location: dashboard.php"); // Rediriger vers le tableau de bord
            exit;
        } else {
            echo "<p style='color:red; text-align:center;'>Mot de passe incorrect</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Email non trouvé</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Glycémie</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>

<!-- Début de l'en-tête -->
<header>
    <nav>
        <a class="crumb" href="index.php">Accueil</a>
        <a class="crumb" href="suivi.php">Suivi de la glycémie</a>
        <a class="crumb" href="alimentation.php">Alimentation et glycémie</a>
        <a class="crumb" href="statistiques.php">Statistiques</a>
        <a class="crumb" href="conseils.php">Conseils et Astuces</a>
        <a class="crumb" href="contact.php">Contact</a>
    </nav>
</header>
<!-- Fin de l'en-tête -->

<h2 style="text-align:center; margin-top: 20px;">Veuillez vous connecter</h2>

<!-- Formulaire de connexion -->
<form method="POST" style="text-align:center; margin-top:20px;">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>
    <br><br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>
    <br><br>
    <button type="submit">Connexion</button>
</form>

<!-- Début du footer -->
<footer>
    <p style="text-align:center;">
        <?php require_once("include/functions.inc.php");?>
        <?php echo get_navigateur();?>
    </p>
    <p style="text-align:center;">
        <a href="#">Revenir en haut de page</a>
    </p>
    <p style="text-align:center;">
        <a href="index.php">Home</a>
    </p>
    <p style="text-align:center;">
        <a href="sitemap.php">Plan du site</a>
    </p>
    <p style="text-align:center;">
        hebc.alwaysdata.net
    </p>
    <p style="text-align:center;">
        © HEBC 2024 Gestion Glycémie. Tous droits réservés.</p>
    </p>
</footer>
<!-- Fin du footer -->

</body>
</html>