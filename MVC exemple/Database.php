<?php

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

    //Fonction permettant d'exécuter une requête de type simple
    public function querySimpleExecute($query)
    {
        return $this->connector->query($query);
    }

    //Fonction permettant de préparer, de binder et d'exécuter une requête (select avec where ou insert, update et delete)
    public function queryPrepareExecute($query, $binds)
    {

        $req = $this->connector->prepare($query);
        foreach ($binds as $bind => $value) {
            $req->bindValue($bind, $value);
        };
        $req->execute();
        return $req;
    }


    //Fonction permettant de traiter les données pour les retourner par exemple en tableau associatif (avec PDO::FETCH_ASSOC)
    public function formatData($req)
    {
        return $req->fetchALL(PDO::FETCH_ASSOC);
    }
}
?>