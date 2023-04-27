<?php
session_start();

// Déconnexion de l'utilisateur
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
}

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
        $_SESSION['userId'] = $user['idUser'];
        $_SESSION['useLogin'] = $db->getOneUser($_SESSION['userId'])['useLogin'];
    }
}

?>

<header>
    <nav class="navbar bg-dark navbar-expand-lg" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand">Surnom des enseignants</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <?php if ($_SESSION['userConnected'] >= 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Accueil</a>
                        </li>
                        <?php if ($_SESSION['userConnected'] == 1) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="addTeacher.php">Ajouter un enseignant</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="addUser.php">Ajouter un utilisateur</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <?php
            if ($_SESSION['userConnected'] != null) {
                echo "<span style='color:white;'> Bienvenue " . $_SESSION['useLogin'] . "</span>";
                echo "<form class='d-flex' action='' method='post'>";
                echo "<button class='btn btn-outline-danger' type='submit' name='logout'>Déconnexion</button>";
                echo "</form>";
            } else { // Sinon, affiche le formulaire
            ?>
                <form class="d-flex" action="" method="post">
                    <input class="form-control me-2" type="text" name="user" id="user" placeholder="Login">
                    <input class="form-control me-2" type="password" name="password" id="password" placeholder="Mot de passe">
                    <button class="btn btn-outline-success" type="submit">Connexion</button>
                </form>
            <?php } ?>
        </div>
            </nav>