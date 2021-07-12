<?php 
require '../config.php';
require '../dao/providersDaoMS.php';
session_start();

$provider = new providersDAOMS($pdo);
$data = json_decode(file_get_contents("php://input"));

$contactsIds = [];

$newProvider = new provider();
$newProvider->setName($data->name);
$newProvider->setObs($data->obs);

$providerId = $provider->createProvider($newProvider);

foreach($data->contacts as $contact) {
    $contactTypeId = $provider->getContactTypeIdByName($contact->type);

    $newProvider->setContact($contact->contact);
    $newProvider->setContactTypeId($contactTypeId);
    
    $contactId = $provider->createContact($newProvider);

    array_push($contactsIds, $contactId);
}

$provider->linkProviderToContacts($providerId, $contactsIds);
$_SESSION['successMessage'] = "O fornecedor " . $data->name . " foi criado com sucesso.";