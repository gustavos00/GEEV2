<?php
require_once '../config.php';
require_once '../dao/providersDaoMS.php';
session_start();

$provider = new providersDaoMS($pdo);
$providerStatus = $provider->checkStatus($_GET['id']);

if(!$providerStatus) {
    if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        $contactsIds = [];
        $provider = new providersDAOMS($pdo);
        $providerData = $provider->getSpecific($_GET['id']);
        $providerContacts = $provider->getSpecificProviderContacts($_GET['id']);

        foreach($providerContacts as $contact) {
            array_push($contactsIds, $contact->getContactId());
        }

        $provider->unlinkProviderToContacts($_GET['id'], $contactsIds);
        $provider->deleteAllProviderContacts($contactsIds);
        $provider->deleteProvider($_GET['id']);


        $_SESSION['successMessage'] = "O fornecedor " . $_GET['id'] . " foi apagado com sucesso.";
    } else {
        $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
    }
} else {
    $_SESSION['indexErrorMessage'] = "O fornecedor está registrado em alguns lugares. Remova-o de lá antes de o tentar apagar.";
}

header('Location: ../index.php');
die();
