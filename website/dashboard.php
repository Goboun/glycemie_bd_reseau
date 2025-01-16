<?php
// Vérifiez si l'utilisateur est authentifié, sinon rediriger vers la page de connexion
if (!isset($_COOKIE['authenticated']) || $_COOKIE['authenticated'] !== 'true') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion Glycémie</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>

<header>
    <nav>
        <a class="crumb" href="index.php">Accueil</a>
        <a class="crumb" href="suivi.php">Suivi de la glycémie</a>
        <a class="crumb" href="alimentation.php">Alimentation et glycémie</a>
        <a class="crumb" href="statistiques.php">Statistiques</a>
        <a class="crumb" href="conseils.php">Conseils et Astuces</a>
        <a class="crumb" href="contact.php">Contact</a>
        <a class="crumb logout" href="logout.php">Déconnexion</a>
    </nav>
</header>

<main class="dashboard">
    <div class="welcome">
        <h1>Bienvenue sur votre tableau de bord !</h1>
        <p>Suivez vos données de glycémie, consultez vos statistiques et accédez à des conseils personnalisés.</p>
    </div>

    <div class="actions">
        <a href="suivi.php" class="action-card">
            <h2>Suivi Glycémie</h2>
            <p>Ajoutez et visualisez vos mesures de glycémie.</p>
        </a>
        <a href="alimentation.php" class="action-card">
            <h2>Alimentation</h2>
            <p>Découvrez les impacts des aliments sur votre glycémie.</p>
        </a>
        <a href="statistiques.php" class="action-card">
            <h2>Statistiques</h2>
            <p>Analysez vos tendances et graphiques.</p>
        </a>
        <a href="conseils.php" class="action-card">
            <h2>Conseils</h2>
            <p>Obtenez des recommandations adaptées à vos données.</p>
        </a>
    </div>
</main>

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

</body>
</html>
