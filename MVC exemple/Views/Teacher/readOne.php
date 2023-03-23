<!-- Fiche de l'utilisateur qui a été crée au préalable (getById du controller) -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../../Ressources/css/style.css" rel="stylesheet">
    <title>Version statique de l'application des surnoms</title>
</head>

<body>

    <header>
        <div class="container-header">
            <div class="titre-header">
                <h1>Surnom des enseignants</h1>
            </div>
            <div class="login-container">
                <form action="#" method="post">
                    <label for="user"> </label>
                    <input type="text" name="user" id="user" placeholder="Login">
                    <label for="password"> </label>
                    <input type="password" name="password" id="password" placeholder="Mot de passe">
                    <button type="submit" class="btn btn-login">Se connecter</button>
                </form>
            </div>
        </div>
        <!--Si l'utilisateur est connecté en tant qu'utilisateur (valeur 1) il n'a pas la possibilité d'ajouter un enseignant tandis que s'il est
        est connecté en tant qu'administrateur (valeur 2) il a la possibilité d'ajouter un enseignant-->
        <nav>
            <h2>Zone pour le menu</h2>
                <a href="http://localhost:3000/teachers">Accueil</a>
                    <a href="http://localhost:3000/createTeacher">Ajouter un enseignant</a>
        </nav>
    </header>
    <div class="container">
        <div class="user-head">
            <h3>Détail : <?php
                            echo $OneTeacher["teaName"] . " " . $OneTeacher["teaFirstname"] ?>
                <?php
                //Affiche une image différente en fonction du genre de l'enseignant (se base sur la valeur de teaGender)
                if ($OneTeacher["teaGender"] == "M") {
                    echo '<img style="margin-left: 1vw;" height="20em" src="../../Ressources/img/male.png" alt="male symbole">';
                } else if ($OneTeacher["teaGender"] == "F") {
                    echo '<img style="margin-left: 1vw;" height="20em" src="../../Ressources/img/femelle.png" alt="male symbole">';
                } else if ($OneTeacher["teaGender"] == "A") {
                    echo '<img style="margin-left: 1vw;" height="20em" src="../../Ressources/img/autre.png" alt="male symbole">';
                }
                ?>

            </h3>
            <p>
                <?php echo $OneTeacher["secName"] ?>
            </p>
            <div class="actions">

                <!--Si l'utilisateur est connecté en tant qu'admin (valeur 2) alors il a accès à la modification et à la supression sinon pas-->
                    <a href="#">
                        <img height="20em" src="../../Ressources/img/edit.png" alt="edit icon"></a>
                    <a href="javascript:confirmDelete()">
                        <img height="20em" src="../../Ressources/img/delete.png" alt="delete icon"> </a>
            </div>
        </div>
        <div class="user-body">
            <div class="left">
                <p>Surnom : <?php echo $OneTeacher["teaNickname"]  ?></p>
                <p> <?php echo $OneTeacher["teaOrigine"]  ?></p>
            </div>
        </div>
        <div class="user-footer">
            <a href="http://localhost:3000/teachers">Retour à la page d'accueil</a>
        </div>

    </div>

    <footer>
        <p>Copyright GCR - bulle web-db - 2022</p>
    </footer>

    <script src="js/script.js"></script>

</body>

</html>