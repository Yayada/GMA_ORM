<?php

$model = fopen("../../../src/Entities/".$argv[1].".php","w");
fclose($model);

$ctrl = fopen("../../../src/Controllers/".$argv[1]."Controller.php","w");
fclose($ctrl);

mkdir("../../../src/vues/".$argv[1], 0777, true);
$vue = fopen("../../../src/vues/".$argv[1]."/all".$argv[1]."svue.php","w");
fclose($vue);

$vue = fopen("../../../src/vues/".$argv[1]."/add".$argv[1]."vue.php","w");
fclose($vue);

$config = fopen("../../../app/entitiesConfig/".$argv[1].".json","w");

$attributes = array(

     "id" => array(
            "type" =>  "int",
            "length" => "8",
            "id" =>true,
    ),
    "exampleAttribute" => array(
        "type" => "string | int ...",
        "length" => "255",
    )
);

$join = array(
    "manytoone" => array(
            "tableName" =>  "user",
            "colonneName" => "idu",
            "referenceID" => "id",
    )
);

$values  = array(
    "tableName" => $argv[1],
    "attributes" =>  $attributes,
    "relations" => $join,
);

fwrite($config,json_encode($values));
fclose($config);
