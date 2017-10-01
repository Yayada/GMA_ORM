<?php
require '../model.php';
// Loop for all table config files;
foreach (glob("../../../app/entitiesConfig/*.json") as $filename) {
    // Get Table name
    $filename = str_replace("../../../app/entitiesConfig/", "",$filename);
    $filename = str_replace(".json", "",$filename);
    createTable($filename);
    createCtrl($filename);
    createVueAll($filename);
    createVueAdd($filename);
}

function createTable ($tableName){

    // Writing the class content
    $classContent = "<?php \n class ".$tableName."{ \n\n ";
    $constructorContent = 'public function __construct(){}';
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
        
        //$constructorInitContent = $constructorInitContent. '$this->'.$attributeNames[$i]." = $".$attributeNames[$i].";\n";        
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
    $classContent = $classContent . $constructorContent . $getSetContent;
    $classContent = $classContent . "};";

    fwrite($class,$classContent);
    fclose($class);    
    //generateRelation($tableName);
}

function generateRelation($tableName){

    $model = Model::getInstance();
    
    // Get Table config file
    $jsonFile = file_get_contents("../../../app/entitiesConfig/".$tableName.".json");
    $table = json_decode($jsonFile,true);

    $query = "CREATE TABLE " .$table["tableName"]." ( ";

    // Get attributes name
    $attributeNames = array_keys($table["attributes"]);
    $primaryKey = "id";
    for ($i = 0; $i<count($table["attributes"]);$i++){

        // Check if the attribute is the  PRIMARY KEY 
        if (array_key_exists("id",$table["attributes"][$attributeNames[$i]])){
            $primaryKey = $attributeNames[$i];
        }
    }
    if (array_key_exists("relations",$table)){
        $joinNames = array_keys($table["relations"]);
        for ($i = 0; $i<count($table["relations"]);$i++){

            $q = 'ALTER TABLE '.$table["tableName"].' ADD CONSTRAINT fk_'.$table["relations"]["manytoone"]["referenceID"].' FOREIGN KEY ('.$table["relations"]["manytoone"]["colonneName"].') REFERENCES '.$table["relations"]["manytoone"]["tableName"].'('.$table["relations"]["manytoone"]["referenceID"].')';
            $model->executeQuery($q);
        }
    }
}

function createCtrl($tableName){
    $ctrlContent = '<?php require "../../config/orm/vueAll.php";  require "../../config/orm/model.php";  require "../vues/'.$tableName.'/all'.$tableName.'svue.php";  require "../Entities/'.$tableName.'.php"; class '.$tableName.'Controller{ private $model;';
    $constructorContent = 'public function __construct(){ $this->model = Model::getInstance(); }';
    $functionGetAll = ' public function getAll'.$tableName.'s(){ $v = new All'.$tableName.'sVue(); $data = $this->model->findAll(new '.$tableName.'()); $v->all'.$tableName.'s($data); $v->afficher(); }';
    $functionGetOne = ' public function get'.$tableName.'($id){ $data = $this->model->findAll(new '.$tableName.'(), $id);}';
    $functionAdd = ' public function add'.$tableName.'($user){ $data = $this->model->save($user);}';
    $functionRemove = ' public function remove'.$tableName.'($id){ $data = $this->model->findAll(new '.$tableName.'(), $id);}';
    
    $ctrl = fopen("../../../src/Controllers/".$tableName.'Controller.php','w');

    // Creating Ctrl
    $ctrlContent = $ctrlContent . $constructorContent . $functionAdd . $functionGetAll . $functionGetOne . $functionRemove;
    $ctrlContent = $ctrlContent . "}";

    fwrite($ctrl,$ctrlContent);
    fclose($ctrl);   
}

function createVueAll($tableName){
    $vueContent = '<?php class All'.$tableName.'sVue extends VueAll{';
    $functionAll = 'public function all'.$tableName.'s($data){ $this->content .="<table style=\'width:50%; margin-top:20px\' align=\'center\' class=\'table table-striped\'><tr>';

    // Get Table config file
    $jsonFile = file_get_contents("../../../app/entitiesConfig/".$tableName.".json");
    $table = json_decode($jsonFile,true);

    // Get attributes name
    $attributeNames = array_keys($table["attributes"]);

    for ($i = 0; $i<count($table["attributes"]);$i++){
        $functionAll .= "<th>".$attributeNames[$i]."</th>";
    }

    $functionAll .= '</tr>"; foreach ($data as $d) { $this->content .= "<tr>';

    for ($i = 0; $i<count($table["attributes"]);$i++){
        $functionAll .= '<td>".$d["'.$attributeNames[$i].'"]."</td>';
    }
    $functionAll .= '</tr>"; } "</table>";}';

    $vue = fopen("../../../src/vues/".$tableName."/all".$tableName."svue.php","w");

    // Creating vue
    $vueContent = $vueContent . $functionAll;
    $vueContent = $vueContent . "}";

    fwrite($vue,$vueContent);
    fclose($vue);   
}

function createVueAdd($tableName){
    $vueContent = '<?php class Add'.$tableName.'Vue extends VueAll{';
    $functionAdd = 'public function add'.$tableName.'(){ $this->content .="<form style=\'width:50%; margin:20px\'>';

    // Get Table config file
    $jsonFile = file_get_contents("../../../app/entitiesConfig/".$tableName.".json");
    $table = json_decode($jsonFile,true);

    // Get attributes name
    $attributeNames = array_keys($table["attributes"]);

    for ($i = 0; $i<count($table["attributes"]);$i++){
        $functionAdd .= "<div class='form-group'>
                            <label for='".$attributeNames[$i]."'>".$attributeNames[$i]."</label>
                            <input type='text' class='form-control' placeholder='".$attributeNames[$i]."'>
                        </div>";
    }

    $functionAdd .= "<button type='submit' class='btn btn-default'>Annuler</button>
                    <button type='submit' class='btn btn-primary'>Ajouter</button>
                </form>;";

    $vue = fopen("../../../src/vues/".$tableName."/add".$tableName."vue.php","w");

    // Creating vue
    $vueContent = $vueContent . $functionAdd;
    $vueContent = $vueContent . "}";

    fwrite($vue,$vueContent);
    fclose($vue);   
}



