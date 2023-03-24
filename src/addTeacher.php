<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Page permettant d'ajouter un enseignant à la db
 */

session_start();

include("Database.php");
$db = new Database();

//Si le formulaire a été envoyé alors un nouvel enseignant est crée 
if ($_SERVER["REQUEST_METHOD"] === "POST")  {
    $teachers = $db->InsertTeacher($_POST);
} else {
    $sections =$db->getAllSections();
}


putenv("LANG=" . $_SESSION["langID"]);
setlocale(LC_ALL, $_SESSION["langID"]);
$domain = "messagesAddTeacher";
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
                    <input type="password" name="password" id="password" placeholder=<?php echo gettext('Mot de passe'); ?>>
                    <button type="submit" class="btn btn-login"><?php echo gettext("Se connecter"); ?></button>
                </form>
            </div>
        </div>
        <nav>
            <h2><?php echo gettext('Zone pour le menu'); ?></h2>
            <a href="index.php"><?php echo gettext('Accueil'); ?> </a>
            <a href="addTeacher.php"><?php echo gettext('Ajouter un enseignant'); ?></a>
        </nav>
    </header>

    <div class="container">
        <div class="user-body">
            <form action="#" method="post" id="form">
                <h3><?php echo gettext("Ajout d'un enseignant"); ?></h3>
                <p>
                    <input type="radio" id="genre1" name="genre" value="M" checked>
                    <label for="genre1"><?php echo gettext("Homme"); ?></label>
                    <input type="radio" id="genre2" name="genre" value="F">
                    <label for="genre2"><?php echo gettext("Femme"); ?></label>
                    <input type="radio" id="genre3" name="genre" value="A">
                    <label for="genre3"><?php echo gettext("Autre"); ?></label>
                </p>
                <p>
                    <label for="firstName"><?php echo gettext("Nom :"); ?></label>
                    <input type="text" name="firstName" id="firstName" value="">
                </p>
                <p>
                    <label for="name"><?php echo gettext("Prenom :"); ?>:</label>
                    <input type="text" name="name" id="name" value="">
                </p>
                <p>
                    <label for="nickName"><?php echo gettext("Surnom :"); ?></label>
                    <input type="text" name="nickName" id="nickName" value="">
                </p>
                <p>
                    <label for="origin"><?php echo gettext("Origine :"); ?></label>
                    <textarea name="origin" id="origin"></textarea>
                </p>
                <p>
                    <label style="display: none" for="section"></label>
                    <select name="section" id="section">
                        <option value=""><?php echo gettext("Section"); ?></option>
                        <?php 
                        $html = "";
                        foreach($sections as $section) {

                            $html .= "<option value='" . $section["idSection"]  . "'>"  . gettext($section["secName"]) . "</option>";
                        } 
                        echo $html;
                        ?>
                    </select>
                </p>
                <p>
                    <label style="display: none" for="astralSign"></label>
                    <select name="astralSign" id="astralSign">
                        <option value=""><?php echo gettext("Section"); ?></option>
                        <option value="info"><?php echo gettext("Informatique"); ?></option>
                        <option value="bois"><?php echo gettext("Bois"); ?></option>
                    </select>
                </p>
                <p>
                    <input type="submit" value=<?php echo gettext("Ajouter"); ?>>
                    <button type="button" onclick="document.getElementById('form').reset();"><?php echo gettext("Effacer"); ?></button>
                </p>
            </form>
        </div>
        <div class="user-footer">
            <a href="index.php"><?php echo gettext("Retour a la page d'accueil"); ?></a>
        </div>
    </div>

    <footer>
        <p>Copyright GCR - bulle web-db - 2022</p>
    </footer>

</body>

</html>