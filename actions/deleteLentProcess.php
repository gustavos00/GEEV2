<?php
require_once '../config.php';
require_once '../dao/lentDaoMS.php';
session_start();

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $lent = new lentDAOMS($pdo);
    $lent->deleteLentProcess($_GET['id']);
} else {
    $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
}
header('Location: ../index.php');
die();
