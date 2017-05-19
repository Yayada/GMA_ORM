<?php


$model = fopen("../../../src/Entities/".$argv[1].".php","w");
fclose($model);
$ctrl = fopen("../../../src/Controllers/".$argv[1]."Controller.php","w");
fclose($ctrl);
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

$values  = array(
    "tableName" => $argv[1],
    "attributes" =>  $attributes,
);

fwrite($config,json_encode($values));
fclose($config);
