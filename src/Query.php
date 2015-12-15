<?php
/**
 * Created by PhpStorm.
 * User: Taf
 * Date: 13/12/2015
 * Time: 12:27
 */

namespace Touffik;




class Query extends Database
{

    private $select = "*";
    private $orderBy = "id ASC";
    private $orderLimit = "1";
    private $condition = "true";

//    private $PDO;
//
//    public function __construct()
//    {
//
//        $yaml = new Parser();
//
//        $parameters = $yaml->parse(file_get_contents('config/parameters.yml'));
//        $parameters = $parameters['parameters'];
//
//        $dsn = $parameters['driver'] . ':dbname=' . $parameters['name'] . ';host=' . $parameters['host'];
//
//
//        try {
//            $PDO = new \PDO($dsn, $parameters["user"], $parameters["password"]);
//            $this->PDO = $PDO;
//            echo "connect success";
//
//        } catch (PDOException $e) {
//            echo 'Connexion échouée : ' . $e->getMessage();
//        }
//
//    }

    public function orderBy(string $column)
    {
        $this->orderBy = $column;
    }

    public function where(string $condition)
    {
        $this->condition = $condition;
    }



    public function find($table)
    {
        $req = $this->PDO->prepare("SELECT $this->select FROM $table WHERE $this->condition ORDER BY $this->orderBy");
        $req->execute();
        $res = [];
        foreach ($req->fetchAll() as $datas => $data) {
            $res[] = $this->createEntity($table, $data);
        }
        $this->orderBy = "ASC";
        $this->condition = "1";
        $this->select = "*";
        return $res;

    }

    public function findOne($table)
    {
        $req = $this->PDO->prepare("SELECT $this->select FROM $table WHERE $this->condition ORDER BY $this->orderBy LIMIT $this->orderLimit");
        $req->execute();
        $res = $this->createEntity($table, $req->fetch());

        $this->orderBy = "ASC";
        $this->condition = "1";
        $this->select = "*";
        return $res;

    }



    public function save($object)
    {
        $properties = $object->getProperties();
        $table = strtolower(get_class($object));
        $id = $object->getId();

        // used for do an Insert
        if ($id == null) {
            $req = $this->PDO->prepare("INSERT INTO $table (id) VALUES (DEFAULT);");
            $req->execute();
            $id = $this->PDO->lastInsertId();

        }
        $set = "";
        $i = 0;
        foreach ($properties as $key => $data) {
            $key = str_replace("*", "", $key);
            if ($data != null) {
                if ($i > 0) $set .= ",";
                $set .= " $key = '$data'";
                $i++;
            }
        }
        $req = $this->PDO->prepare("UPDATE $table SET $set WHERE id = '$id';");
        $req->execute();
    }


    public function delete($object)
    {
        $tablename = strtolower(get_class($object));
        $req = $this->PDO->prepare("DELETE $tablename WHERE $object->getId()");
        $req->execute();
    }
}
