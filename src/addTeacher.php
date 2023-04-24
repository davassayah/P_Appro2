<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Page permettant d'ajouter un enseignant à la db
 */

session_start();

if ($_SESSION['userConnected'] < 1) {
    header('Location: index.php');
}

include("uploadImages/RenameImages.php");
include("Database.php");
include("header.php");
$db = new Database();

const ERRORVOID = "*Obligatoire";

$sections = $db->getAllSections();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imageData = RenameImages($_FILES, $db);
    $genreIsNotFilled = ($_POST["genre"] == null);
    $firstNameIsNotFilled = ($_POST["firstName"] == null);
    $nameIsNotFilled = ($_POST["name"] == null);
    $nickNameIsNotFilled = ($_POST["nickName"] == null);
    $sectionIsNotFilled = ($_POST["section"] == null);
    $downloadImgIsNotFilled = ($imageData["downloadImg"] == null);

    //Si le formulaire a été envoyé alors un nouvel enseignant est crée 
    if (
        !$genreIsNotFilled and
        !$firstNameIsNotFilled and
        !$nameIsNotFilled and
        !$nickNameIsNotFilled and
        !$sectionIsNotFilled and
        !$downloadImgIsNotFilled and
        ($imageData['extensionImg'] == "jpg" or $imageData['extensionImg'] == "png")
    ) {

        move_uploaded_file($imageData['fileTmpNameImg'], $imageData['uploadPathImg']);

        // On ne changera pas la valeur de $_POST en sachant que ce sont des variables read-only.
        // Ce qui veut dire qu'on ne nommera pas une varaible comme ceci -> $_POST['imPath'] = xyz !!!!!!
        // On rajoutera les variables hors formulaire en tant que params.
        $teachers = $db->InsertTeacher($_POST, $imageData);
        $errorOrValidationMessage = "L'enseignant a bien été ajouté!";
    } else {
        if ($_POST) {
            $errorOrValidationMessage = "Merci de bien remplir tous les champs marqués comme obligatoires";
        }
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
                <form action="#" method="post" id="form" enctype="multipart/form-data">
                    <h3>Ajout d'un enseignant</h3>
                    <br>
                    <p style="color:red;">
                        <?php echo $errorOrValidationMessage ?>
                    </p>
                    <p>
                        <input type="radio" id="genre1" name="genre" value="M" checked>
                        <label for="genre1">Homme</label>
                        <input type="radio" id="genre2" name="genre" value="F">
                        <label for="genre2">Femme</label>
                        <input type="radio" id="genre3" name="genre" value="A">
                        <label for="genre3">Autre</label>
                    <p style="color:red;">
                        <?php if ($_POST and $genreIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    <p>
                        <label for="firstName">Prénom :</label>
                        <input type="text" name="firstName" id="firstName" value=<?php if (isset($firstname)) echo $firstname ?>>
                    <p style="color:red;">
                        <?php if ($_POST and $firstNameIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    </p>
                    <p>
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" value=<?php if (isset($name)) echo $name ?>>
                    <p style="color:red;">
                        <?php if ($_POST and $nameIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    </p>
                    <p>
                        <label for="nickName">Surnom :</label>
                        <input type="text" name="nickName" id="nickName" value=<?php if (isset($nickName)) echo $nickName ?>>
                    <p style="color:red;">
                        <?php if ($_POST and $nickNameIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    <p>
                        <label for="origin">Origine :</label>
                        <textarea name="origin" id="origin"></textarea>
                    </p>
                    <p>
                        <label style="display: none" for="section"></label>
                        <select name="section" id="section">
                            <option value="">Section</option>
                            <?php
                            $html = "";
                            foreach ($sections as $section) {

                                $html .= "<option value='" . $section["idSection"]  . "'>"  . ($section["secName"]) . "</option>";
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
                        } else if ($_POST and ($imageData['extensionImg'] != "jpg" and $imageData['extensionImg'] != "png")) {
                            echo "Votre fichier n'est pas au bon format, merci d'utiliser le convertisseur jpg/png";
                        } else if ($imageData['extensionImg'] == "jpg" or $imageData['extensionImg'] == "png") {
                            echo "Votre fichier a bien été téléchargé";
                        }
                        ?>
                    </p>
                    <p>
                        <input type="submit" value="Ajouter">
                        <button type="button" onclick="document.getElementById('form').reset();">Effacer</button>
                    </p>
                </form>
    </fieldset>
    </div>
    <div class="user-footer">
        <a href="index.php">Retour a la page d'accueil</a>
    </div>
    </div>

    <footer>
        <p>Copyright GCR - bulle web-db - 2022</p>
    </footer>

</body>

</html>