<?php
require '../model.php';

// Loop for all table config files;
foreach (glob("../../../app/entitiesConfig/*.json") as $filename) {
    
    // Get Table name
    $filename = str_replace("../../../app/entitiesConfig/", "",$filename);
    $filename = str_replace(".json", "",$filename);

    createTable($filename);
}


function createTable ($tableName){

    $model = Model::getInstance();
    
    // Get Table config file
    $jsonFile = file_get_contents("../../../app/entitiesConfig/".$tableName.".json");
    $table = json_decode($jsonFile,true);

    $query = "CREATE TABLE " .$table["tableName"]." ( ";

    // Get attributes name
    $attributeNames = array_keys($table["attributes"]);

    $primaryKey = "id";

    for ($i = 0; $i<count($table["attributes"]);$i++){

        // Convert Attributes Type
        if ($table["attributes"][$attributeNames[$i]]["type"] === "string")
            $table["attributes"][$attributeNames[$i]]["type"] = "varchar";

        // Check if the attribute is the  PRIMARY KEY 
        if (array_key_exists("id",$table["attributes"][$attributeNames[$i]])){
            $primaryKey = $attributeNames[$i];
            $query = $query . $attributeNames[$i]. " ".$table["attributes"][$attributeNames[$i]]["type"]."(".$table["attributes"][$attributeNames[$i]]["length"].") NOT NULL AUTO_INCREMENT , ";
        }else{
            $query = $query . $attributeNames[$i]. " ".$table["attributes"][$attributeNames[$i]]["type"]."(".$table["attributes"][$attributeNames[$i]]["length"].") , ";
        }
    }

    $query = $query . " PRIMARY KEY (".$primaryKey.") )";

    $model->executeQuery($query);
}


