<!-- MODIFIER UN COURS (admin) -->

<?php
// Charger le fichier JSON en chaîne de caractères
$jsonString = file_get_contents('json/cours.json');

// Décoder la chaîne JSON en tableau associatif PHP
$coursData = json_decode($jsonString, true);

// Tableau associatif des couleurs par matière
$couleursMatiere = array(
    "IAS" => "#F1948A",
    "WEB" => "#7DCEA0",
    "PIIA" => "#85C1E9",
    "LF" => "#F7DC6F",
    "BDD" => "#D2B4DE",
    "PFA" => "#ff7df4",
    "ANGLAIS" => "#cfc699"
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mettre à jour le cours correspondant à l'ID dans le tableau $coursData
    foreach ($coursData as $key => $cours) {
        if ($cours['id'] == $_POST['id']) {
            // Mettre à jour les propriétés du cours avec les valeurs soumises
            $coursData[$key] = array_merge($coursData[$key], $_POST);

            // Mettre à jour la couleur du cours en fonction de la matière sélectionnée
            $coursData[$key]['couleur'] = $couleursMatiere[$_POST['matiere']];

            // Mettre à jour la propriété renouvelable
            if (isset($_POST['repeatWeekly'])) {
                $coursData[$key]['renouvelable'] = true;
            } else {
                $coursData[$key]['renouvelable'] = false;
            }
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
                <form action="modifCoursAdmin.php" method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo $coursAModifier['id']; ?>">

                    <!-- LE TYPE DE COURS -->
                    <label for="type">Type de cours:</label>
                    <select id="type" name="type">
                        <option value="AMPHI" <?php echo ($coursAModifier['type'] == 'AMPHI') ? 'selected' : ''; ?>>Amphi</option>
                        <option value="TP" <?php echo ($coursAModifier['type'] == 'TP') ? 'selected' : ''; ?>>TP</option>
                        <option value="TD" <?php echo ($coursAModifier['type'] == 'TD') ? 'selected' : ''; ?>>TD</option>
                    </select>

                    <!-- LA MATIERE -->
                    <label for="matiere">Matière:</label>
                    <select id="matiere" name="matiere">
                        <?php
                        $matieres = ['IAS', 'WEB', 'PIIA', 'LF', 'BDD', 'PFA', 'ANGLAIS'];
                        foreach ($matieres as $matiere) {
                            $selected = ($coursAModifier['matiere'] == $matiere) ? 'selected' : '';
                            echo "<option value='{$matiere}' {$selected}>{$matiere}</option>";
                        }
                        ?>
                    </select>

                    <!-- L'ENSEIGNANT -->
                    <label for="enseignant">Enseignant:</label>
                    <input type="text" id="enseignant" name="enseignant" value="<?php echo $coursAModifier['enseignant']; ?>">

                    <!-- LA SALLE -->
                    <label for="salle">Salle:</label>
                    <input type="text" id="salle" name="salle" value="<?php echo $coursAModifier['salle']; ?>" required>

                    <!-- LE JOUR -->
                    <label for="jour">Jour:</label>
                    <select id="jour" name="jour">
                        <?php
                        $jours = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI'];
                        foreach ($jours as $jour) {
                            $selected = ($coursAModifier['jour'] == $jour) ? 'selected' : '';
                            echo "<option value='{$jour}' {$selected}>{$jour}</option>";
                        }
                        ?>
                    </select>

                    <!-- L'HEURE DE DEBUT -->
                    <label for="debutH">Heure de début:</label>
                    <div class="heure-debut">
                        <input type="number" id="debutH" name="debutH" value="<?php echo $coursAModifier['debutH']; ?>" min="8"
                            max="19" required>
                        <span>h</span>
                        <input type="number" id="debutM" name="debutM" value="<?php echo $coursAModifier['debutM']; ?>" min="0"
                            max="45" step="15" required>
                        <span>min</span>
                    </div>

                    <!-- LA DUREE -->
                    <label for="duree">Durée (en quart d'heure):</label>
                    <input type="number" id="duree" name="duree" value="<?php echo $coursAModifier['duree']; ?>" min="1"
                        max="16" required>

                    <!-- LE GROUPE -->
                    <label for="groupe">Groupe :</label>
                    <select id="groupe" name="groupe">
                        <?php
                        $groupes = [1, 2, 3, 0];
                        foreach ($groupes as $groupe) {
                            $selected = ($coursAModifier['groupe'] == $groupe) ? 'selected' : '';
                            $groupe_text = ($groupe == 0) ? 'Tous les groupes' : "Groupe {$groupe}";
                            echo "<option value='{$groupe}' {$selected}>{$groupe_text}</option>";
                        }
                        ?>
                    </select>

                    <!-- LA DATE DE DEBUT DE SEMAINE -->
                    <label for="week_start">Date de début de semaine:</label>
                    <input type="date" id="week_start" name="week_start" value="<?php echo $coursAModifier['week_start']; ?>"
                        required>

                    <!-- LES COMMENTAIRES!!! -->
                    <label for="commentaireAdm">Commentaires:</label>
                    <input type="text" id="commentaireAdm" name="commentaireAdm"
                        value="<?php echo $coursAModifier['commentaireAdm']; ?>">

                    <!-- REPETER CHAQUE SEMAINE -->
                    <input type="checkbox" id="repeatWeekly" name="repeatWeekly" <?php echo ($coursAModifier['renouvelable'] == true) ? 'checked' : ''; ?> />
                    <label for="repeatWeekly">Répéter chaque semaine</label>

                    <!-- BOUTON VALIDER ! -->
                    <input type="submit" id="modifiercoursbouton" value="Modifier">

                    <!-- Bouton de retour au calendrier -->
                    <input type="button" id="retourcalendrier" value="Retour au calendrier" onclick="location.href='index.php'">

            </div>
            </div>
            </form>
        </body>

        </html>
        <?php
    } else {
        echo "Erreur : Le cours avec l'ID " . $id_cours . " n'a pas été trouvé.";
    }
}
?>