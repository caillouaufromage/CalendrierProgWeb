<!-- AJOUTER UN COURS (role : administrateur) -->

<?php
// Vérifier que le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

//on voit si le cours est renouvelable (on le met ici parce que je sais pas comment faire autrement)
if (isset($_POST['repeatWeekly'])) {
    $renouvelable = true;
} else {
    $renouvelable = false;
}

// Ajouter le nouveau cours
$nouveauCours = array(
    "type" => $_POST['type'],
    "matiere" => $_POST['matiere'],
    "enseignant" => $_POST['enseignant'],
    "salle" => $_POST['salle'],
    "jour" => $_POST['jour'],
    "debutH" => intval($_POST['debutH']),
    "debutM" => intval($_POST['debutM']),
    "duree" => intval($_POST['duree']),
    "groupe" => intval($_POST['groupe']),
    "couleur" => $couleursMatiere[$_POST['matiere']],
    "id" => uniqid(),
    "week_start" => $_POST['week_start'],
    "commentaireAdm" => $_POST['commentaireAdm'],
    "renouvelable" => $renouvelable
);

    $coursData[] = $nouveauCours;

    // Encoder le tableau associatif PHP en chaîne JSON
    $nouveauJsonString = json_encode($coursData, JSON_PRETTY_PRINT);

    // Écrire la chaîne JSON dans le fichier cours.json
    file_put_contents('json/cours.json', $nouveauJsonString);

    // Rediriger vers la page d'accueil
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un cours - ProgWeb</title>
    <link rel="stylesheet" href="css/styleModifCours.css">
</head>
<body>
    <h1><img src="images/logo_nouveauCours.png" width=25px height=25px>&nbsp;Ajouter un cours&nbsp;<img src="images/logo_nouveauCours.png" width=25px height=25px></h1>
    <div>
    <form action="ajoutCours.php" method="post">

        <!-- LE TYPE DE COURS -->
        <label for="type">Type de cours:</label>
        <select id="type" name="type">
            <option value="AMPHI">Amphi</option>
            <option value="TP">TP</option>
            <option value="TD">TD</option>
        </select>

        <!-- LA MATIERE -->
       <label for="matiere">Matière:</label>
       <select id="matiere" name="matiere">
            <option value="IAS">IAS</option>
            <option value="WEB">ProgWeb</option>
            <option value="PIIA">PIIA</option>
            <option value="LF">Langages Formels</option>
            <option value="BDD">BDD</option>
            <option value="PFA">Projet PFA</option>
            <option value="ANGLAIS">Anglais</option>

        </select>

         <!-- L'ENSEIGNANT -->
        <label for="enseignant">Enseignant:</label>
        <input type="text" id="enseignant" name="enseignant" required>

        <!-- LA SALLE -->
        <label for="salle">Salle:</label>
        <input type="text" id="salle" name="salle" required>

        <!-- LE JOUR -->
        <label for="jour">Jour:</label>
        <select id="jour" name="jour">
            <option value="LUNDI">Lundi</option>
            <option value="MARDI">Mardi</option>
            <option value="MERCREDI">Mercredi</option>
            <option value="JEUDI">Jeudi</option>
            <option value="VENDREDI">Vendredi</option>
        </select>

        <!-- L'HEURE DE DEBUT -->
        <label for="debutH">Heure de début:</label>
        <div class="heure-debut">
        <input type="number" id="debutH" name="debutH" min="8" max="19" required>
        <span>h</span>
        <input type="number" id="debutM" name="debutM" min="0" max="45" step="15" required>
        <span>min</span>
        </div>

        <!-- LA DUREE -->
        <label for="duree">Durée (en quart d'heure):</label>
        <input type="number" id="duree" name="duree" min="1" max="16" required>

        <!-- LE GROUPE -->
        <label for="groupe">Groupe :</label>
        <select id="groupe" name="groupe">
            <option value="1">Groupe 1</option>
            <option value="2">Groupe 2</option>
            <option value="3">Groupe 3</option>
            <option value="0">Tous les groupes</option>
        </select>

        <!-- LA DATE DE DEBUT DE SEMAINE -->
        <label for="week_start">Date de début de semaine:</label>
        <input type="date" id="week_start" name="week_start" required>

        <!-- LE COMMENTAIRE DE L'ADMIN -->
        <label for="enseignant">Commentaires:</label>
        <input type="text" id="commentaireAdm" name="commentaireAdm">

        <!-- REPETITION DU COURS -->
        <input type="checkbox" id="repeatWeekly" name="repeatWeekly" />
        <label for="repeatWeekly">Répéter chaque semaine</label>

        <br><br><br>
        <!-- BOUTON VALIDER -->
        <input type="submit" id=ajoutercoursbouton value="Ajouter">

        <!-- Bouton de retour au calendrier -->
        <input type="button" id="retourcalendrier" value="Retour au calendrier" onclick="location.href='index.php'">

        </div>
    </form>
</body>
</html>

