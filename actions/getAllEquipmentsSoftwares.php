<?php
require '../config.php';
require '../dao/softwaresDaoMS.php';
session_start();

function extract_props($object) {
    $public = [];

    $reflection = new ReflectionClass(get_class($object));

    foreach ($reflection->getProperties() as $property) {
        $property->setAccessible(true);

        $value = $property->getValue($object);
        $name = $property->getName();

        if(is_array($value)) {
            $public[$name] = [];

            foreach ($value as $item) {
                if (is_object($item)) {
                    $itemArray = extract_props($item);
                    $public[$name][] = $itemArray;
                } else {
                    $public[$name][] = $item;
                }
            }
        } else if(is_object($value)) {
            $public[$name] = extract_props($value);
        } else $public[$name] = $value;
    }

    return $public;
}


$data = json_decode(file_get_contents("php://input"));
$softwares = new softwaresDAOMS($pdo);

$equipmentSoftwaresData = $softwares->getSpecificEquipmentSoftwares($data);
$equipmentsSoftwares = [];

foreach($equipmentSoftwaresData as $softwareData) {
    $equipmentsSoftwares[] = json_encode(extract_props($softwareData));
}

print_r(json_encode($equipmentsSoftwares));

