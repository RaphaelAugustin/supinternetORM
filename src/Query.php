<?php
/**
 * Created by PhpStorm.
 * User: Taf
 * Date: 13/12/2015
 * Time: 12:27
 */

namespace Touffik;




class Query
{

    private $select = "*";
    private $orderBy = "id ASC";
    private $orderLimit = "1";
    private $condition = "true";

    public function orderBy($column)
    {
        $this->orderBy = $column;
    }

    public function where($condition)
    {
        $this->condition = $condition;
    }


    public function find($table)
    {
        $con = Database::getConnection();
        $sql = "SELECT $this->select FROM $table WHERE $this->condition ORDER BY $this->orderBy";
        $req = $con->prepare($sql);
        $req->execute();
        if ($req->errorInfo()[2] != null) {
            Database::errorLog($req);
        } else {
            Database::accessLog($sql);
        }
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

        $con = Database::getConnection();
        $sql = "SELECT $this->select FROM $table WHERE $this->condition  ORDER BY $this->orderBy LIMIT $this->orderLimit";
        $req = $con->prepare($sql);
        $req->execute();
        if ($req->errorInfo()[2] != null) {
            Database::errorLog($req);
        } else {
            Database::accessLog($sql);
        }
        $res = $req->fetch();
        if ($res != false) {
            $entity = $this->createEntity($table, $res);
        } else {
            $entity = false;
        }
        $this->orderBy = "ASC";
        $this->condition = "1";
        $this->select = "*";


        return $entity;

    }

    public function createEntity($name, $datas)
    {
        $name = 'Model\\' . ucfirst($name);
        $newEntity = new $name();

        foreach ($datas as $key => $value) {
            if (gettype($key) != 'integer') {
                $function = 'set' . $key;
                $newEntity->$function($value);
            }
        }
        return $newEntity;
    }

    public function count($table)
    {
        $con = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM $table WHERE $this->condition  ORDER BY $this->orderBy";
        $req = $con->prepare($sql);
        $req->execute();
        if ($req->errorInfo()[2] != null) {
            Database::errorLog($req);
        } else {
            Database::accessLog($sql);
        }
        return $req->fetch()[0];
    }
}