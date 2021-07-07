<?php 
require '../config.php';
require '../dao/providersDaoMS.php';
session_start();

$data = json_decode(file_get_contents("php://input"));
var_dump( $data);

$provider = new providersDAOMS($pdo);
$newProvider = new provider();

$newProvider->setId($data->id);
$newProvider->setName($data->name);
$newProvider->setObs($data->obs);
$provider->updateProvider($newProvider);

if ($data->status == "d") {
    $contactsIds = [];
    $contacts = $provider->getSpecificProviderContacts($data->id);

    foreach ($contacts as $contact) {
        array_push($contactsIds, $contact->getContactId());
    }

    $provider->unlinkProviderToContacts($data->id, $contactsIds);
    $provider->deleteAllProviderContacts($contactsIds);

    foreach ($data->contacts as $contact) {
        $contactsIds = [];
        $newProvider->setContactTypeId($provider->getContactTypeIdByName($contact->type));
        $newProvider->setContactType($contact->type);
        $newProvider->setContact($contact->contact);

        array_push($contactsIds, $provider->createContact($newProvider));

        $provider->linkProviderToContacts($data->id, $contactsIds);
    }
}   