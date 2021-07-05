<?php

class assistance
{
    private $id;
    private $initialDate;
    private $finalDate;
    private $duration;

    private $description;
    private $technical;

    private $goals;
    private $frontOffice;

    private $typeId;
    private $typeName;
    private $equipmentId;

    function getId()
    {
        return $this->id;;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setInitialDate($id)
    {
        $this->initialDate = $id;
    }

    function getInitialDate()
    {
        return $this->initialDate;
    }

    function setFinalDate($fd)
    {
        $this->finalDate = $fd;
    }

    function getFinalDate()
    {
        return $this->finalDate;
    }

    function setDuration($d)
    {
        $this->duration = $d;
    }

    function getDuration()
    {
        return $this->duration;
    }

    function setDescription($d)
    {
        $this->description = $d;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setTechnical($t)
    {
        $this->technical = $t;
    }

    function getTechnical()
    {
        return $this->technical;
    }

    function setGoals($g)
    {
        $this->goals = $g;
    }

    function getGoals()
    {
        return $this->goals;
    }

    function setFrontOffice($ff)
    {
        $this->frontOffice = $ff;
    }

    function getFrontOffice()
    {
        return $this->frontOffice;
    }

    function setTypeId($tId)
    {
        $this->typeId = $tId;
    }

    function getTypeId()
    {
        return $this->typeId;
    }

    function setTypeName($tn)
    {
        $this->typeName = $tn;
    }

    function getTypeName()
    {
        return $this->typeName;
    }

    function setEquipmentId($eId)
    {
        $this->equipmentId = $eId;
    }

    function getEquipmentId()
    {
        return $this->equipmentId;
    }
}

interface assistanceDAO
{
    public function createAssistance(assistance $a);
    
    public function getAll();

    public function deleteAssistance(assistance $a);

    public function getAssistanceTypeIdBYName($name);
    public function getAllAssistanceTypes();
}