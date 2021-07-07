<?php

class provider
{
    private $id;
    private $name;
    private $obs;

    private $contactId;
    private $contact;
    private $contactType;  
    private $contactTypeId;  
    private $contacts;

    function getId()
    {
        return $this->id;;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($n)
    {
        $this->name = $n;
    }

    function getObs()
    {
        return $this->obs;
    }

    function setObs($o)
    {
        $this->obs = $o;
    }

    function getContactId() {
        return $this->contactId;
    } 

    function setContactId($c) {
        $this->contactId = $c;
    }

    function getContact() {
        return $this->contact;
    } 

    function setContact($c) {
        $this->contact = $c;
    }

    function setContactTypeId($cti) {
        $this->contactTypeId = $cti;
    }
    
    function getContactTypeId() {
        return $this->contactTypeId;
    } 

    function getContactType() {
        return $this->contactType;
    } 

    function setContactType($ct) {
        $this->contactType = $ct;
    }

    function getContacts() {
        return $this->contacts;
    } 

    function setContacts($c) {
        $this->contacts = $c;
    }
}

interface providersDao
{
    public function checkStatus($id);
    
    public function getAll();
    public function getSpecific($id);
    public function getSpecificProviderContacts($id);
    public function getIdByName($n);
    public function getAllContactsType();

    public function createContact(provider $p);
    public function createContactType($t);

    public function updateProvider(provider $p);

    public function deleteAllProviderContacts(provider $p);
    public function deleteProvider($id);
    
    public function linkProviderToContacts($providerId, $contactsId);
}
