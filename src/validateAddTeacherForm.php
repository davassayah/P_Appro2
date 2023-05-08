<?php

/**
 * ETML
 * Auteur: GCR
 * Date: 21.02.2022
 * Description: Fichier permettant la désinfection et la validation des données saisies par l'utilisateur lors de la
 * modification ou de l'ajout d'un enseignant
 */

const ERROR_GENDER_REQUIRED    = "Veuillez renseigner le champ genre de l'enseignant";
const ERROR_IMAGE_REQUIRED    = "Veuillez ajouter l'image de l'enseignant";
const ERROR_IMAGE_EXTENSION    = "Seuls les formats jpg/png sont acceptés";
const ERROR_FIRSTNAME_REQUIRED = "Veuillez renseigner le champ prénom de l'enseignant";
const ERROR_LASTNAME_REQUIRED  = "Veuillez renseigner le champ nom de l'enseignant";
const ERROR_NICKNAME_REQUIRED  = "Veuillez renseigner le champ surnom de l'enseignant";
const ERROR_ORIGIN_REQUIRED    = "Veuillez renseigner le champ origine de l'enseignant";
const ERROR_SECTION_REQUIRED   = "Veuillez renseigner le champ section de l'enseignant";
const ERROR_LENGTH             = "Le champ doit avoir un nombre de caractères entre 2 et 30";
const ERROR_STRING             = "Pour ce champ, vous devez saisir une chaine entre 2 et 30 caractères mais seuls " .
    " les caractères suivant sont autorisés : les lettres de a à z (minuscules ou majuscules), les accents, " .
    "l'espace, le - et le '";

const ERROR_SECTION_NAME_REQUIRED  = "Veuillez renseigner le champ nom de la section";

const REGEX_STRING = '/^[a-zàâçéèêîïôûù -]{2,30}$/mi';

function validationTeacherForm($db)
{

    // ATTENTION
    // Si on désinfecte les data avec FILTER_SANITIZE_FULL_SPECIAL_CHARS
    // on obtient des strings qui ont été modifiées avec des < ou > ou & etc
    // donc après on ne peut plus faire de validation avec des REGEX précise ...
    // de plus lorsque l'on affiche une variable après avoir été désinfectée, on ne voit pas, dans le navigateur,
    // les caractères < ou > ou & etc car le navigateur les re-transforme
    // Donc on ne sanitize pas ci-dessous certains champs car on veut leur appliiquer une REGEX particulière

    // On commence par désinfecter les données saisies par l'utilisateur
    // ainsi on se protège contre les attaques de types XSS

    $userData = filter_input_array(
        INPUT_POST,
        [
            'genre' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            // on ne filtre pas les 3 champs car on veut effectuer une validation par REGEX
            // tout en affichant une erreur précise à l'utilisateur
            'firstName' => $_POST['firstName'],
            'name'  => $_POST['name'],
            'nickName' => $_POST['nickName'],
            'origin'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'section'  => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ]
    );

// $imageData = UpdateImages($_FILES, $teacher);
$imageData = RenameImages($_FILES, $db);

    $userData['imageData'] = $imageData;

    // echo "<pre>";
    // var_dump($userData);
    // echo "</pre>";

    // Si certains champs n'ont pas été saisies alors on donne la valeur ''
    $genre    = $userData['genre']    ?? '';
    $firstName = $userData['firstName'] ?? '';
    $name = $userData['name']  ?? '';
    $nickName  = $userData['nickName']  ?? '';
    $origin   = $userData['origin']   ?? '';
    $section   = $userData['section']   ?? '';
    $downloadImg = $userData['imageData']['downloadImg']['size'] ?? '';

    $errors = [];

    //
    // Validation des données
    //

    // le champ genre est obligatoire
    if (!$genre) {
        $errors['genre'] = ERROR_GENDER_REQUIRED;
    }

    // le champ prénom :
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$firstName) {
        $errors['firstName'] = ERROR_FIRSTNAME_REQUIRED;
    } elseif (!filter_var(
        $firstName,
        FILTER_VALIDATE_REGEXP,
        array(
            "options" => array("regexp" => REGEX_STRING)
        )
    )) {
        $errors["firstName"] = ERROR_STRING;
    }
    // le champ nom
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$name) {
        $errors['name'] = ERROR_LASTNAME_REQUIRED;
    } elseif (!filter_var(
        $name,
        FILTER_VALIDATE_REGEXP,
        array(
            "options" => array("regexp" => REGEX_STRING)
        )
    )) {
        $errors["name"] = ERROR_STRING;
    }

    // le champ surnom
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$nickName) {
        $errors['nickName'] = ERROR_NICKNAME_REQUIRED;
    } elseif (!filter_var(
        $nickName,
        FILTER_VALIDATE_REGEXP,
        array(
            "options" => array("regexp" => REGEX_STRING)
        )
    )) {
        $errors["nickName"] = ERROR_STRING;
    }

    // le champ origine est obligatoire
    if (!$origin) {
        $errors['origin'] = ERROR_ORIGIN_REQUIRED;
    }

    // le champ section est obligatoire et ne peut donc pas avoir
    // la valeur "Section"
    if (!$section || $section === "Section") {
        $errors['section'] = ERROR_SECTION_REQUIRED;
    }

    if (!$downloadImg) {
        $errors['downloadImg'] = ERROR_IMAGE_REQUIRED;
    } elseif (!in_array($imageData['extensionImg'], ['jpg', 'png'])) {
        $errors['downloadImg'] = ERROR_IMAGE_EXTENSION;
    }
    return ["userData" => $userData, "errors" => $errors];
}


