<?php
//require "../../src/Entities/emp.php";
class Model {

    private $db;
    private static $instance;

    public function __construct(){
        $content = file_get_contents(__DIR__."/../../app/config/config.json");
        $jsonConfig = json_decode($content,true);
        $dbString = $jsonConfig["DB_CONFIG"]["SGBD"].':host='.$jsonConfig["DB_CONFIG"]["HOST"].';dbname='.$jsonConfig["DB_CONFIG"]["BASE"];
        $this->db = new PDO($dbString,$jsonConfig["DB_CONFIG"]["USER"],$jsonConfig["DB_CONFIG"]["PASS"]);
    }

    public static function getInstance (){
        if(empty(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

    public function executeQuery($query){
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }

    public function findOne($model,$var){
        $table = get_class($model);
        $a = $this->findId($model);
        $query = 'select * from '.$table.' where '.$a.' = ?';
        $stmt = $this->db->prepare($query);
        $stmt->execute(array($var));
        $ob = $stmt->fetch();
        return $ob;
    }

    public function findAll($model){
        $table = get_class($model);
        $query = 'select * from '.$table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $ob = $stmt->fetchAll();
        return $ob;
    }

    public function remove($model,$var){
        $table = get_class($model);
        $a = $this->findId($model);
        $query = 'delete from '.$table.' where '.$a.' = ?';
        $stmt = $this->db->prepare($query);
        $stmt->execute(array($var));
        return $stmt->fetch();
    }

    public function save($model){

        $table = get_class($model);
        $array = $this->descTable($table);
        $query = 'insert into '.$table.' values (';
        for ($i=0; $i < sizeof($array)-1; $i++) {
            $query .='?, ';
        }
        $query .= '?);';
        echo $query;
        $stmt = $this->db->prepare($query);
        $arra =  $this->convertObjectToArray($model);
        print_r($arra);
        $stmt->execute($arra);
    }

    public function convertObjectToArray($model){
        $arra =  (array) $model;
        $a = array();
        $j = 0;
        foreach ($arra as $key => $value) {
            $a[$j] = $value;
            $j++;
        }
        return $a;
    }

    private function findId($model){
        $table = get_class($model);
        $a;$k;
        $jsondata = file_get_contents("../../app/entitiesConfig/".$table.".json");
        $array = json_decode($jsondata,true);
        $attr = $array['attributes'];

        foreach ($attr as $key => $value) {
            if ($attr[$key]['id']) {
                $a=$key;
                $k=$attr[$key]['id'];
                break;
            }
        }
        return $a;
    }

    public function descTable($table){
        $ch = "desc ".$table;
        $query=$this->db->prepare($ch);
        $query->execute();
        return $query->fetchAll();
    } 
    
}
/*$m=new emp(1,"med",'');
$k = new Model();
$k->save($m);
//$k->convertObjectToArray($m);*/
?>