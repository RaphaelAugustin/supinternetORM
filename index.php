<?php
/**
 * Created by PhpStorm.
 * User: TaF
 * Date: 11/12/2015
 * Time: 13:32
 */

use Model\Users;
require_once 'vendor/autoload.php';


//$db = new Database();


$user = new Users;
$user->setName('touffik');
$user->setEmail('test');
$user->setPassword('test');
//$user->setId(1);

var_dump($user);
$user->save();
