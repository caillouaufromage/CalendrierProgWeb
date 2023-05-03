<?php
session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: log.php');
    exit;
}

if (isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit;
}

//on lit le fichier 'utilisateurs.json'
$usersFile = file_get_contents('json/utilisateurs.json');
$usersData = json_decode($usersFile, true);

/* on vérifie si le formulaire a été soumis
si oui, on vérifie si les identifiants sont corrects
si oui, on redirige vers la page d'accueil
sinon, on affiche un message d'erreur */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputId = $_POST['id'];
    $inputMdp = $_POST['mdp'];

    foreach ($usersData as $user) {
        // les identifiants et le mot de passe sont corrects
        if ($user['id'] === $inputId && $user['mdp'] === $inputMdp) {
            $_SESSION['logged'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['id'] = $user['id'];
            header('Location: index.php');
            exit;
        }
        // identifiants incorrects
        else if ($user['id'] != $inputId) {
            $error = "Identifiant incorrect.";
        }
        // mot de passe incorrect
        else if ($user['mdp'] != $inputMdp) {
            $error = "Identifiant incorrect.";
        }
    }

    $error = "ERREUR :(";

}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <h1>Connexion</h1>
    <link rel="stylesheet" href="css/styleModifCours.css">
</head>

<body>
        <?php
        // s'il y a une erreur, on l'affiche
        if (isset($error)) {
            echo '<div class="error">' . $error . '</div>';
        }
        ?>

        <form id="login-form" method="post">
            <!-- IDENTIFIANT -->
            <input type="text" id="id" name="id" placeholder="identifiant" required>

            <!-- MOT DE PASSE -->
            <input type="password" id="mdp" name="mdp" placeholder="mot de passe" required>

            <br>
            <!-- BOUTON DE CONNEXION -->
            <input type="submit" id="login" value="Se connecter">

            <!-- Bouton de l'inscription -->
            <input type="submit" id="sub" value="Créez un compte" onclick="location.href='sub.php'">

        </form>
</body>
</html>