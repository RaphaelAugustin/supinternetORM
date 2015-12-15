<?php
/**
 * Created by PhpStorm.
 * User: TaF
 * Date: 11/12/2015
 * Time: 20:22
 */

function connect ($host, $databasename, $user, $password, $table) {

    $connexion = new PDO('mysql:host='.$host.';dbname='.$databasename, $user, $password);
    $req = $connexion->prepare('SHOW columns FROM '.$table.'');
    $req->execute();

    return $req->fetchAll();
}

$fields = connect($argv[1], $argv[2], $argv[3], $argv[4], $argv[5]);
var_dump($fields);

function do_tabs($tabs)
{
    $ret = '';
    for ($i = 0 ; $i < $tabs ; $i++)
        $ret .= '  ';

    return $ret;
}

$className = $argv[5];
//MAGIC

$tabs = 2;
$code = "<?php\n\nnamespace Model;\n\n";
$code .= 'use Touffik\Entity;' . "\n\n" ;
$code .=  "class ".ucfirst($className)." extends Entity \n{\n";

$code .= "\n";
foreach ($fields as $field)
{
    $code .= do_tabs($tabs) . 'protected $'.$field["Field"].";\n";
}

$code .= "\n";

foreach ($fields as $field)
{
    $code .= do_tabs($tabs) . "/**\n";
    $code .= do_tabs($tabs) . "*    $". $field['Field'] . "  " . $field['Type'] ." \n";
    $code .= do_tabs($tabs) . "*/\n";
    $code .= do_tabs($tabs) . 'public function get'.ucfirst($field['Field'])."()\n";
    $code .= do_tabs($tabs) . "{\n";
    $code .= do_tabs($tabs+2) . 'return $this->'.$field['Field'].";\n";
    $code .= do_tabs($tabs) . "}\n\n";
    $code .= do_tabs($tabs) . 'public function set'.ucfirst($field['Field']).'($'.$field['Field'].")\n";
    $code .= do_tabs($tabs) . "{\n";
    $code .= do_tabs($tabs+2) . '$this->'.$field['Field'].' = $'.$field['Field'].";\n";
    $code .= do_tabs($tabs) . "}\n\n";
}
$code .= "}\n";
var_dump($code);
if (!is_dir('app/Model/')) {
    // dir doesn't exist, make it
    if (!is_dir('app/')){
        mkdir('app/');
    }
    mkdir('app/Model/');
}
file_put_contents("app/Model/". ucfirst($className).".php", $code);
