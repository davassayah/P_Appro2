  <?php
    /**
     * ETML
     * Auteur: David Assayah
     * Date: 17.03.2023
     * Description: Page permettant de modifier les informations d'un enseignant
     */

    session_start();

    include("Database.php");
    $db = new Database();

    //declaration des variables

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $genre = $_POST["genre"];
        $firstName = $_POST["firstName"];
        $name = $_POST["name"];
        $nickName = $_POST["nickName"];
        $origin = $_POST["origin"];
        $section = $_POST["section"];

        $genreIsNotFilled = ($genre == null);
        $firstNameIsNotFilled = ($firstName == null);
        $nameIsNotFilled = ($name == null);
        $nickNameIsNotFilled = ($nickName == null);
        $sectionIsNotFilled = ($section == null);
    }

    const ERRORVOID = "*Obligatoire";

    //Si le formulaire a été envoyé alors un nouvel enseignant est crée 
    if ($_SERVER["REQUEST_METHOD"] === "POST" and !$genreIsNotFilled and !$firstNameIsNotFilled and !$nameIsNotFilled and !$nickNameIsNotFilled and !$sectionIsNotFilled) {
        $db->UpdateTeacherById($_GET["idTeacher"], $_POST);
        //Essai d'afficher un message de confirmation de modification grâce à Javascript, sans succès
        // echo '<script type="text/javascript">
        //     function alertModify(){
        //         alert("La modification a bien été effectuée");
        //     }
        //     setTimeout(alertModify, 10000); 
        //   </script>';
        header('Location: index.php');
        die();
    }

    $sections = $db->getAllSections();

    //Récupère les informations de l'enseignant grâce à l'id de l'enseignant dans l'url
    $teacher = $db->getOneTeacher($_GET["idTeacher"]);
    ?>

  <!DOCTYPE html>
  <html lang="fr">

  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="./css/style.css" rel="stylesheet">
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
              <a href="index.php">Accueil</a>
              <a href="addTeacher.php">Ajouter un enseignant</a>
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
                                $html .= " >" . gettext($section["secName"]) . "</option>";
                            }
                            echo $html;
                            ?>
                      </select>
                  </p>
                  <p style="color:red;">
                      <?php if ($_POST and $sectionIsNotFilled) echo ERRORVOID;
                        ?>
                  <p>
                      <input type="submit" value="Modifier">
                  </p>
              </form>
          </div>
          <div class="user-footer">
              <a href="index.php">Retour à la page d'accueil</a>
          </div>
      </div>
      <footer>
          <p>Copyright GCR - bulle web-db - 2022</p>
      </footer>

  </body>

  </html>