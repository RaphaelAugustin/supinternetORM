<?php
/**
 * Created by PhpStorm.
 * User: TaF
 * Date: 11/12/2015
 * Time: 13:32
 */

use Model\Users;
use Touffik\Query;
require_once 'vendor/autoload.php';




$user = new Users;
$user->setName('Titi');
$user->setEmail('test@test.fr');
$user->setPassword('test');

$user->save();
////$user = new Users();
//var_dump($user);
$userQuery = new Query();
$userQuery->find('users');
