<?php

class provider
{
    private $id;
    private $name;
    private $obs;

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

    function getContactTypeId() {
        return $this->contactTypeId;
    } 
 
    function setContactTypeId($cti) {
        $this->contactTypeId = $cti;
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
    public function getAll();
    public function getSpecific($id);
    public function getSpecificProviderContacts($id);
    public function getIdByName($n);
    public function getAllContactsType();

    public function createContact(provider $p);
    public function createContactType($t);
    public function linkProviderToContacts($providerId, $contactsId);

    public function updateProvider(provider $p);
}
