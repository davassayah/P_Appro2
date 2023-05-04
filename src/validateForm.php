<?php

/**
 * ETML
 * Auteur: GCR
 * Date: 21.02.2022
 * Description: Fichier permettant la désinfection et la validation des données saisies par l'utilisateur lors de la
 * modification ou de l'ajout d'un enseignant
 */

const ERROR_GENDER_REQUIRED    = "Veuillez renseigner le champ genre de l'enseignant";
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

function validationSectionForm()
{
    $name = $_POST['name'] ?? '';
    $errors = [];

    if (!$name) {
        $errors['name'] = ERROR_SECTION_NAME_REQUIRED;
    } elseif (!filter_var(
        $name,
        FILTER_VALIDATE_REGEXP,
        array(
            "options" => array("regexp" => REGEX_STRING)
        )
    )) {
        $errors["name"] = ERROR_STRING;
    }

    return ["name" => $name, "errors" => $errors];
}

function validationTeacherForm()
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
            'gender'    => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            // on ne filtre pas les 3 champs car on veut effectuer une validation par REGEX
            // tout en affichant une erreur précise à l'utilisateur
            'firstName' => $_POST['firstName'],
            'lastName'  => $_POST['lastName'],
            'nickname'  => $_POST['nickname'],
            'origine'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'section'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ]
    );

    // Si certains champs n'ont pas été saisies alors on donne la valeur ''
    $gender    = $userData['gender']    ?? '';
    $firstName = $userData['firstName'] ?? '';
    $lastName  = $userData['lastName']  ?? '';
    $nickname  = $userData['nickname']  ?? '';
    $origine   = $userData['origine']   ?? '';
    $section   = $userData['section']   ?? '';

    $errors = [];

    //
    // Validation des données
    //

    // le champ genre est obligatoire
    if (!$gender) {
        $errors['gender'] = ERROR_GENDER_REQUIRED;
    }

    // le champ prénom :
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$firstName) {
        $errors['firstName'] = ERROR_FIRSTNAME_REQUIRED;
    } elseif (!filter_var(
        $lastName,
        FILTER_VALIDATE_REGEXP,
        array(
            "options" => array("regexp" => REGEX_STRING)
        )
    )) {
        $errors["lastName"] = ERROR_STRING;
    }
    // le champ nom
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$lastName) {
        $errors['lastName'] = ERROR_LASTNAME_REQUIRED;
    } elseif (!filter_var(
        $lastName,
        FILTER_VALIDATE_REGEXP,
        array(
            "options" => array("regexp" => REGEX_STRING)
        )
    )) {
        $errors["lastName"] = ERROR_STRING;
    }

    // le champ surnom
    // - est obligatoire
    // - doit être une string entre 2 et 30 caractères
    // - répondant à la REGEX 'REGEX_STRING'
    if (!$nickname) {
        $errors['nickname'] = ERROR_NICKNAME_REQUIRED;
    } elseif (!filter_var(
        $nickname,
        FILTER_VALIDATE_REGEXP,
        array(
            "options" => array("regexp" => REGEX_STRING)
        )
    )) {
        $errors["nickname"] = ERROR_STRING;
    }

    // le champ origine est obligatoire
    if (!$origine) {
        $errors['origine'] = ERROR_ORIGIN_REQUIRED;
    }

    // le champ section est obligatoire et ne peut donc pas avoir
    // la valeur "Section"
    if (!$section || $section === "Section") {
        $errors['section'] = ERROR_SECTION_REQUIRED;
    }

    return ["userData" => $userData, "errors" => $errors];
}