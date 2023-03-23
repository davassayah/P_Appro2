<?php
//Utilise le modèle crée
namespace Controller;

use Model\Teacher;

class TeacherController
{

    private $modelTeacher;

    public function __construct()
    {
        $this->modelTeacher = new Teacher();
    }

    //rajouter l'entité dans les () de la fonction en tant que paramètre
    public function Create()
    {
        //Essaye de réaliser le code, si ça ne fonctionne pas reporte l'erreur dans le catch
        try {
            if ($_POST) {
                $newTeacher = $this->modelTeacher->Create($_POST);
            }
            include('Views/Teacher/create.php');
            return;
            //appel la fonction Create du modèle Teacher
        } catch (\Throwable $th) {
            //throw $th;
            //Gère les erreurs
        }
    }

    public function GetAll()
    {
        try {
            $teachers = $this->modelTeacher->GetAll();
            include('Views/Teacher/index.php');
            return;
        } catch (\Throwable $th) {
            //throw $th;
            //Gère les erreurs
        }
    }

    public function GetById()
    {
        try {
            $OneTeacher = $this->modelTeacher->GetById($_GET['id']);
            include('Views/Teacher/readOne.php');
            return;
        } catch (\Throwable $th) {
            //throw $th;
            //Gère les erreurs
        }
    }

    public function UpdateById()
    {

        try {
            $teacher = $this->modelTeacher->GetById($_GET["id"]);
            if ($_POST) {
            $updateteacher = array_merge($teacher,$_POST);
            $teacher = $this->modelTeacher->UpdateById($_GET["id"], $_POST);
                header('Location: http://localhost:3000/teachers');
                return;
            };
            include('Views/Teacher/modify.php');
        } catch (\Throwable $th) {
            //throw $th;
            //Gère les erreurs
        }
    }
    
    public function DeleteById()
    {
        if ($id = $_GET['idTeacher']) 
        var_dump($_GET['idTeacher']);
        {
            $this->modelTeacher->DeleteById($id);
            include('Views/Teacher/index.php');
            header('Location: http://localhost:3000/teachers');
        } 
    }
}
