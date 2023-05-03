<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: log.php');
    exit;
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

$week_offset = isset($_GET['week_offset']) ? intval($_GET['week_offset']) : 0;

$week_start = strtotime("this week +{$week_offset} week");
$week_end = strtotime("this week +6 days +{$week_offset} week");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Calendrier - ProgWeb</title>
    <!-- La police d'ecriture -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<!---------------------------------------------------------------------------------------------------------->
<!--                                    BANDEAU EN TETE DE PAGE                                           -->
<!---------------------------------------------------------------------------------------------------------->
<div class="header-container" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Informations utilisateur (id + role)-->
        <?php
        echo '<img src="images/logo_utilisateurComm.png" alt="Utilisateur" width="30" height="30">';
        echo '&nbsp <strong>' . $id . '&nbsp</strong> (' . strtolower($role) . ')';
        ?>

        <!-- Circulation des dates -->
        <div class="circulation_dates">
            <?php
            echo "<a href='?week_offset=" . ($week_offset - 1) . "'><img id=flecheG src='images/flecheG.png' alt='<--' width='30' height='30'></a> ";
            echo "&nbsp;&nbsp;Semaine du " . date('d/m/Y', $week_start) . " au " . date('d/m/Y', $week_end) . "&nbsp;&nbsp;";
            echo "<a href='?week_offset=" . ($week_offset + 1) . "'><img src='images/flecheD.png' alt='-->' width='30' height='30'></a> ";
            ?>
        </div>

        <!-- Bouton Ajouter un cours (si admin) -->
        <a href="stat.php" style="margin-right: 15px;"><img width="30px" height="30px" src="images/logo_stat.png"></a>

        <!-- vue statistiques de chaque cours -->
        <?php
        echo '<a style="margin-right:15px" href="ajoutCours.php"><img src="images/logo_plusCours.png" alt="Ajouter un cours" title="Ajouter un cours" width="30" height="30"></a>';
        ?>

        <!-- Bouton de déconnexion -->
        <form action="log.php" method="post" style="margin: 0;">
            <input type="hidden" name="logout" value="true">
            <input type="image" src="images/logo_logout.png" alt="Se déconnecter" title="Se déconnecter" width="30"
                height="30">
        </form>
    </div>

    <!---------------------------------------------------------------------------------------------------------->
    <!--                                            VARIABLES                                                 -->
    <!---------------------------------------------------------------------------------------------------------->
    <?php
    // Tableau des jours de la semaine
    $jours_semaine = array('LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI');

    // Tableau des horaires par quart d'heure
    $horaires = array();
    for ($i = 8; $i < 18; $i++) {
        for ($j = 0; $j < 4; $j++) {
            $horaires[] = $i . ':' . ($j * 15);
        }
    }

    // Tableau des groupes
    $groupes = array('G1', 'G2', 'G3');

    ?>

    <!---------------------------------------------------------------------------------------------------------->
    <!--                                    LECTURE DU FICHIER JSON                                           -->
    <!---------------------------------------------------------------------------------------------------------->
    <?php
    // Charger le fichier JSON en chaîne de caractères
    $jsonString = file_get_contents('json/cours.json');

    // Décoder la chaîne JSON en tableau associatif PHP
    $coursData = json_decode($jsonString, true);

    // Parcourir et afficher les informations sur le cours
    foreach ($coursData as $cours) {
        $cours_week_start = strtotime(date('Y-m-d', strtotime($cours['week_start'])));
        $display_week_start = strtotime(date('Y-m-d', $week_start));
        $Crenouvelable = $cours['renouvelable'];
        if (($Crenouvelable == false) && ($cours_week_start != $display_week_start)) {
            continue;
        }
        $Ctype = $cours['type'];
        $Cmatiere = $cours['matiere'];
        $Censeignant = $cours['enseignant'];
        $Csalle = $cours['salle'];
        $Cjour = $cours['jour'];
        $CdebutH = $cours['debutH'];
        $CdebutM = $cours['debutM'];
        $Cduree = $cours['duree'];
        $Cgroupe = $cours['groupe'];
        $Ccouleur = $cours['couleur'];
        $Cid = $cours['id'];
        $Csemaine = $cours['week_start'];

        // Afficher les informations sur le cours
        // echo "<p> Type : $Ctype <br> Matière : $Cmatiere <br> Enseignant : $Censeignant <br> Salle : $Csalle <br> Jour : $Cjour <br> Début : $Cdebut <br> Durée : $Cduree <br> Groupe : $Cgroupe </p>";
    
        for ($i = 0; $i < $Cduree; $i++) {
            if (($Cduree - $i) == $Cduree)
                $premiereHeure = true;
            else
                $premiereHeure = false;
            $calendrier[$Cjour][$Cgroupe][$CdebutH . ':' . $CdebutM] = array($Cmatiere, $Cduree - $i, $Ccouleur, $Ctype, $Censeignant, $Csalle, $Cid, $Csemaine, $premiereHeure);
            $CdebutM += 15;
            if ($CdebutM == 60) {
                $CdebutM = 0;
                $CdebutH += 1;
            }
        }    
    }


    ?>

    <!---------------------------------------------------------------------------------------------------------->
    <!--                                      AFFICHAGE CALENDRIER                                            -->
    <!---------------------------------------------------------------------------------------------------------->
    <?php
        include 'vues/vueSemaine.php';
    ?>

</body>

</html>