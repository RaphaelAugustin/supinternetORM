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


    public static function getConnection()
    {

        $yaml = new Parser();

        $parameters = $yaml->parse(file_get_contents('config/parameters.yml'));
        $parameters = $parameters['parameters'];

        $dsn = $parameters['driver'] . ':dbname='.$parameters['name'] . ';host=' . $parameters['host'];


    try {
        $PDO = new \PDO($dsn, $parameters["user"], $parameters["password"]);
        echo "connect success";
        return $PDO;

    } catch (PDOException $e) {
        echo 'Connexion échouée : ' . $e->getMessage();
    }


    }

     public static function errorLog($req)
     {
         $file = __DIR__."/../logs/error.log";
         if (!file_exists($file)) {
             file_put_contents($file, "Error log: \n");
         }
         file_put_contents($file, date("\[d/m/y H:i:s\]")." : ".$req->errorInfo()[2]." \n", FILE_APPEND);
     }

     public static function accessLog($sql)
     {
         $file = __DIR__."/../logs/access.log";
         if (!file_exists($file)) {
             file_put_contents($file, "LOGS ACCESS: \n");
         }
         file_put_contents($file, date("\[d/m/y H:i:s\]")." : ".$sql." \n", FILE_APPEND);

     }
}