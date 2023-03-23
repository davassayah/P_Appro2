<!--Formulaire qui permet de modifier les données (updateById)-->
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
                <form action="" method="post">
                    <label for="user"> </label>
                    <input type="text" name="user" id="user" placeholder="Login">
                    <label for="password"> </label>
                    <input type="password" name="password" id="password" placeholder="Mot de passe">
                    <button type="submit" class="btn btn-login">Se connecter</button>
                </form>
            </div>
        </div>
        <nav>
            <h2>Zone pour le menu</h2>
            <a href="http://localhost:3000/teachers">Accueil</a>
            <a href="http://localhost:3000/createTeacher">Ajouter un enseignant</a>
        </nav>
    </header>

    <div class="container">
        <div class="user-body">
            <form action="#" method="post" id="form">
                <h3>Modifier un enseignant</h3>
                <p>
                    <!--Condition permettant de sélectionner le genre de l'enseignant déjà renseigné-->
                    <input type="radio" id="genre1" name="genre" value="M" <?php if ($teacher['teaGender'] == 'M') { ?>checked<?php } ?>>
                    <label for="genre1">Homme</label>
                    <input type="radio" id="genre2" name="genre" value="F" <?php if ($teacher['teaGender'] == 'F') { ?>checked<?php } ?>>
                    <label for="genre2">Femme</label>
                    <input type="radio" id="genre3" name="genre" value="A" <?php if ($teacher['teaGender'] == 'A') { ?>checked<?php } ?>>
                    <label for="genre3">Autre</label>
                </p>
                <p>
                    <label for="firstName">Nom :</label>
                    <input type="text" name="firstName" id="firstName" value="<?php echo $teacher['teaFirstname'] ?>">
                </p>
                <p>
                    <label for="name">Prénom :</label>
                    <input type="text" name="name" id="name" value="<?php echo $teacher['teaName'] ?>">
                </p>
                <p>
                    <label for="nickName">Surnom :</label>
                    <input type="text" name="nickName" id="nickName" value="<?php echo $teacher['teaNickname'] ?>">
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
                        <option value="info" <?php if ($teacher['secName'] == 'Informatique') { ?>selected<?php } ?>>Informatique</option>
                        <option value="bois" <?php if ($teacher['secName'] == 'Bois') { ?>selected<?php } ?>>Bois</option>
                    </select>
                </p>
                <p>
                    <input type="submit" value="Modifier">
                </p>
            </form>
        </div>
        <div class="user-footer">
            <a href="http://localhost:3000/teachers">Retour à la page d'accueil</a>
        </div>
    </div>

    <footer>
        <p>Copyright GCR - bulle web-db - 2022</p>
    </footer>

</body>

</html>