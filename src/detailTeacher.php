<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Page permettant d'afficher toutes les informations d'un enseignant
 */

session_start();

if (!isset($_SESSION['userConnected'])) {
    header('Location: index.php');
}

include("Database.php");
include("header.php");

$db = new Database();
//Récupère les informations de l'enseignant via son id qui se trouve dans l'url
$OneTeacher = $db->getOneTeacher($_GET["idTeacher"]);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <title>Version statique de l'application des surnoms</title>
</head>

<body>
    <fieldset class="mb-3 mt-5">
        <div class="container">
            <div class="user-body">
                <h3>Informations de l'enseignant : </h3> <?php
                                                            echo "Nom de famille : " . $OneTeacher["teaName"] . "<br>" . "Prénom : " . $OneTeacher["teaFirstname"] . "<br>" ?>
                <?php
                //Affiche une image différente en fonction du genre de l'enseignant (se base sur la valeur de teaGender)
                if ($OneTeacher["teaGender"] == "M") {
                    echo "Genre :" . '<img style="margin-left: 1vw;" height="20em" src="./img/male.png" alt="male symbole">';
                } else if ($OneTeacher["teaGender"] == "F") {
                    echo  "Genre :" . '<img style="margin-left: 1vw;" height="20em" src="./img/femelle.png" alt="male symbole">';
                } else if ($OneTeacher["teaGender"] == "A") {
                    echo "Genre :" . '<img style="margin-left: 1vw;" height="20em" src="./img/autre.png" alt="male symbole">';
                }
                ?>
                <p>
                    <?php echo "Section : " . $OneTeacher["secName"] ?>
                </p>
                <div class="user-body">
                    <div class="left">
                        <p><?php echo "Surnom : " . $OneTeacher["teaNickname"] ?></p>
                        <p> <?php echo "Origine du surnom : " . $OneTeacher["teaOrigine"] ?></p>
                    </div>
                </div>
                <div class="actions">
                    <!--Si l'utilisateur est connecté en tant qu'admin (valeur 2) alors il a accès à la modification et à la supression sinon pas-->
                    <?php if ($_SESSION['userConnected'] == 1) {
                        echo " Actions : " ?>
                        <a href="updateTeacher.php">
                            <img height="20em" src="./img/edit.png" alt="edit icon"></a>
                        <a href="javascript:confirmDelete()">
                            <img height="20em" src="./img/delete.png" alt="delete icon"> </a>
                    <?php } ?>
                    <div>
                        <img src="<?php echo $OneTeacher["teaPhoto"] ?>">
                    </div>
                </div>
            </div>
            <div class="user-footer">
                <a href="index.html">
                    <p>Retour a la page d'accueil
                </a>
            </div>
    </fieldset>
    </div>

    <footer>
        <p>Copyright GCR - bulle web-db - 2022</p>
    </footer>

    <script src="js/script.js"></script>

</body>

</html>