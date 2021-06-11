<?php
require_once '../config.php';
require_once '../dao/softwaresDaoMS.php';

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $software = new softwaresDAOMS($pdo);

    $newSoftware = new softwares();
    $newSoftware->setId($_GET['id']);

    $software->deleteSoftware($newSoftware);
    $_SESSION['successMessage'] = "O software " . $_GET['id'] . " foi apagado com sucesso.";
}

header('Location: ../index.php');
die();