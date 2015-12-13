<?php

namespace Model;

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

}
