<?php
require '../config.php';
require '../dao/providersDaoMS.php';
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

$providerArrayContactsData = [];
$data = json_decode(file_get_contents("php://input"));
$providers = new providersDAOMS($pdo);

$providerContactsData = $providers->getSpecificProviderContacts($data);

foreach($providerContactsData as $contactData) {
    array_push($providerArrayContactsData, extract_props($contactData));
}

echo(json_encode($providerArrayContactsData));

