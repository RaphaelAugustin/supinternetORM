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
    private $orderBy = "ASC";
    private $orderLimit = "0";
    private $where = "true";

    private $PDO;

    public function __construct()
    {

        $yaml = new Parser();

        $parameters = $yaml->parse(file_get_contents('config/parameters.yml'));
        $parameters = $parameters['parameters'];

        $dsn = $parameters['driver'] . ':dbname=' . $parameters['name'] . ';host=' . $parameters['host'];


        try {
            $PDO = new \PDO($dsn, $parameters["user"], $parameters["password"]);
            $this->PDO = $PDO;
            echo "connect success";

        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }

    }

        public function find($table)
    {
        $req = $this->PDO->prepare("SELECT $this->select FROM $table WHERE $this->where ORDER BY $this->orderBy");
        $res = [];
        foreach ($req->fetchAll() as $datas => $data) {
            $res[] = $this->createEntity($table, $data);
        }
        $this->orderBy = "ASC";
        $this->where = "1";
        $this->select = "*";
        return $res;

    }
}
