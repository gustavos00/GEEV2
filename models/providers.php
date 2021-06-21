<?php

class provider
{
    private $id;
    private $name;
    private $obs;

    private $contact;
    private $contactType;  
    private $contactTypeId;  

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

    function getContact() {
        return $this->contact;
    } 

    
    function setContact($c) {
        $this->contact = $c;
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
}

interface providersDao
{
    public function getAll();
    public function getSpecific($id);    
    public function getIdByName($n);

    public function createContact(provider $p);

    public function getAllContactsType();
}
