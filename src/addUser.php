<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Page permettant d'ajouter un enseignant à la db
 */

session_start();

if ($_SESSION['userConnected'] != 1) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    require_once(__DIR__ . "/../includes/403.php");
    exit;
}

include("Database.php");
include("header.php");
$db = new Database();

const ERRORVOID = "*Obligatoire";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login = $_POST["login"] ?? '';
    $password = $_POST["password"] ?? '';
    $attribution = $_POST["attribution"] ?? '';

    $loginIsNotFilled = ($login == null);
    $passwordIsNotFilled = ($password == null);
    $attributionIsNotFilled = ($attribution == null);
}

$users = $db->getAllUsers();

if ($_SERVER['REQUEST_METHOD'] == 'POST' and !$genreIsNotFilled and !$firstNameIsNotFilled and !$nameIsNotFilled) {
    $db->createUser($_POST);
    $errorOrValidationMessage = "L'utilisateur a bien été ajouté!";
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
                <form action="" method="POST">
                    <p>
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" name="username" id="username" value=<?php if (isset($login)) echo $login ?>>
                    <p style="color:red;">
                        <?php if ($_POST and  $loginIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    </p>
                    <p>
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" value=<?php if (isset($password)) echo $password ?>>
                    <p style="color:red;">
                        <?php if ($_POST and $passwordIsNotFilled) echo ERRORVOID; ?>
                    </p>
                    </p>
                    <p>
                        <label for="is_admin">Rôle</label>
                        <select name="is_admin" id="is_admin">
                            <option value=""></option>
                            <option value="1">Administrateur</option>
                            <option value="2">Utilisateur</option>
                        </select>
                    </p>
                    <p style="color:red;">
                        <?php if ($_POST and $attributionIsNotFilled) echo ERRORVOID;
                        ?>
                    </p>
                    <input class="btn btn-primary" type="submit" value="Ajouter">
                </form>
    </fieldset>
    </div>
    </div>
</body>

</html>

<?php include("footer.php");?>