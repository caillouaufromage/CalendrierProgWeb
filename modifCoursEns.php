<!-- MODIFIER UN COURS (enseignant) -->
<?php
// Charger le fichier JSON en chaîne de caractères
$jsonString = file_get_contents('json/cours.json');

// Décoder la chaîne JSON en tableau associatif PHP
$coursData = json_decode($jsonString, true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mettre à jour le cours correspondant à l'ID dans le tableau $coursData
    foreach ($coursData as $key => $cours) {
        if ($cours['id'] == $_POST['id']) {
            // Mettre à jour les propriétés du cours avec les valeurs soumises
            $coursData[$key] = array_merge($coursData[$key], $_POST);
            break;
        }
    }

    // Encoder le tableau associatif PHP en chaîne JSON
    $nouveauJsonString = json_encode($coursData, JSON_PRETTY_PRINT);

    // Écrire la chaîne JSON dans le fichier cours.json
    file_put_contents('json/cours.json', $nouveauJsonString);

    // Rediriger vers la page d'accueil
    header('Location: index.php');
    exit();
} else {
    // Trouver le cours à modifier
    $coursAModifier = null;
    $id_cours = isset($_GET['id']) ? $_GET['id'] : null;
    foreach ($coursData as $cours) {
        if ($cours['id'] == $id_cours) {
            $coursAModifier = $cours;
            break;
        }
    }

    if ($coursAModifier) {
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Modifier un cours - ProgWeb</title>
            <link rel="stylesheet" href="css/styleModifCours.css">
        </head>

        <body>
            <h1><img src="images/logo_modifCours.png" width=25px height=25px>&nbsp;Modifier un cours&nbsp;<img
                    src="images/logo_modifCours.png" width=25px height=25px></h1>
            <div>
                <form action="modifCoursEns.php" method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo $coursAModifier['id']; ?>">
                    <!-- ON AFFICHE LA DESCRIPTION DU COURS (non modifiable!) -->
                    <label>Type de cours:
                        <?php echo $coursAModifier['type']; ?>
                    </label><br>
                    <label>Matière:
                        <?php echo $coursAModifier['matiere']; ?>
                    </label><br>
                    <label>Enseignant:
                        <?php echo $coursAModifier['enseignant']; ?>
                    </label><br>
                    <label>Salle:
                        <?php echo $coursAModifier['salle']; ?>
                    </label><br>
                    <label>Jour:
                        <?php echo $coursAModifier['jour']; ?>
                    </label><br>

                    <!-- HEURE DU DEBUT (modifiable) -->
                    <label for="debutH">Heure de début:</label>
                    <div class="heure-debut">
                        <input type="number" id="debutH" name="debutH" value="<?php echo $coursAModifier['debutH']; ?>" min="8"
                            max="19" required>
                        <span>h</span>
                        <input type="number" id="debutM" name="debutM" value="<?php echo $coursAModifier['debutM']; ?>" min="0"
                            max="45" step="15" required>
                        <span>min</span>
                    </div>

                    <!-- DUREE (modifiable) -->
                    <label for="duree">Durée (en quart d'heure):</label>
                    <input type="number" id="duree" name="duree" value="<?php echo $coursAModifier['duree']; ?>" min="1"
                        max="16" required>

                    <!-- RESTE DE LA DESCRIPTION (non modifiable) -->
                    <label>Groupe:
                        <?php
                        $groupe_text = ($coursAModifier['groupe'] == 0) ? 'Tous les groupes' : "Groupe {$coursAModifier['groupe']}";
                        echo $groupe_text; ?>
                    </label><br>

                    <label>Date de début de semaine:
                        <?php echo $coursAModifier['week_start']; ?>
                    </label><br>

                    <!-- BOUTON VALIDER -->
                    <input type="submit" id="modifiercoursbouton" value="Modifier">

                    <!-- Bouton de retour au calendrier -->
                    <input type="button" id="retourcalendrier" value="Retour au calendrier" onclick="location.href='index.php'">

                </form>
            </div>
        </body>

        </html>

        <?php
    } else {
        echo "Erreur : Le cours avec l'ID " . $id_cours . " n'a pas été trouvé.";
    }
}
?>