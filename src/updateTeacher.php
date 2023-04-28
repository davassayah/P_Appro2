<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Page permettant de modifier les informations d'un enseignant
 */

session_start();

if ($_SESSION['userConnected'] != 1) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/403.php");
    exit;
}

include("uploadImages/UpdateImages.php");
include("Database.php");
include("header.php");

const ERRORVOID = "*Obligatoire";

//Récupère les informations de l'enseignant grâce à l'id de l'enseignant dans l'url
$teacher = $db->getOneTeacher($_GET["idTeacher"]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $imageData = UpdateImages($_FILES, $teacher);
    $_POST["imgPath"] = $imageData["imgPath"];

    $genreIsNotFilled =  ($_POST["genre"] == null);
    $firstNameIsNotFilled = ($_POST["firstName"] == null);
    $nameIsNotFilled = ($_POST["name"] == null);
    $nickNameIsNotFilled = ($_POST["nickName"] == null);
    $sectionIsNotFilled = ($_POST["section"] == null);
    $downloadImgIsNotFilled = ($imageData["downloadImg"] == null);
}

//Si le formulaire a été envoyé alors un nouvel enseignant est crée 
if (
    $_SERVER["REQUEST_METHOD"] === "POST" and !$genreIsNotFilled and !$firstNameIsNotFilled and !$nameIsNotFilled and !$nickNameIsNotFilled
    and !$sectionIsNotFilled and !$downloadImgIsNotFilled and ($imageData["extensionImg"] == "jpg" or $imageData["extensionImg"] == "png")
) {
    move_uploaded_file($imageData["fileTmpNameImg"], $imageData["filePath"]);
    $db->UpdateTeacherById($_GET["idTeacher"], $_POST);
    header('Location: index.php');
    die();
} else if ($_POST and ($imageData["extensionImg"] != "jpg" or $imageData["extensionImg"] != "png") and $imageData["fileNameImg"] != null) {
    echo "Merci de vérifier que tous les champs sont bien remplis correctement et que l'extension du fichier est jpg/png";
} else
    if ($_POST and ($imageData["fileNameImg"] == null)) {
    $db->UpdateTeacherById($_GET["idTeacher"], $_POST);
    header('Location: index.php');
}

$sections = $db->getAllSections();

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
    <div class="container">
        <fieldset class="mb-5 mt-4">
            <div class="user-body">
                <form action="#" method="post" id="form" enctype="multipart/form-data">
                    <h3>Modifier un enseignant</h3>
                    <p>
                        <!--Condition permettant de sélectionner le genre de l'enseignant déjà renseigné-->
                        <input type="radio" id="genre1" name="genre" value="M" <?php if ($teacher['teaGender'] == 'M') { ?>checked<?php } ?>>
                        <label for="genre1">Homme</label>
                        <input type="radio" id="genre2" name="genre" value="F" <?php if ($teacher['teaGender'] == 'F') { ?>checked<?php } ?>>
                        <label for="genre2">Femme</label>
                        <input type="radio" id="genre3" name="genre" value="A" <?php if ($teacher['teaGender'] == 'A') { ?>checked<?php } ?>>
                        <label for="genre3">Autre</label>
                    <p style="color:red;">
                        <?php if ($_POST and $genreIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    </p>
                    <p>
                        <label for="firstName">Nom :</label>
                        <input type="text" name="firstName" id="firstName" value="<?php echo $teacher['teaFirstname'] ?>">
                    <p style="color:red;">
                        <?php if ($_POST and $firstNameIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    </p>
                    <p>
                        <label for="name">Prénom :</label>
                        <input type="text" name="name" id="name" value="<?php echo $teacher['teaName'] ?>">
                    <p style="color:red;">
                        <?php if ($_POST and $nameIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    </p>
                    <p>
                        <label for="nickName">Surnom :</label>
                        <input type="text" name="nickName" id="nickName" value="<?php echo $teacher['teaNickname'] ?>">
                    <p style="color:red;">
                        <?php if ($_POST and $nickNameIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    </p>
                    <p>
                        <label for="origin">Origine :</label>
                        <textarea name="origin" id="origin"><?php echo $teacher['teaOrigine'] ?></textarea>
                    </p>
                    <p>
                        <label style="display: none" for="section"></label>
                        <select name="section" id="section">
                            <option value="">Section</option>
                            <!--Condition permettant de sélectionner la section de l'enseignant déjà renseigné-->
                            <?php
                            $html = "";
                            foreach ($sections as $section) {

                                $html .= "<option value='" . $section["idSection"]  . "' ";
                                if ($section["idSection"] === $teacher["fkSection"]) {

                                    $html .= " selected ";
                                }
                                $html .= " >" . ($section["secName"]) . "</option>";
                            }
                            echo $html;
                            ?>
                        </select>
                    </p>
                    <p style="color:red;">
                        <?php if ($_POST and $sectionIsNotFilled) echo ERRORVOID;
                        ?>
                    <p>
                    <div>
                        <img src=<?php echo $teacher["teaPhoto"] ?>>
                    </div>
                    <label for="downloadImg">Photo de l'enseignant (format jpg/png) :</label>
                    <br>
                    <input type="file" name="downloadImg" id="downloadImg" />
                    <br>
                    <a href="https://convertio.co/fr/convertisseur-jpg/" target="_blank">Convertissez votre fichier au format jpg/png en cliquant ici</a>
                    <p style="color:red;">
                        <?php
                        if (($imageData["fileNameImg"] != null)) {
                            if ($_POST and ($imageData["extensionImg"] != "jpg" and $imageData["extensionImg"] != "png")) {
                                echo "Votre fichier n'est pas au bon format, merci d'utiliser le convertisseur jpg/png";
                            } else if ($imageData["extensionImg"] == "jpg" or $imageData["extensionImg"] == "png") {
                                echo "Votre fichier a bien été téléchargé";
                            }
                        }
                        ?>
                    </p>
                    <p>
                        <input type="submit" value="Modifier">
                    </p>
                </form>
            </div>
    </div>
</body>

</html>

<?php include("footer.php"); ?>