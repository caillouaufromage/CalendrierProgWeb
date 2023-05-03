<?php
session_start();
$id_cours = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_cours) {
    $jsonString = file_get_contents('json/cours.json');
    $coursData = json_decode($jsonString, true);

    $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : null;
    if ($commentaire) {
        foreach ($coursData as &$c) {
            if ($c['id'] == $id_cours) {
                $id_utilisateur = $_SESSION['id']; // on stocke le pseudo de l'utilisateur, ça fera des commentaires stylés
                $role_utilisateur = $_SESSION['role']; // on stocke le role de l'utilisateur, ça fera des commentaires stylés (bis)
                $c['commentaires'][] = "<b>[" . $role_utilisateur . "]&nbsp;" . $id_utilisateur . "</b>: " . $commentaire;
                break;
            }
        }
        $newJsonString = json_encode($coursData);
        file_put_contents('json/cours.json', $newJsonString);
    }
}

if ($id_cours) {
    $jsonString = file_get_contents('json/cours.json');
    $coursData = json_decode($jsonString, true);
    $cours = null;

    foreach ($coursData as $c) {
        if ($c['id'] == $id_cours) {
            $cours = $c;
            break;
        }
    }

    if ($cours) {
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>commentaire du cours - ProgWeb</title>
            <link rel="stylesheet" href="css/styleModifCours.css">
        </head>

        <body>
            <form action="commentaire.php?id=<?php echo $id_cours; ?>" method="post">
                <h1>commentaire du cours</h1>
                <br><br>
                <label>
                    <!-- description du cours sur lequel on a cliqué -->
                    <?php echo $cours['type'] . ' ' . $cours['matiere'] . ' par ' . $cours['enseignant']; ?>
                </label>
                <br><br>

                <?php
                if ($cours['commentaireAdm'] != null) { // on affiche le commentaire de l'admin s'il existe
                    echo "<p><img src='images/logo_adminComm.png' width=30px; height=30px;> &nbsp;'" . $cours['commentaireAdm'] . '</p>';
                } else { // sinon on affiche un message disant qu'il n'y a pas de commentaire
                    echo "<p><img src='images/logo_adminComm.png' width=30px; height=30px;> &nbsp; <b>- Aucun commentaire pour ce cours -</b></p>";
                }
                echo '<br><br>';

                if (!empty($cours['commentaires'])) { //ici on affiche les commentaires des utilisateurs
                    foreach ($cours['commentaires'] as $commentaire) {
                        echo '<p><img src="images/logo_utilisateurComm.png" width=30px; height=30px;> &nbsp;' . $commentaire . "</p><br>";
                    }
                } else { //s'il n'y en a pas on le signale!
                    echo "<p>Aucun commentaire pour ce cours :-( </p>";
                }

                echo '<br><br>';
                ?>

                <!-- AJOUTER UN COMMENTAIRE -->
                <label for="commentaire">Ajouter un commentaire:</label>
                <br>
                <textarea id="commentaire" name="commentaire" rows="4" cols="50"></textarea>
                <br>
                <input type="submit" id="ajoutercommentaire" value="Soumettre le commentaire">
                <br><br>

                <!-- Bouton de retour au calendrier -->
                <input type="button" id="retourcalendrier" value="Retour au calendrier" onclick="location.href='index.php'">
            </form>

            <?php
    } else {
        echo "Erreur : Le cours avec l'ID " . $id_cours . " n'a pas été trouvé.";
    }
} else {
    echo "Erreur : Aucun ID de cours fourni.";
}
?>

</body>

</html>