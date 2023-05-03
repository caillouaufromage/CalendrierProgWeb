<?php
// Charger le fichier JSON en chaîne de caractères
$jsonString = file_get_contents('json/cours.json');

// Décoder la chaîne JSON en tableau associatif PHP
$coursData = json_decode($jsonString, true);
// Obtenir la date actuelle
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/styleModifCours.css">
    <title>Statistiques - ProgWeb</title>
</head>

<body>
    <h1>Statistiques des cours</h1>

    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th>Amphi</th>
                <th>TD</th>
                <th>TP</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo '<label>Nombre d\'heures par matière dans l\'année</label>';

            // Parcourir les données des cours et calculer les heures pour chaque type de cours
            foreach ($coursData as $cours) {
                $matiere = $cours['matiere'];
                $type = $cours['type'];
                $duree = $cours['duree'];
                $premiereHeure = $cours['premiereHeure'];
                $renouvelable = $cours['renouvelable'];

                // Initialise les statistiques pour cette matière si elles n'existent pas encore
                if (!isset($stats[$matiere])) {
                    $stats[$matiere] = ['AMPHI' => 0, 'TD' => 0, 'TP' => 0];
                }

                // Ajoute la durée du cours aux statistiques
                $stats[$matiere][$type] += $duree/4;
            }

            // Afficher les statistiques
            /* foreach ($stats as $key => $stat) {
            echo '<p>Matière : ' . $key . '; Heures Amphi : ' . $stat['AMPHI'] . '; Heures TD : ' . $stat['TD'] . '; Heures TP : ' . $stat['TP'] . '</p>';
            } */

            foreach ($stats as $matiere => $stat) {
                echo "<tr>";
                echo "<td>{$matiere}</td>";
                echo "<td>{$stat['AMPHI']}</td>";
                echo "<td>{$stat['TD']}</td>";
                echo "<td>{$stat['TP']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>

    </table>
    <!-- Bouton de retour au calendrier -->
    <input type="button" id="retourcalendrier" value="Retour au calendrier" onclick="location.href='index.php'">

</body>

</html>