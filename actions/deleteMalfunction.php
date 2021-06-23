<?php
require_once '../config.php';
require_once '../dao/malfunctionsDaoMS.php';
session_start();

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $malfunctions = new malfunctionsDAOMS($pdo);

    $malfunctionModel = new malfunction();
    $malfunctionModel->setId($_GET['id']);

    $malfunctions->deleteMalfunction($malfunctionModel);
    $_SESSION['successMessage'] = "A assistência " . $_GET['id'] . " foi apagada com sucesso.";
} else {
    $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
}

header('Location: ../index.php');
die();