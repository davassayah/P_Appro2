<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Page d'accueil où les informations des enseignants enregistrés sont visibles et où il est possible de consulter, modifier ou supprimer un enseignant
 * selon que l'on soit utilisateur ou administrateur.
 */

session_start();
include("Database.php");
$db = new Database();

if (!isset($_SESSION["langID"])) {
    $_SESSION["lang"] = "fr_CH";
}

if (isset($_GET["langID"])) {
    $_SESSION["langID"] = $_GET["langID"];
}

putenv("LANG=" . $_SESSION["langID"]);
setlocale(LC_ALL, $_SESSION["langID"]);
$domain = "messagesIndex";
bindtextdomain($domain, "locale");
textdomain($domain);

//Vérifie les identifiants de l'utilisateur grâce à la méthode CheckAuth. Si les informations n'existent ou ne correspondent pas, la valeur est nulle et une erreur s'affiche.
//Si la valeur de $user n'est pas null (les identifiants sont valides) l'utilisateur est connecté en tant qu'utilisateur si la valeur de userAdministrator est 0.
//Si la valeur de userAdministrator est 1 l'utilisateur est connecté en tant qu'administrateur et la valeur de userConnected est 2.
if (isset($_POST['user']) && isset($_POST['password'])) {
    $user = $db->CheckAuth($_POST['user'], $_POST['password']);
    if ($user == null) {
        echo "erreur de connexion";
    } else if ($user != null) {
        echo "vous êtes connecté";
        if ($user['useAdministrator'] == 0) {
            $_SESSION['userConnected'] = 1;
        }
        if ($user['useAdministrator'] == 1) {
            $_SESSION['userConnected'] = 2;
        }
    }
}

$teachers = $db->getAllTeachers();
//Récupère l'id de l'enseignant dans l'url pour le supprimer
if ($id = $_GET['idTeacher']) {
    $db->DeleteTeacherById($id);
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/style.css" rel="stylesheet">
    <title><?php echo gettext("Version statique de l'application des surnoms"); ?> </title>
</head>

<body>

    <header>
        <div class="container-header">
            <div class="titre-header">
                <h1><?php echo gettext('Surnom des enseignants'); ?></h1>
            </div>
            <form name="langSelect" action="" method="get">
                <select name="langID" id="langID">
                    <option>
                        <p><?php echo gettext('Choisissez une langue'); ?></p>
                    </option>
                    <option value="fr_CH"><?php echo gettext('Francais'); ?></option>
                    <option value="en_US"><?php echo gettext('Anglais'); ?></option>
                </select>
                <br /><br />
                <button type="submit"><?php echo gettext('Submit'); ?></button>

            </form>
            <div class="login-container">
                <form action="" method="post">
                    <label for="user"> </label>
                    <input type="text" name="user" id="user" placeholder="Login">
                    <label for="password"> </label>
                    <input type="password" name="password" id="password" placeholder=<?php echo gettext('Mot de passe'); ?>>
                    <button type="submit" class="btn btn-login"><?php echo gettext('Se connecter'); ?></button>
                    <div class="col-md-6">
                    </div>
                </form>
            </div>
        </div>
        <nav>
            <!--Affiche certaines fonctionnalités selon que l'utilisateur soit connecté en tant qu'utilisateur ou en tant qu'admin-->
            <h2><?php echo gettext('Zone pour le menu'); ?></h2>
            <?php if ($_SESSION['userConnected'] >= 1) {
            ?>
                <a href="index.php"><?php echo gettext('Accueil'); ?> </a>
                <?php if ($_SESSION['userConnected'] == 2) {
                ?>
                    <a href="addTeacher.php"><?php echo gettext('Ajouter un enseignant'); ?></a>
                <?php } ?>
        </nav>
    <?php } ?>
    </header>

    <div class="container">
        <h3><?php echo gettext('Liste des enseignants'); ?></h3>
        <form action="#" method="post">
            <table>
                <thead>
                    <tr>
                        <th><?php echo gettext('Nom'); ?></th>
                        <th><?php echo gettext('Surnom'); ?></th>
                        <th><?php echo gettext('Options'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($teachers as $teacher) { ?>
                        <tr>
                            <td><?php echo $teacher["teaName"] . " " . $teacher["teaFirstname"] ?></td>
                            <td><?php echo $teacher["teaNickname"] ?></td>
                            <td class="containerOptions">
                                <!--Affiche différentes fonctionnalités selon que l'utilisateur soit connecté en tant qu'utilisateur ou en tant qu'admin-->
                                <?php if ($_SESSION['userConnected'] >= 1) {
                                ?>
                                    <?php if ($_SESSION['userConnected'] == 2) {
                                    ?>
                                        <a href="updateTeacher.php?idTeacher=<?php echo $teacher["idTeacher"]; ?>">
                                            <img height="20em" src="./img/edit.png" alt="edit">
                                        </a>
                                        <a href="javascript:confirmDelete(<?php echo $teacher["idTeacher"] ?>)">
                                            <img height="20em" src="./img/delete.png" alt="delete">
                                        </a>
                                    <?php } ?>
                                    <a href="detailTeacher.php?idTeacher=<?php echo $teacher["idTeacher"] ?>">
                                        <img height="20em" src="./img/detail.png" alt="detail">
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
        <script src="js/script.js"></script>
    </div>

    <footer>
        <p>Copyright GCR - bulle web-db - 2022</p>
    </footer>

</body>

</html>