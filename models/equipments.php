<?php

class equipments
{
    private $id;
    private $internalCode;
    private $serieNumber;
    private $features;
    private $obs;
    private $acquisitionDate;
    private $patrimonialCode;
    private $ipAdress;
    private $activeEquipment;
    private $lanPort;
    private $user;
    private $userDate;
    private $location;
    
    private $categoryId; //
    private $stateId; //
    private $brandId; //
    private $providerId; //

    private $model;

    private $initialDate;
    private $finalDate;

    private $categoryName; //
    private $stateName; //
    private $brandName; //
    private $providerName; //

    function getId()
    {
        return $this->id;;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getInternalCode()
    {
        return $this->internalCode;
    }

    function setInternalCode($ic)
    {
        $this->internalCode = $ic;
    }

    function getSerieNumber()
    {
        return $this->serieNumber;
    }

    function setSerieNumber($sn)
    {
        $this->serieNumber = $sn;
    }

    function getFeatures()
    {
        return $this->features;
    }

    function setFeatures($f)
    {
        $this->features = $f;
    }

    function getInitialDate()
    {
        return $this->initialDate;
    }

    function setInitialDate($f)
    {
        $this->initialDate = $f;
    }

    function getFinalDate()
    {
        return $this->finalDate;
    }

    function setFinalDate($f)
    {
        $this->finalDate = $f;
    }

    function getObs()
    {
        return $this->obs;
    }

    function setObs($o)
    {
        $this->obs = $o;
    }

    function getAcquisitionDate()
    {
        return $this->acquisitionDate;
    }

    function setAcquisitionDate($ad)
    {
        $this->acquisitionDate = $ad;
    }

    function getPatrimonialCode()
    {
        return $this->patrimonialCode;
    }

    function setPatrimonialCode($pc)
    {
        $this->patrimonialCode = $pc;
    }

    function getIpAdress()
    {
        return $this->ipAdress;
    }

    function setIpAdress($ip)
    {
        $this->ipAdress = $ip;
    }

    function getLanPort()
    {
        return $this->lanPort;
    }

    function setLanPort($lp)
    {
        $this->lanPort = $lp;
    }

    function getCategoryId()
    {
        return $this->categoryId;
    }

    function setCategoryId($c)
    {
        $this->categoryId = $c;
    }

    function getStateId()
    {
        return $this->stateId;
    }

    function setStateId($s)
    {
        $this->stateId = $s;
    }

    function getBrandId()
    {
        return $this->brandId;
    }

    function setBrandId($b)
    {
        $this->brandId = $b;
    }

    function getProviderId()
    {
        return $this->providerId;
    }

    function setProviderId($sp)
    {
        $this->providerId = $sp;
    }

    function getCategoryName()
    {
        return $this->categoryName;
    }

    function setCategoryName($c)
    {
        $this->categoryName = $c;
    }

    function getStateName()
    {

        return $this->stateName;
    }

    function setStateName($s)
    {
        $this->stateName = $s;
    }

    function getBrandName()
    {
        return $this->brandName;
    }

    function setBrandName($b)
    {
        $this->brandName = $b;
    }

    function getProviderName()
    {
        return $this->providerName;
    }

    function setProviderName($sp)
    {
        $this->providerName = $sp;
    }

    function getModel()
    {
        return $this->model;
    }

    function setModel($m)
    {
        $this->model = $m;
    }

    function getUser()
    {
        return $this->user;
    }

    function setUser($u)
    {
        $this->user = $u;
    }

    function getUserDate()
    {
        return $this->userDate;
    }

    function setUserDate($ud)
    {
        $this->userDate = $ud;
    }

    function getLocation()
    {
        return $this->location;
    }

    function setLocation($l)
    {
        $this->location = $l;
    }

    function getActiveEquipment()
    {
        return $this->activeEquipment;
    }

    function setActiveEquipment($ac)
    {
        $this->activeEquipment = $ac;
    }
}

interface equipmentsDAO
{
    public function getAll();

    public function createEquipment(equipments $e);

    public function updateEquipment(equipments $e);

    public function deleteEquipment(equipments $e);

    public function getSpecificById($id);
    public function setEquipmentAsRetired(equipments $e, $categoryId);
    public function getIdByInternalCode($ic);

    public function linkSoftwares($softwareId, $equipmentId);
}
