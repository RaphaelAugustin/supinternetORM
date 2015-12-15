<?php

namespace Model;

use Touffik\Database;
use Touffik\Entity;

class Users extends Entity 
{

    protected $id;
    protected $name;
    protected $password;
    protected $email;
    /**
    *    $id  int(11) 
    */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
    *    $name  varchar(255) 
    */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
    *    $password  varchar(255) 
    */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
    *    $email  varchar(255) 
    */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function getTableNameLower() {

        if ($pos = strrpos(get_class($this), '\\'))
            return strtolower(substr(get_class($this), $pos + 1));

    }
    public function save(PDO $con = null)
    {
echo 'test';
        $properties = $this->getProperties();
        $table = $this->getTableNameLower();
        $id = $this->getId();

        if ($con === null) {
            $con = Database::getConnection();

        }
        // used for do an Insert
        if ($id === null) {
            try {
                $req = $con->prepare("INSERT INTO $table(id) VALUES (DEFAULT);");
                $req->execute();
            }
            catch (\Exception $e){
                echo 'error' . $e;
            }

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
        echo 'test4';
        try{
        $req = $con->prepare("UPDATE $table SET $set WHERE id = '$id';");
        $req->execute();
        }
        catch (\Exception $e){
            echo 'error' . $e;
        }
    }
}
