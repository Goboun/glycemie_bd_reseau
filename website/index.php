<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion Glycémie</title>
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
    <img class="header" src="images/logo.png" alt="Logo du site Glycémie" height="450" width="150" style="border-radius: 10px;">

</header>
<!-- Fin de l'en-tête -->

<h1>Bienvenue sur le site de Gestion de la Glycémie</h1>
<p>Suivez, gérez et analysez vos données de glycémie facilement !</p>
<p>Veuillez soit, vous connectez sur <a href="login.php">Connexion</a>, soit si vous n'avez pas de compte médecin, créez en un sur <a href="register.php">Créer un compte</a>.</p>
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
