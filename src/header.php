<?php
session_start();

require_once 'Database.php';
$db = Database::getInstance();

if (isset($_POST['user']) && isset($_POST['password'])) {
    $user = $db->CheckAuth($_POST['user'], $_POST['password']);
    if ($user == null) {
        echo "erreur de connexion";
    } else if ($user != null) {
        //echo "vous êtes connecté";
        $_SESSION['userConnected'] = $user['useAdministrator'];
        $_SESSION['userId'] = $user['idUser'];
        $_SESSION['useLogin'] = $db->getOneUser($_SESSION['userId'])['useLogin'];
    }
}

// Déconnexion de l'utilisateur
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

?>

<header>
    <nav class="navbar bg-dark navbar-expand-lg" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand">Surnom des enseignants</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <?php
                    if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] >= 1) {
                    ?>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item mt-3">
                                <a class="nav-link" href="index.php">Accueil</a>
                            </li>
                            <?php
                            if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == 1) {
                            ?>
                                <li class="nav-item mt-3 text-nowrap">
                                    <a class="nav-link" href="addTeacher.php">Ajouter un enseignant</a>
                                </li>
                                <li class="nav-item mt-3 text-nowrap">
                                    <a class="nav-link" href="addUser.php">Ajouter un utilisateur</a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == 1) {
                        echo '<form class="nav-admin" action="" method="post">';
                        echo '<span class="nav-item text-white text-nowrap">Bienvenue ' . $_SESSION['useLogin'] . '</span>';
                        echo '<button class="btn btn-outline-danger mx-3" type="submit" name="logout">Déconnexion</button>';
                        echo '</form>';
                    } else if (isset($_SESSION['userConnected']) && $_SESSION['userConnected'] == 2) {
                        echo '<form class="nav-user" action="" method="post">';
                        echo '<span class="nav-item text-white text-nowrap">Bienvenue ' . $_SESSION['useLogin'] . '</span>';
                        echo '<button class="btn btn-outline-danger mx-3" type="submit" name="logout">Déconnexion</button>';
                        echo '</form>';
                    } else {
                        echo '<form class="d-flex" action="" method="post">';
                        echo '<input class="form-control me-2 mt-3" type="text" name="user" id="user" placeholder="Login">';
                        echo '<input class="form-control me-2 mt-3" type="password" name="password" id="password" placeholder="Mot de passe">';
                        echo '<button class="btn btn-outline-success mt-3" type="submit">Connexion</button>';
                        echo '</form>';
                    }
                    ?>
            </div>
    </nav>