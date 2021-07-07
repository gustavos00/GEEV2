<?php
require '../config.php';
require '../dao/providersDaoMS.php';
session_start();

$data = json_decode(file_get_contents("php://input"));
$provider = new providersDAOMS($pdo);

$id = $provider->getContactTypeIdByName($data->content);
$typeStatus = $provider->checkContactTypeStatus($id);

if($data->action == "create") {
    $provider->createContactType($data->content);
} else if($data->action == 'delete') {
    if(!$typeStatus) {
        $provider->deleteContactType($data->content);
    } else {
        $_SESSION['providerContactError'] = 'O tipo de contacto que deseja apagar está registrado num contacto, remova-o primeiro.';
    }
} else {
    $_SESSION['providerContactError'] = 'Ocorreu um erro, tente novamente.';
}

