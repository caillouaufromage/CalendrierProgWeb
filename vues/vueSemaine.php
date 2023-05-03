<!-- AFFICHAGE DU CALENDRIER EN VUE SEMAINE (vue de base) -->

<?php
/*******************************************************************************************************/
/*                                            VARIABLES                                                */
/*******************************************************************************************************/
$role = $_SESSION['role'];


/*******************************************************************************************************/
/*                                            FONCTIONS                                                */
/*******************************************************************************************************/
/*  pour le bouton modification, on stocke l'id du cours dans l'url et on redirige vers modifCoursAdmin/Ens!
    pour le bouton commentaire, on stocke l'id du cours dans l'url et on redirige vers commentaire.php */

//fonction très longue qui affiche un amphi en fonction de celui-ci et de l'utilisateur 
function afficheAmphi($role_utilisateur, $groupe, $slot)
{
    if ($role_utilisateur == 'admin') { //si admin
        $cellule = '<button class="cours-button" onclick="location.href=\'modifCoursAdmin.php?id=' . $slot[6] . '\'" style="background-color:' . $slot[2] . '">' . $slot[3] . ' - ' . $slot[0] . '<br>' . $slot[4] . '<br>' . $slot[5] . '</button>';
        echo ('<td id=creneau colspan=3 rowspan="' . $slot[1] . '" bgcolor="' . $slot[2] . '">' . $cellule .
            '<div style="position:relative;"><a href="commentaire.php?id=' . $slot[6] . '" style="position:absolute; bottom:0; right:0;">
                <img src="images/logo_chat.png" width=20px; height=15px;></a></div>
                </td>');

    } else if ($role_utilisateur == 'enseignant') { //si enseignant
        $cellule = '<button class="cours-button" onclick="location.href=\'modifCoursEns.php?id=' . $slot[6] . '\'" style="background-color:' . $slot[2] . '">' . $slot[3] . ' - ' . $slot[0] . '<br>' . $slot[4] . '<br>' . $slot[5] . '</button>';
        echo ('<td id=creneau colspan=3 rowspan="' . $slot[1] . '" bgcolor="' . $slot[2] . '">' . $cellule .
            '<div style="position:relative;"><a href="commentaire.php?id=' . $slot[6] . '" style="position:absolute; bottom:0; right:0;">
                <img src="images/logo_chat.png" width=20px; height=15px;></a></div>
                </td>');

    } else { //si etudiant
        $cellule = $slot[3] . ' - ' . $slot[0] . '<br>' . $slot[4] . '<br>' . $slot[5] . '<div style="position:relative;"><a href="commentaire.php?id=' . $slot[6] . '" style="position:absolute; bottom:0; right:0;">
        <img src="images/logo_chat.png" width=20px; height=15px;></a></div>';
        echo '<td id=creneau colspan=3 rowspan="' . $slot[1] . '" bgcolor="' . $slot[2] . '">' . $cellule . '</td>';

    }
}

//fonction très longue qui affiche un TD/TP en fonction de celui-ci et de l'utilisateur 
function afficheCours($role_utilisateur, $groupe, $slot)
{
    if ($role_utilisateur == 'admin') { //si admin
        $cellule = '<button class="cours-button" onclick="location.href=\'modifCoursAdmin.php?id=' . $slot[6] . '\'" style="background-color:' . $slot[2] . '">' . $slot[3] . ' - ' . $slot[0] . '<br>' . $slot[4] . '<br>' . $slot[5] . '</button>';
        echo ('<td id=creneau rowspan="' . $slot[1] . '" bgcolor="' . $slot[2] . '">' . $cellule .
            '<div style="position:relative;"><a href="commentaire.php?id=' . $slot[6] . '" style="position:absolute; bottom:0; right:0;"><img src="images/logo_chat.png" width=20px; height=15px;></a></div>
                </td>');

    } else if ($role_utilisateur == 'enseignant') { //si enseignant
        $cellule = '<button class="cours-button" onclick="location.href=\'modifCoursEns.php?id=' . $slot[6] . '\'" style="background-color:' . $slot[2] . '">' . $slot[3] . ' - ' . $slot[0] . '<br>' . $slot[4] . '<br>' . $slot[5] . '</button>';
        echo ('<td id=creneau rowspan="' . $slot[1] . '" bgcolor="' . $slot[2] . '">' . $cellule .
            '<div style="position:relative;"><a href="commentaire.php?id=' . $slot[6] . '" style="position:absolute; bottom:0; right:0;"><img src="images/logo_chat.png" width=20px; height=15px;></a></div>
                    </td>');

    } else { //si etudiant
        $cellule = $slot[3] . ' - ' . $slot[0] . '<br>' . $slot[4] . '<br>' . $slot[5] . '<div style="position:relative;"><a href="commentaire.php?id=' . $slot[6] . '" style="position:absolute; bottom:0; right:0;">
            <img src="images/logo_chat.png" width=20px; height=15px;></a></div>';
        echo '<td id=creneau rowspan="' . $slot[1] . '" bgcolor="' . $slot[2] . '">' . $cellule . '</td>';
    }
}

/*******************************************************************************************************/
/*                                      AFFICHAGE CALENDRIER                                           */
/*******************************************************************************************************/
// Affichage du tableau calendrier
echo '<table>';

// les jours de la semaine
echo '<thead><tr><th></th>';
foreach ($jours_semaine as $jour) {
    echo '<th id=jours colspan=3>' . $jour . '</th>';
}
echo '</tr></thead>';

// les groupes
echo '<tr><td id=groupe bgcolor = "#0092b2"></td>';
foreach ($jours_semaine as $jour) {
    foreach ($groupes as $groupe) {
        echo '<td id=groupe bgcolor = "#0092b2">' . $groupe . '</td>';
    }
}
echo '</tr>';

// les horaires
$i = 0;
echo '<tbody>';

foreach ($horaires as $horaire) {
    $color = "#d4d4d4";
    if ($i % 2 == 0)
    $color = "#FFFFFF"; //le lignes paires sont blanches, grises sinon
    echo '<tr> <td id=creneau bgcolor = "#BBBBBBB"" >' . $horaire . '</td>';
    foreach ($jours_semaine as $jour) {

        for ($groupe = 0; $groupe <= 3; $groupe++) {
            $slot = $calendrier[$jour][$groupe][$horaire];
            $cellule = ''; //on stocke le contenu de la cellule

            if (is_array($slot)) { //si le cours existe

                if ($slot[8] == true) { //si c'est la première heure de cours

                    if ($groupe == 0) { //s'il s'agit d'un amphi (tous les groupes)
                        afficheAmphi($role, $groupe, $slot);
                        break;

                    } else { // s'il s'agit d'un td (seulement un groupe)
                        afficheCours($role, $groupe, $slot);
                    }

                } else if ($groupe == 0)
                    break; //si ce n'est pas la première heure de cours d'un amphi, on ne fait rien

            } else {
                if ($groupe != 0)
                    echo '<td id="creneau" bgcolor="' . $color . '"></td>';
            }
        }
    }
    $i++;
}

echo '</tbody>';
echo '</table>';
?>