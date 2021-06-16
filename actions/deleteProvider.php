<?php
require_once '../config.php';
require_once '../dao/providersDaoMS.php';
session_start();

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $provider = new providersDAOMS($pdo);

    $newProvider = new provider;
    $newProvider->setId($_GET['id']);

    $provider->deleteProvider($newProvider);
    $_SESSION['successMessage'] = "O fonecedor " . $_GET['id'] . " foi apagado com sucesso.";
}    

header('Location: ../index.php');
die();
