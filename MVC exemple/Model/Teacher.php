<?php
namespace Model;

use Database;
use ModelTeacher;

class Teacher {

    public $db;
    public $id;
    
    //création d'un constructeur de la class User
    function __construct() {
        $this->db = new Database();
    }

    public function Create($teacher) {

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
            'fk_section' => $this->db->GetSectionIdBySectionName($teacher['section']),
        ];

        $response = $this->db->queryPrepareExecute($query, $replacements);
    }
    
    public function GetAll() {
        $query = "SELECT * FROM t_teacher";
        //appeler la méthode pour executer la requête
        $req = $this->db->querySimpleExecute($query);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $teachers = $this->db->formatData($req);
        //retourne tous les enseignants
        return $teachers;
    }
    
    public function GetById($id) {
        //avoir la requête sql pour 1 enseignant (utilisation de l'id)
        $query = "SELECT * FROM t_teacher, t_section WHERE idTeacher = :id AND fkSection = idSection";
        //appeler la méthode pour executer la requête
        $bind = array('id' => $id);
        $req = $this->db->queryPrepareExecute($query, $bind);
        //appeler la méthode pour avoir le résultat sous forme de tableau
        $Oneteacher = $this->db->formatData($req);
        //retourne l'enseignant
        return $Oneteacher[0];
    }
    
    /*
    * Fonction permettant de modifier les informations d'un enseignant
    * @param $id        int | id de l'enseignant a mettre a jour
    * @param $teacher array | contient tous les attributs d'un enseignant a modifier
    */
    public function UpdateById($id,$teacher) {    
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
           ;" ;
   
           $replacements = [
               'id' => $id,
               'first_name' => $teacher['firstName'],
               'last_name' => $teacher['name'],
               'gender' => $teacher['genre'],
               'nick_name' => $teacher['nickName'],
               'origin' => $teacher['origin'],
               //Recupère l'id de la section grâce au nom de la section
               'fk_section' => $this->db->GetSectionIdBySectionName($teacher['section']),
           ];
   
           $this->db->queryPrepareExecute($query, $replacements);
       }
        
    }
    
    public function DeleteById() {
        
        $query = "
        DELETE FROM t_teacher 
        WHERE idTeacher = :id
    ;";

    $replacements = ['id' => $this->id];

    $this->db->queryPrepareExecute($query, $replacements);

    }
}
?>