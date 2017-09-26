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

    // Writing the class content
    $classContent = "<?php \n class ".$tableName."{ \n\n ";
    $constructorContent = 'public function __construct(';
    $constructorInitContent="";
    $getSetContent="";    
    $class = fopen("../../../src/Entities/".$tableName.'.php','w');

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

        // Create Attribute
        $classContent = $classContent . " private $".$attributeNames[$i].";\n";
        // Create Constructor
            // check if we put comma after the attribute or not
        if(count($table["attributes"]) - $i == 1){
            $constructorContent = $constructorContent . '$'.$attributeNames[$i].'';        
        }
        else{
            $constructorContent = $constructorContent . '$'.$attributeNames[$i].',';                    
        }
        $constructorInitContent = $constructorInitContent. '$this->'.$attributeNames[$i]." = $".$attributeNames[$i].";\n";        
        // Create Getter and setter        
        $upperAttr = ucfirst($attributeNames[$i]);
        $getSetContent = $getSetContent.
        ' public function set'.$upperAttr.'($'.$attributeNames[$i]."){
             \n".' $this->'.$attributeNames[$i].' = $'.$attributeNames[$i].";\n 
            }\n
         public function get".$upperAttr."(){
            \n".' return $this->'.$attributeNames[$i].";\n 
            }
        ";
    }

    // Creating table
    $query = $query . " PRIMARY KEY (".$primaryKey.") )";
    $model->executeQuery($query);

    // Creating Class
    $constructorContent = $constructorContent ."){ \n". $constructorInitContent . "}";
    $classContent = $classContent . $constructorContent . $getSetContent;
    $classContent = $classContent . "};";

    fwrite($class,$classContent);
    fclose($class);    
}



