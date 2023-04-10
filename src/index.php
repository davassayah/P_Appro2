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

$sections = $db->getAllSections();

//Vérifie les identifiants de l'utilisateur grâce à la méthode CheckAuth. Si les informations n'existent ou ne correspondent pas, la valeur est nulle et une erreur s'affiche.
//Si la valeur de $user n'est pas null (les identifiants sont valides) l'utilisateur est connecté en tant qu'utilisateur si la valeur de userAdministrator est 0.
//Si la valeur de userAdministrator est 1 l'utilisateur est connecté en tant qu'administrateur et la valeur de userConnected est 2.
if (isset($_POST['user']) && isset($_POST['password'])) {
    $user = $db->CheckAuth($_POST['user'], $_POST['password']);
    if ($user == null) {
        echo "erreur de connexion";
    } else if ($user != null) {
        // echo "vous êtes connecté";
        $_SESSION['userConnected'] = $user['useAdministrator'];
    }
}

$teachers = $db->getAllTeachers();
//Récupère l'id de l'enseignant dans l'url pour le supprimer
if (isset($_GET) and $id = $_GET['idTeacher']) {
    $db->DeleteTeacherById($id);
    header('Location: index.php');
}

// Vérifie si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupère la valeur entrée dans le champ de texte
    $teachers = $db->sortTeachers($_POST);
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link href="./css/style.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script
        src="https://code.jquery.com/jquery-3.6.4.js"
        integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <title>Version statique de l'application des surnoms</title>
</head>

<body>
    <nav class="navbar bg-dark navbar-expand-lg" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand">Surnom des enseignants</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                <?php if ($_SESSION['userConnected'] >= 0) { ?>
                    <li class="nav-item">
                        <a  class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <?php if ($_SESSION['userConnected'] == 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="addTeacher.php">Ajouter un enseignant</a>
                        </li>
                    <?php } ?>
                <?php } ?>
                </ul>
            </div>
            <form class="d-flex" action="" method="post">
                <input class="form-control me-2" type="text" name="user" id="user" placeholder="Login">
                <input class="form-control me-2" type="password" name="password" id="password" placeholder="Mot de passe">
                <button class="btn btn-outline-success" type="submit">Connexion</button>
            </form>
        </div>
    </nav>
    <div class="container">
        
        <fieldset class="mb-3 mt-5">
            <h5>Filtres</h5>
            <form method="POST" action="" class="row g-3">
                <div class="col-2">
                    <label for="inputEmail4" class="form-label">Nom</label>
                    <input type="text" name="search" id="search" class="form-control">
                </div>
                <div class="col-2">
                    <label for="inputEmail4" class="form-label">Genre</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="h" value="M" name="genders[]">
                        <label class="form-check-label" for="h">
                            Homme
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="f" value="F" name="genders[]">
                        <label class="form-check-label" for="f">
                            Femme
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="a" value="A" name="genders[]">
                        <label class="form-check-label" for="a">
                            Autre (mais ussi les croissant)
                        </label>
                    </div>
                </div>

                <div class="col-2">
                    <label for="inputEmail4" class="form-label">Section</label>
                    <select name="section_id" class="form-select" aria-label="Default select example">
                        <option value="">
                        Select...
                        </option>
                        <?php foreach ($sections as $section) { ?>
                            <option value="<?php echo $section["idSection"] ?>">
                                <?php echo $section["secName"] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                
                <input type="submit" name="submit" value="Rechercher" class="btn btn-success btn-sm">
            </form>
        </fieldset>

        <h3 class="mb-3 mt-3">Liste des enseignants</h3>

        <form action="#" method="post">
            <table id="table-avec-sort" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="th-sm">
                            Nom
                        </th>
                        <th class="th-sm">
                            Surnom
                        </th>
                        <th class="th-sm">
                            Genre
                        </th>
                        <th class="th-sm">
                            Section
                        </th>
                        <th class="th-sm">
                            Options
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $teacher) { ?>
                        <tr>
                            <td><?php echo $teacher["teaName"] . " " . $teacher["teaFirstname"] ?></td>
                            <td><?php echo $teacher["teaNickname"] ?></td>
                            <td><?php echo $teacher["teaGender"] ?></td>
                            <td><?php echo $teacher["teaSectionName"] ?></td>
                            <td class="containerOptions">
                                <!--Affiche différentes fonctionnalités selon que l'utilisateur soit connecté en tant qu'utilisateur ou en tant qu'admin-->
                                <?php if ($_SESSION['userConnected'] >= 0) { ?>
                                    <?php if ($_SESSION['userConnected'] == 1) {
                                    ?>
                                        <a class="link-light" href="updateTeacher.php?idTeacher=<?php echo $teacher["idTeacher"]; ?>">
                                            <img height="20em" src="./img/edit.png" alt="edit">
                                        </a>
                                        <a class="link-light" href="javascript:confirmDelete(<?php echo $teacher["idTeacher"] ?>)">
                                            <img height="20em" src="./img/delete.png" alt="delete">
                                        </a>
                                    <?php } ?>
                                    <a class="link-light" href="detailTeacher.php?idTeacher=<?php echo $teacher["idTeacher"] ?>">
                                        <img height="20em" src="./img/detail.png" alt="detail">
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
        <footer>
            <p>Copyright GCR - bulle web-db - 2022</p>
        </footer>
        <script src="js/script.js"></script>
    </div>

    <script>
       $(document).ready( function () {
            $('#table-avec-sort').DataTable({
                searching: false,
                language: {
                    lengthMenu: "Montrer _MENU_ entrees"
                }
            });
        } );
    </script>

</body>

</html>