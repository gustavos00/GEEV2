<?php

class lent
{
    private $id;
    private $user;
    private $initialDate;
    private $finalDate;
    private $contact;
    private $obs;

    private $equipmentInternalCode;
    private $equipmentId;

    function getId()
    {
        return $this->id;;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getUser()
    {
        return $this->user;
    }

    function setUser($u)
    {
        $this->user = $u;
    }

    function getInitialDate()
    {
        return $this->initialDate;
    }

    function setInitialDate($id)
    {
        $this->initialDate = $id;
    }

    function getFinalDate()
    {
        return $this->finalDate;
    }

    function setFinalDate($fd)
    {
        $this->finalDate = $fd;
    }

    function getContact()
    {
        return $this->contact;
    }

    function setContact($cd)
    {
        $this->contact = $cd;
    }

    function getObs()
    {
        return $this->obs;
    }

    function setObs($o)
    {
        $this->obs = $o;
    }
    
    function getEquipmentId()
    {
        return $this->equipmentId;
    }

    function setEquipmentId($eid)
    {
        $this->equipmentId = $eid;
    }

    function getEquipmentInternalCode()
    {
        return $this->equipmentInternalCode;
    }

    function setEquipmentInternalCode($eic)
    {
        $this->equipmentInternalCode = $eic;
    }
}

interface lentDAO
{
    public function checkIfIslent($id);
    public function returnEquipment(lent $l);
    public function getAll();
}
