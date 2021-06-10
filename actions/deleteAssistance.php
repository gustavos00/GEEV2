<?php
require_once '../config.php';
require_once '../dao/assistanceDaoMS.php';

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $assistances = new assistanceDAOMS($pdo);

    $newAssistance = new assistance();
    $newAssistance->setId($_GET['id']);

    $assistances->deleteAssistance($newAssistance);
}    

header('Location: ../index.php');
die();