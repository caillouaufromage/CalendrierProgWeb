<!-- PAGE D'INSCRIPTION -->

<?php
session_start();

if (isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit;
}

$usersFile = file_get_contents('json/utilisateurs.json');
$usersData = json_decode($usersFile, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputId = $_POST['id'];
    $inputMdp = $_POST['mdp'];

    $userExists = false;

    foreach ($usersData as $user) {
        if ($user['id'] === $inputId) {
            $userExists = true;
            $error = "L'identifiant existe déjà.";
            break;
        }
    }

    if (!$userExists) {
        $newUser = [
            'id' => $inputId,
            'mdp' => $inputMdp,
            'role' => 'etudiant'
        ];

        $usersData[] = $newUser;
        $usersFile = fopen('json/utilisateurs.json', 'w');
        fwrite($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));
        fclose($usersFile);

        header('Location: log.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <h1>Inscription</h1>
    <link rel="stylesheet" href="css/styleModifCours.css">
</head>

<body>
    <?php
    // s'il y a une erreur, on l'affiche
    if (isset($error)) {
        echo '<div class="error">' . $error . '</div>';
    }
    ?>

    <form id="signup-form" method="post">
        <!-- IDENTIFIANT -->
        <input type="text" id="id" name="id" placeholder="identifiant" required>

        <!-- MOT DE PASSE -->
        <input type="password" id="mdp" name="mdp" placeholder="mot de passe" required>

        <br>
        <!-- BOUTON D'INSCRIPTION -->
        <input type="submit" id="signup" value="Créez un compte">

        <!-- BOUTON RETOUR PAGE DE CONNEXION -->
        <input type="submit" id="sub" value="Retour à la page de connexion" onclick="location.href='log.php'">

    </form>
</body>

</html>