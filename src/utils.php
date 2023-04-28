<?php

/**
 * Retourne le mot de passe de l'utilisateur
 * 
 * Une bonne pratique consiste à stocker les secrets
 * dans des variables d'environnements ou dans un fichier json
 */
function getPassword()
{
    //read json file from url in php
    $readJSONFile = file_get_contents(__DIR__ . "/../secrets.json");

    //convert json to array in php
    $array = json_decode($readJSONFile, TRUE);

    return $array["password"];
}
