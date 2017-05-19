<?php
class Model {

    private $db;
    private static $instance;

    private function __construct(){
        $content = file_get_contents("../../app/config/config.json");
        $jsonConfig = json_decode($content,true);
        $dbString = $jsonConfig["DB_CONFIG"]["SGBD"].':host='.$jsonConfig["DB_CONFIG"]["HOST"].';dbname='.$jsonConfig["DB_CONFIG"]["BASE"];
        $this->db = new PDO($dbString,$jsonConfig["DB_CONFIG"]["USER"],$jsonConfig["DB_CONFIG"]["PASS"]);
    }

    public static function getInstance (){
        if(empty(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

}