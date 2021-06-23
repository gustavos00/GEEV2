<?php
require_once '../config.php';
require_once '../dao/softwaresDaoMS.php';

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $software = new softwaresDAOMS($pdo);

    $softwareModel = new softwares();
    $softwareModel->setId($_GET['id']);

    $software->deleteSoftware($softwareModel);
    $_SESSION['successMessage'] = "O software " . $_GET['id'] . " foi apagado com sucesso.";
} else {
    $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
}

header('Location: ../index.php');
die();