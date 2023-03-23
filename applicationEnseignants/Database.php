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

    /**
     * Fonction permettant de créer un nouvel enseignant
     * @param $teacher array | contient tous les attributs d'un enseignant a creer
     */
    public function InsertTeacher($teacher)
    {

        $query = "
            INSERT INTO t_teacher (teaFirstname, teaName, teaGender, teaNickname, teaOrigine, fkSection) 
            VALUES (:first_name, :last_name, :gender, :nick_name, :origin, :fk_section);
        ";

        $replacements = [
            'first_name' => $teacher['firstName'],
            'last_name' => $teacher['name'],
            'gender' => $teacher['genre'],
            'nick_name' => $teacher['nickName'],
            'origin' => $teacher['origin'],
            'fk_section' => $this->GetSectionIdBySectionName($teacher['section']),
        ];

        $response = $this->queryPrepareExecute($query, $replacements);
    }

    /**
     * Fonction permettant de récupérer l'id de la section via le nom de la section
     * @param $name string | Nom de la section
     */
    public function GetSectionIdBySectionName($name)
    {

        if ($name == 'info') {
            $name = 'informatique';
        }

        $query = "
            SELECT
              idSection
            FROM t_section
            WHERE secName = :sec_name
        ";

        $replacements = ['sec_name' => $name];

        $request = $this->queryPrepareExecute($query, $replacements);

        // fetchObject permet de recuperer les valeurs d'une entree dans la db
        $result = $request->fetchObject();

        return $result->idSection;
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
                teaFirstname = :first_name,
                teaName = :last_name,
                teaGender = :gender,
                teaNickname = :nick_name,
                teaOrigine = :origin,
                fkSection = :fk_section
            WHERE
                idTeacher = :id
        ;";

        $replacements = [
            'id' => $id,
            'first_name' => $teacher['firstName'],
            'last_name' => $teacher['name'],
            'gender' => $teacher['genre'],
            'nick_name' => $teacher['nickName'],
            'origin' => $teacher['origin'],
            //Recupère l'id de la section grâce au nom de la section
            'fk_section' => $this->GetSectionIdBySectionName($teacher['section']),
        ];

        $this->queryPrepareExecute($query, $replacements);
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
