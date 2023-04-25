<?php
session_start();

$users = $db->getAllUsers();

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
                echo "<span style='color:white;'> Bienvenue " . $users['useLogin'] . "</span>";
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
</header>