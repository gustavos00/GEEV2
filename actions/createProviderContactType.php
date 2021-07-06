<?php
require '../config.php';
require '../dao/providersDaoMS.php';
session_start();

$data = json_decode(file_get_contents("php://input"));
$provider = new providersDAOMS($pdo);

if($data->action == "create") {
    $provider->createContactType($data->content);
} else if($data->action == 'delete') {
    //Check if exist some contact with this type and return error message
    $provider->deleteContactType($data->content);
} else {
    echo 'error';
}

