<?php

/**
 * $dataFiles 
 * $db
 */
function RenameImages($dataFiles, $db)
{

    $imageData = [];

    if (isset($dataFiles['downloadImg'])) {
        //Gestion du transfert de l'image
        //prends le dossier actuel
        $imageData["currentDirectory"] = getcwd();
        //dossier vers lequel le fichier va être transféré
        $imageData["uploadDirectoryImg"] = "\img\photos";
        //Récupère le fichier
        $imageData["downloadImg"] = $dataFiles["downloadImg"];
        //Récupère le nom du fichier
        $imageData["fileNameImg"] = $dataFiles['downloadImg']['name'];
        //Récupère le nom temporaire du fichier
        $imageData["fileTmpNameImg"] = $dataFiles['downloadImg']['tmp_name'];
        //Reprends l'extension du fichier transféré
        $imageData["fileExtensionImg"] = strtolower(end(explode('.', $imageData["fileNameImg"])));
        //Definis l'extension du fichier apres l'avoir recuperee
        $imageData["extensionImg"] = pathinfo($imageData["fileNameImg"], PATHINFO_EXTENSION);

        $nameId = $db->RenameFile();
        $ImgNewName = "\Img_" . ($nameId + 1) . "." . $imageData["extensionImg"];
        $imageData["fileNameImg"] = $ImgNewName;

        //Définit le chemin final avec le nom du fichier où va être transférer le fichier en lui donnant un nom unique
        $imageData["uploadPathImg"] = $imageData["currentDirectory"] . $imageData["uploadDirectoryImg"] . $imageData["fileNameImg"];
    }

    // // Supprimer le fichier existant s'il y en a un
    // $imageData["filePath"] = "." . $imageData["imgPath"];
    // if (file_exists($imageData["filePath"])) {
    //     if (unlink($imageData["filePath"])) {
    //         echo 'File deleted successfully.';
    //     } else {
    //         echo 'Unable to delete file.';
    //     }
    // } else {
    //     echo 'File does not exist.';
    // }

    return $imageData;
}
