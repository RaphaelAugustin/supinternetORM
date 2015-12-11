<?php
/**
 * Created by PhpStorm.
 * User: TaF
 * Date: 11/12/2015
 * Time: 14:22
 */


namespace Touffik;


use Symfony\Component\Yaml\Parser;

class Database
{

    protected $PDO;

    public function __construct()
    {

        $yaml = new Parser();

        $parameters = $yaml->parse(file_get_contents('config/parameters.yml'));
        $parameters = $parameters['parameters'];

        $dsn = $parameters['driver'] . ':dbname='.$parameters['name'] . ';host=' . $parameters['host'];


    try {
        $PDO = new \PDO($dsn, $parameters["user"], $parameters["password"]);
        echo "connect success";

    } catch (PDOException $e) {
        echo 'Connexion échouée : ' . $e->getMessage();
    }


    }
}