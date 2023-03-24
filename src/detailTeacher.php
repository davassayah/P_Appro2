<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Page permettant d'afficher toutes les informations d'un enseignant
 */

session_start();
include("Database.php");
$db = new Database();
//Récupère les informations de l'enseignant via son id qui se trouve dans l'url
$OneTeacher = $db->getOneTeacher($_GET["idTeacher"]);

putenv("LANG=" . $_SESSION["langID"]);
setlocale(LC_ALL, $_SESSION["langID"]);
$domain = "messagesDetailTeacher";
bindtextdomain($domain, "locale");
textdomain($domain);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/style.css" rel="stylesheet">
    <title><?php echo gettext("Version statique de l'application des surnoms"); ?></title>
</head>

<body>

    <header>
        <div class="container-header">
            <div class="titre-header">
                <h1><?php echo gettext("Surnom des enseignants"); ?></h1>
            </div>
            <div class="login-container">
                <form action="#" method="post">
                    <label for="user"> </label>
                    <input type="text" name="user" id="user" placeholder="Login">
                    <label for="password"> </label>
                    <input type="password" name="password" id="password" placeholder=<?php echo gettext("Mot de passe"); ?>>
                    <button type="submit" class="btn btn-login"><?php echo gettext("Se connecter"); ?></button>
                </form>
            </div>
        </div>
        <!--Si l'utilisateur est connecté en tant qu'utilisateur (valeur 1) il n'a pas la possibilité d'ajouter un enseignant tandis que s'il est
        est connecté en tant qu'administrateur (valeur 2) il a la possibilité d'ajouter un enseignant-->
        <nav>
            <h2><?php echo gettext("Zone pour le menu"); ?></h2>
            <?php if ($_SESSION['userConnected'] >= 1) {
            ?>
                <a href="index.php"><?php echo gettext("Accueil"); ?></a>
                <?php if ($_SESSION['userConnected'] == 2) {
                ?>
                    <a href="addTeacher.php"><?php echo gettext("Ajouter un enseignant"); ?></a>
                <?php } ?>

        </nav>
    <?php } ?>
    </header>

    <div class="container">
        <div class="user-head">
            <h3><?php echo gettext("Detail :"); ?><?php
                            echo $OneTeacher["teaName"] . " " . $OneTeacher["teaFirstname"] ?>
                <?php
                //Affiche une image différente en fonction du genre de l'enseignant (se base sur la valeur de teaGender)
                if ($OneTeacher["teaGender"] == "M") {
                    echo '<img style="margin-left: 1vw;" height="20em" src="./img/male.png" alt="male symbole">';
                } else if ($OneTeacher["teaGender"] == "F") {
                    echo '<img style="margin-left: 1vw;" height="20em" src="./img/femelle.png" alt="male symbole">';
                } else if ($OneTeacher["teaGender"] == "A") {
                    echo '<img style="margin-left: 1vw;" height="20em" src="./img/autre.png" alt="male symbole">';
                }
                ?>

            </h3>
            <p>
                <?php echo $OneTeacher["secName"] ?>
            </p>
            <div class="actions">

                <!--Si l'utilisateur est connecté en tant qu'admin (valeur 2) alors il a accès à la modification et à la supression sinon pas-->
                <?php if ($_SESSION['userConnected'] == 2) { ?>
                    <a href="#">
                        <img height="20em" src="./img/edit.png" alt="edit icon"></a>
                    <a href="javascript:confirmDelete()">
                        <img height="20em" src="./img/delete.png" alt="delete icon"> </a>
                <?php } ?>
            </div>
        </div>
        <div class="user-body">
            <div class="left">
                <p><?php echo gettext("Surnom :"); echo $OneTeacher["teaNickname"]  ?></p>
                <p> <?php echo $OneTeacher["teaOrigine"]  ?></p>
            </div>
        </div>
        <div class="user-footer">
            <a href="index.html"><p><?php echo gettext("Retour a la page d'accueil")?></a>
        </div>

    </div>

    <footer>
        <p>Copyright GCR - bulle web-db - 2022</p>
    </footer>

    <script src="js/script.js"></script>

</body>

</html>