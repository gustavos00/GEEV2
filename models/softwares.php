<?php

class softwares
{
    private $id;
    private $key;
    private $version;
    private $initialDate;
    private $finalDate;
    private $typeId;
    private $typeName;
    private $providerId;
    private $providerName;


    function getId()
    {
        return $this->id;;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getKey()
    {
        return $this->key;
    }

    function setKey($k)
    {
        $this->key = $k;
    }

    function getVersion()
    {
        return $this->version;
    }

    function setVersion($o)
    {
        $this->version = $o;
    }

    function getInitialDate()
    {
        return $this->initialDate;
    }

    function setInitialDate($d)
    {
        $this->initialDate = $d;
    }

    function getFinalDate()
    {
        return $this->finalDate;
    }

    function setFinalDate($d)
    {
        $this->finalDate = $d;
    }

    function getTypeName()
    {
        return $this->typeName;
    }

    function setTypeName($t)
    {
        $this->typeName = $t;
    }

    function getTypeId()
    {
        return $this->typeId;
    }

    function setTypeId($t)
    {
        $this->typeId = $t;
    }

    function getProviderId()
    {
        return $this->providerId;
    }

    function setProviderId($p)
    {
        $this->providerId = $p;
    }

    function getProviderName()
    {
        return $this->providerName;
    }

    function setProviderName($p)
    {
        $this->providerName = $p;
    }
}

interface sotfwaresDao
{
    public function getAllSoftwares();

    public function insertSoftware(softwares $s);

    public function updateSoftware(softwares $s);

    public function deleteSoftware(softwares $s);

    public function getAllSoftwareTypes();
    public function getSpecificSoftwareById($id);
    public function checkIfSoftwareTypeExists($n);


}
