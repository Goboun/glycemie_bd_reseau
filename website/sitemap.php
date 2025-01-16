<?php
declare(strict_types=1);

// List of pages for the sitemap
$pages = [
    ['name' => 'Accueil', 'url' => 'index.php'],
    ['name' => 'Connexion', 'url' => 'login.php'],
    ['name' => 'Création de Compte', 'url' => 'register.php'],
    ['name' => 'Tableau de Bord', 'url' => 'dashboard.php'],
    ['name' => 'Suivi de la Glycémie', 'url' => 'suivi.php'],
    ['name' => 'Alimentation', 'url' => 'alimentation.php'],
    ['name' => 'Statistiques', 'url' => 'statistiques.php'],
    ['name' => 'Conseils et Astuces', 'url' => 'conseils.php'],
    ['name' => 'Contact', 'url' => 'contact.php'],
];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan du site - Gestion Glycémie</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
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
    </nav>
</header>

<main>
    <h1>Plan du Site</h1>
    <p>Voici une liste de toutes les pages accessibles de notre site :</p>
    <ul>
        <?php foreach ($pages as $page): ?>
            <li>
                <a href="<?= htmlspecialchars($page['url']) ?>">
                    <?= htmlspecialchars($page['name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<!-- Début du footer -->
<!--Navigateur, plan du site, et nom du site-->
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
