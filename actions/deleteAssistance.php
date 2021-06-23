<?php
require_once '../config.php';
require_once '../dao/assistanceDaoMS.php';
session_start();

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $assistances = new assistanceDAOMS($pdo);

    $assistanceModel = new assistance();
    $assistanceModel->setId($_GET['id']);

    $assistances->deleteAssistance($assistanceModel);
    $_SESSION['successMessage'] = "A assistência " . $stateContent . " foi criada com sucesso.";
} else {
    $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
}

header('Location: ../index.php');
die();