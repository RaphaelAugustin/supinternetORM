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
$code .= 'use Touffik\Database;' . "\n\n" ;
$code .=  "class ".ucfirst($className)."\n{\n";

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

$code .= <<<'EOT'
    public function getTableNameLower() {

        if ($pos = strrpos(get_class($this), '\\'))
            return strtolower(substr(get_class($this), $pos + 1));

    }
    public function save(PDO $con = null)
    {
        $properties = get_object_vars($this);
        $table = $this->getTableNameLower();
        $id = $this->getId();

        if ($con === null) {
            $con = Database::getConnection();
        }
        // used for do an Insert
        if ($id === null) {
            try {
                $sql = "INSERT INTO $table(id) VALUES (DEFAULT)";
                $req = $con->prepare($sql);
                $req->execute();
                if ($req->errorInfo()[2] != null) {
                     Database::errorLog($req);
                } else {
                     Database::accessLog($sql);
                }
                $id = $con->lastInsertId();
                $this->setId($id);
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
        try{
        $sql = "UPDATE $table SET $set WHERE id = '$id'";
            $req = $con->prepare($sql);
            $req->execute();
              if ($req->errorInfo()[2] != null) {
                     Database::errorLog($req);
                } else {
                     Database::accessLog($sql);
                }
        }
        catch (\Exception $e){
            echo 'error' . $e;
        }
    }

    public function delete(PDO $con = null)
    {

        if ($con === null) {
            $con = Database::getConnection();
        }

        $tablename = $this->getTableNameLower();
        $id = $this->getId();
        $sql = "DELETE FROM $tablename WHERE id = $id";
        $req = $con->prepare($sql);
        $req->execute();
                if ($req->errorInfo()[2] != null) {
                     Database::errorLog($req);
                } else {
                     Database::accessLog($sql);
                }
    }
EOT;
$code .= "}\n";

if (!is_dir('app/Model/')) {
    // dir doesn't exist, make it
    if (!is_dir('app/')){
        mkdir('app/');
    }
    mkdir('app/Model/');
}
file_put_contents("app/Model/". ucfirst($className).".php", $code);
