<?php
declare(strict_types=1);

try {
    $pdo = new PDO('pgsql:host=autorack.proxy.rlwy.net;port=46994;dbname=railway', 'postgres', 'JuadqbFAGHKKrDgWyojqALbTaEkNGKin', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $mot_de_passe = trim($_POST['password']);
    $sexe = $_POST['sexe'] ?? 'H';
    $preferences_notifs = $_POST['preferences_notifs'] ?? 'EMAIL';
    $date_naissance = $_POST['date_naissance'] ?? null;

    if (strlen($mot_de_passe) < 8) {
        die("<p style='color:red; text-align:center;'>Erreur : Le mot de passe doit contenir au moins 8 caractères.</p>");
    }

    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);
    $id_utilisateur = 'U' . uniqid(); // Génération d'un identifiant unique
    $date_creation = date('Y-m-d'); // Date de création automatique

    try {
        $stmt = $pdo->prepare("
            INSERT INTO Utilisateur (id_utilisateur, nom, prenom, date_naissance, email, telephone, mot_de_passe, date_creation, photo_identite, sexe, preferences_notifs)
            VALUES (:id_utilisateur, :nom, :prenom, :date_naissance, :email, :telephone, :mot_de_passe, :date_creation, :photo_identite, :sexe, :preferences_notifs)
        ");

        $stmt->execute([
            'id_utilisateur' => $id_utilisateur,
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $date_naissance,
            'email' => $email,
            'telephone' => $telephone,
            'mot_de_passe' => $mot_de_passe_hache,
            'date_creation' => $date_creation,
            'photo_identite' => NULL, // À mettre à jour si vous gérez les fichiers
            'sexe' => $sexe,
            'preferences_notifs' => $preferences_notifs
        ]);

        echo "<p style='color:green; text-align:center;'>Compte créé avec succès !</p>";
    } catch (PDOException $e) {
        die("<p style='color:red; text-align:center;'>Erreur lors de la création du compte : " . $e->getMessage() . "</p>");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte - Gestion Glycémie</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
<header>
    <nav><a href="index.php">Accueil</a></nav>
</header>
<h2>Créer un Compte</h2>
<form method="POST">
    <label>Nom :</label>
    <input type="text" name="nom" required>
    <label>Prénom :</label>
    <input type="text" name="prenom" required>
    <label>Date de naissance :</label>
    <input type="date" name="date_naissance" required>
    <label>Email :</label>
    <input type="email" name="email" required>
    <label>Téléphone :</label>
    <input type="tel" name="telephone" pattern="[0-9]{10}" required>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <label>Sexe :</label>
    <select name="sexe">
        <option value="H">Homme</option>
        <option value="F">Femme</option>
    </select>
    <label>Préférences de notifications :</label>
    <select name="preferences_notifs">
        <option value="EMAIL">Email</option>
        <option value="SMS">SMS</option>
    </select>
    <button type="submit">Créer un compte</button>
</form>
</body>
</html>
