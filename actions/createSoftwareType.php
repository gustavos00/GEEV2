<?php
require '../config.php';
require '../dao/softwaresDaoMS.php';
session_start();

$data = json_decode(file_get_contents("php://input"));

$software = new softwaresDAOMS($pdo);

if($data->action == "create") {
    $softwareTypeStatus = $software->checkIfSoftwareTypeExists($data->content);

    if(!$softwareTypeStatus) {
        $software->createSoftwareType($data->content);
    } else {
        http_response_code(400);
    }
} else if($data->action == 'delete') {
    $software->deleteSoftwareType($data->content);
} else {
    echo 'error';
}
