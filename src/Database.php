<?php

/**
 * ETML
 * Auteur: David Assayah
 * Date: 17.03.2023
 * Description: Fichier permettant de se connecter à la db et contenant toutes les fonctions utilisées
 */

 class Database
{
    
    private static $instance = null;
    private $connector;

    private function __construct()
    {
        include(__DIR__ . "/utils.php");
        $configs = include(__DIR__ . "/../config.php");
        try {
            $this->connector = new PDO(
                $configs['dns'],
                $configs['user'],
                getPassword()
            );
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
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
            $query = "
                SELECT
                    tt.*,
                    ts.secName AS teaSectionName
                FROM t_teacher tt
                LEFT JOIN t_section ts ON ts.idSection = tt.fkSection
            ";
            //appeler la méthode pour executer la requête
            $req = $this->querySimpleExecute($query);
            //appeler la méthode pour avoir le résultat sous forme de tableau
            $teachers = $this->formatData($req);
            //retourne tous les enseignants
            return $teachers;
        }
    
        public function getAllUsers()
        {
            $query = "SELECT * FROM t_user";
            $req = $this->querySimpleExecute($query);
            $users = $this->formatData($req);
    
            return $users;
        }
    
        public function createUser($user)
        {
            $query = "
                INSERT INTO t_user (useLogin, usePassword, useAdministrator)
                VALUES (:username, :password, :is_admin)
            ";
    
            $replacements = [
                'username' => $user['username'],
                'password' => password_hash($user['password'], PASSWORD_BCRYPT),
                'is_admin' => $user['is_admin']
            ];
    
            $this->queryPrepareExecute($query, $replacements);
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
    
    
        //Recupère la liste des informations pour 1 enseignant
        public function getOneUser($id)
        {
            //avoir la requête sql pour 1 enseignant (utilisation de l'id)
            $query = "SELECT * FROM t_user WHERE idUser = :id";
            //appeler la méthode pour executer la requête
            $bind = array('id' => $id);
            $req = $this->queryPrepareExecute($query, $bind);
            //appeler la méthode pour avoir le résultat sous forme de tableau
            $OneUser = $this->formatData($req);
            //retourne l'enseignant
            return $OneUser[0];
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
         * @param $imgData array | contient touts les attributs de l'image a uploader
         */
        public function InsertTeacher($teacher, $imgData)
        {
            $query = "
                INSERT INTO t_teacher (teaFirstname, teaName, teaGender, teaNickname, teaOrigine, teaPhoto, fkSection) 
                VALUES (:first_name, :last_name, :gender, :nick_name, :origin, :teaPhoto, :fk_section);
            ";
    
            $replacements = [
                'first_name' => $teacher['firstName'],
                'last_name' => $teacher['name'],
                'gender' => $teacher['genre'],
                'nick_name' => $teacher['nickName'],
                'origin' => $teacher['origin'],
                'teaPhoto' => $imgData['uploadDirectoryImg'] . $imgData['fileNameImg'],
                'fk_section' => $teacher['section'],
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
        public function CheckAuth($username, $password)
        {
            $query = "
                SELECT * 
                FROM t_user 
                WHERE useLogin = :username
            ";
    
            $replacements = ['username' => $username];
            $req = $this->queryPrepareExecute($query, $replacements);
            $user = $this->formatData($req)[0];
    
            if (password_verify($password, $user['usePassword'])) {
                return $user;
            } else {
                echo 'non';
            }
        }
    
        private function convertGenders($genders)
        {
            $f = '';
            foreach ($genders as $gender) {
                $f .= "'" . $gender . "', ";
            }
            return substr($f, 0, -2);
        }
    
        private function addWhereOrAnd($filter)
        {
            $operator = "";
            if ($filter) {
                $operator .= " AND ";
            } else {
                $operator .= " WHERE ";
            }
            return $operator;
        }
    
        public function sortTeachers($filters)
        {
    
            $query = "
                SELECT
                    tt.*,
                    ts.secName AS teaSectionName 
                FROM t_teacher tt
                LEFT JOIN t_section ts ON ts.idSection = tt.fkSection ";
    
    
            $filter = false;
    
            // Condition 1
            if (!empty($filters['search'])) {
                $query .= " WHERE tt.teaName LIKE :searchValue ";
                $filter = true;
            }
    
            // Condition 2
            if (isset($filters['genders'])) {
                $query .= $this->addWhereOrAnd($filter);
                $query .= " tt.teaGender IN (" . $this->convertGenders($filters['genders'])  .  ")";
                $filter = true;
            }
    
    
            // Condition 3
            if (isset($filters['section_id']) and $filters['section_id'] !== '') {
                $query .= $this->addWhereOrAnd($filter);
                $query .= " tt.fkSection = :section_id";
                $filter = true;
            }
    
            $query .= " ORDER BY tt.teaName ASC";
    
            var_dump($query);
    
            $replacements = [];
    
            if (!empty($filters['search'])) {
                $replacements['searchValue'] =  '%' . $filters['search'] . '%';
            }
            /*
            if (isset($filters['genders'])) {
    
                // $replacements['genders'] = implode(',', $filters['genders']);
                $replacements['genders'] = $this->convertGenders($filters['genders']);
                var_dump($replacements['genders']);
                
            }
    */
            if (isset($filters['section_id']) and $filters['section_id'] !== '') {
                $replacements['section_id'] = $filters['section_id'];
            }
    
            $req = $this->queryPrepareExecute($query, $replacements);
            $filtres = $this->formatData($req);
    
            return $filtres;
        }
    }
