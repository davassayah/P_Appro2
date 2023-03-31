<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Fichier permettant de se connecter à la db et contenant toutes les fonctions utilisées
 */


class Database
{


    // Variable de classe
    private $connector;

    //Fonction qui est appelée lors de l'instanciation de la classe
    public function __construct()
    {
        //Se connecter via PDO et utilise la variable de classe $connector
        try {
            $this->connector = new PDO('mysql:host=localhost:6033;dbname=P_Appro2;charset=utf8', 'root', 'root');
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }


    //Fonction permettant d'exécuter une requête de type simple
    private function querySimpleExecute($query)
    {
        return $this->connector->query($query);
    }

    //Fonction permettant de préparer, de binder et d'exécuter une requête (select avec where ou insert, update et delete)
    private function queryPrepareExecute($query, $binds)
    {

        $req = $this->connector->prepare($query);
        foreach ($binds as $bind => $value) {
            $req->bindValue($bind, $value);
        };
        $req->execute();
        return $req;
    }


    //Fonction permettant de traiter les données pour les retourner par exemple en tableau associatif (avec PDO::FETCH_ASSOC)
    private function formatData($req)
    {
        return $req->fetchALL(PDO::FETCH_ASSOC);
    }

    //Fonction permettant de vider le jeu d'enregistrement
    private function unsetData($req)
    {
        unset($req->data);
    }

    //Fonction permettant de récupérer la liste de tous les enseignants de la BD
    public function getAllTeachers()
    {
        $query = "SELECT * FROM t_teacher";
        //appeler la méthode pour executer la requête
        $req = $this->querySimpleExecute($query);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $teachers = $this->formatData($req);
        //retourne tous les enseignants
        return $teachers;
    }

    public function getAllSections()
    {
        $query = "SELECT * FROM t_section";
        //appeler la méthode pour executer la requête
        $req = $this->querySimpleExecute($query);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $sections = $this->formatData($req);
        //retourne tous les enseignants
        return $sections;
    }

    //Recupère la liste des informations pour 1 enseignant
    public function getOneTeacher($id)
    {
        //avoir la requête sql pour 1 enseignant (utilisation de l'id)
        $query = "SELECT * FROM t_teacher, t_section WHERE idTeacher = :id AND fkSection = idSection";
        //appeler la méthode pour executer la requête
        $bind = array('id' => $id);
        $req = $this->queryPrepareExecute($query, $bind);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $Oneteacher = $this->formatData($req);
        //retourne l'enseignant
        return $Oneteacher[0];
    }

    public function RenameFile()
    {
        $query = "
        SELECT idTeacher FROM t_teacher ORDER BY idTeacher desc Limit 1";
        $req = $this->querySimpleExecute($query);
        $result = $this->formatData($req);
        return  $result[0]['idTeacher'];
    }

    /**
     * Fonction permettant de créer un nouvel enseignant
     * @param $teacher array | contient tous les attributs d'un enseignant a creer
     */
    public function InsertTeacher($teacher)
    {


        $query = "
             INSERT INTO t_teacher (teaFirstname, teaName, teaGender, teaNickname, teaOrigine, teaPhoto, fkSection) 
             VALUES (:first_name, :last_name, :gender, :nick_name, :origin, :teaPhoto, :fk_section);
         ";

        $replacements = [
            
            'first_name' => $teacher[0],
            'last_name' => $teacher[1],
            'gender' => $teacher[2],
            'nick_name' => $teacher[3],
            'origin' => $teacher[4],
            'teaPhoto' => $teacher[5],
            'fk_section' => $teacher[6],
        ];

         $response = $this->queryPrepareExecute($query, $replacements);
    }

    /**
     * Fonction permettant de modifier les informations d'un enseignant
     * @param $id        int | id de l'enseignant a mettre a jour
     * @param $teacher array | contient tous les attributs d'un enseignant a modifier
     */
    public function UpdateTeacherById($id, $teacher)
    {
        $query = "
            UPDATE
                t_teacher
            SET
                teaFirstname = :firstName,
                teaName = :name,
                teaGender = :genre,
                teaNickname = :nickName,
                teaOrigine = :origin,
                teaPhoto = :imgPath,
                fkSection = :section
            WHERE
                idTeacher = :id
        ;";

        $teacher["id"] = $id;
        $this->queryPrepareExecute($query, $teacher);
    }



    /**
     * Fonction permettant de supprimer un enseignant
     * @param $id int | id de l'enseignant a supprimer
     */
    public function DeleteTeacherById($id)
    {

        $query = "
            DELETE FROM t_teacher 
            WHERE idTeacher = :id
        ;";

        $replacements = ['id' => $id];

        $this->queryPrepareExecute($query, $replacements);
    }

    /**
     * Fonction permettant d'authentifier l'utilisateur
     * @param $user string | nom d'utilisateur de l'enseignant 
     * @param $password string | mot de passe de l'enseignant
     */
    public function CheckAuth($user, $password)
    {

        $query = "
            SELECT * 
            FROM t_user 
            WHERE useLogin = :user
            AND usePassword = :password
        ";

        $replacements = [
            'user' => $user,
            'password' => $password,
        ];

        $req = $this->queryPrepareExecute($query, $replacements);

        //Retourne les données de l'utilisateur
        return $this->formatData($req)[0];
    }
}
