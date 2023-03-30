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

const ERRORVOID = "*Obligatoire";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Gestion du transfert de l'image
    //prends le dossier actuel
    $currentDirectory = getcwd();
    //dossier vers lequel le fichier va être transféré
    $uploadDirectoryImg = "\img\photos";
    //Récupère le fichier
    $downloadImg = $_FILES["downloadImg"];
    //Récupère le nom du fichier
    $fileNameImg = $_FILES['downloadImg']['name'];
    //Récupère le nom temporaire du fichier
    $fileTmpNameImg = $_FILES['downloadImg']['tmp_name'];
    //Reprends l'extension du fichier transféré
    $fileExtensionImg = strtolower(end(explode('.', $fileNameImg)));
    //Definis l'extension du fichier apres l'avoir recuperee
    $extensionImg = pathinfo($fileNameImg, PATHINFO_EXTENSION);
    //Permet de donner un nom unique au fichier enregistre cote serveur
    $nameId = $db->RenameFile();
    $ImgNewName = "\Img_" . $nameId + 1 . "." . $extensionImg;
    //Permet de supprimer le nom donne au fichier par l'utilisateur et de le remplacer par le nom desire
    if (isset($_FILES['downloadImg'])) {
        $fileNameImg = str_replace($fileNameImg, $ImgNewName, $fileNameImg);
    }
    //Définis le chemin final avec le nom du fichier où va être transférer le fichier en lui donnant un nom unique
    $uploadPathImg = $currentDirectory . $uploadDirectoryImg . "/" . basename($fileNameImg);
    //permet de donner un nom final au fichier
    $imgPath = $uploadDirectoryImg . $fileNameImg;

    $genre = $_POST["genre"] ?? '';
    $firstName = $_POST["firstName"] ?? '';
    $name = $_POST["name"] ?? '';
    $nickName = $_POST["nickName"] ?? '';
    $origin = $_POST["origin"] ?? '';
    $section = $_POST["section"] ?? '';
    //bien regarder l'ordre par rapport à la base de données
    $teacher = [ $firstName, $name,$genre, $nickName, $origin, $imgPath, $section];

    $genreIsNotFilled = ($genre == null);
    $firstNameIsNotFilled = ($firstName == null);
    $nameIsNotFilled = ($name == null);
    $nickNameIsNotFilled = ($nickName == null);
    $sectionIsNotFilled = ($section == null);
    $downloadImgIsNotFilled = ($downloadImg == null);
}

$sections = $db->getAllSections();

putenv("LANG=" . $_SESSION["langID"]);
setlocale(LC_ALL, $_SESSION["langID"]);
$domain = "messagesAddTeacher";
bindtextdomain($domain, "locale");
textdomain($domain);

//Si le formulaire a été envoyé alors un nouvel enseignant est crée 
if (
    $_SERVER["REQUEST_METHOD"] === "POST" and !$genreIsNotFilled and !$firstNameIsNotFilled and !$nameIsNotFilled
    and !$nickNameIsNotFilled and !$sectionIsNotFilled and !$downloadImgIsNotFilled and ($extensionImg == "jpg" or $extensionImg == "png")
) {
    move_uploaded_file($fileTmpNameImg, $uploadPathImg);
    $teachers = $db->InsertTeacher($teacher);
    $errorOrValidationMessage = "L'enseignant a bien été ajouté!";
} else {
    if ($_POST) {
        $errorOrValidationMessage = "Merci de bien remplir tous les champs marqués comme obligatoires";
    }
}

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
            <form action="#" method="post" id="form" enctype="multipart/form-data">
                <h3><?php echo gettext("Ajout d'un enseignant"); ?></h3>
                <br>
                <p style="color:red;">
                    <?php echo $errorOrValidationMessage ?>
                </p>
                <p>
                    <input type="radio" id="genre1" name="genre" value="M" checked>
                    <label for="genre1"><?php echo gettext("Homme"); ?></label>
                    <input type="radio" id="genre2" name="genre" value="F">
                    <label for="genre2"><?php echo gettext("Femme"); ?></label>
                    <input type="radio" id="genre3" name="genre" value="A">
                    <label for="genre3"><?php echo gettext("Autre"); ?></label>
                <p style="color:red;">
                    <?php if ($_POST and $genreIsNotFilled) echo ERRORVOID;
                    ?>
                </p>
                <p>
                    <label for="firstName"><?php echo gettext("Prénom :"); ?></label>
                    <input type="text" name="firstName" id="firstName" value=<?php if (isset($firstname)) echo $firstname ?>>
                <p style="color:red;">
                    <?php if ($_POST and $firstNameIsNotFilled) echo ERRORVOID;
                    ?>
                </p>
                </p>
                <p>
                    <label for="name"><?php echo gettext("Nom :"); ?>:</label>
                    <input type="text" name="name" id="name" value=<?php if (isset($name)) echo $name ?>>
                <p style="color:red;">
                    <?php if ($_POST and $nameIsNotFilled) echo ERRORVOID;
                    ?>
                </p>
                </p>
                <p>
                    <label for="nickName"><?php echo gettext("Surnom :"); ?></label>
                    <input type="text" name="nickName" id="nickName" value=<?php if (isset($nickName)) echo $nickName ?>>
                <p style="color:red;">
                    <?php if ($_POST and $nickNameIsNotFilled) echo ERRORVOID;
                    ?>
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
                        foreach ($sections as $section) {

                            $html .= "<option value='" . $section["idSection"]  . "'>"  . gettext($section["secName"]) . "</option>";
                        }
                        echo $html;
                        ?>
                    </select>
                </p>
                <p style="color:red;">
                    <?php if ($_POST and $sectionIsNotFilled) echo ERRORVOID;
                    ?>
                </p>
                <p>
                    <label for="downloadImg">Photo de l'enseignant (format jpg/png) :</label>
                    <br>
                    <input type="file" name="downloadImg" id="downloadImg" />
                    <br>
                    <a href="https://convertio.co/fr/convertisseur-jpg/">Convertissez votre fichier au format jpg/png en cliquant ici</a>
                <p style="color:red;">
                    <?php if ($_POST and $downloadImgIsNotFilled) {
                        echo ERRORVOID;
                    } else if ($_POST and ($extensionImg != "jpg" and $extensionImg != "png")) {
                        echo "Votre fichier n'est pas au bon format, merci d'utiliser le convertisseur jpg/png";
                    } else if ($extensionImg == "jpg" or $extensionImg == "png") {
                        echo "Votre fichier a bien été téléchargé";
                    }
                    ?>
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