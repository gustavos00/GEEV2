<?php

class malfunction
{
    private $id;
    private $date;
    private $description;

    private $assistanceId;
    private $status = "Por resolver";

    private $providerName;
    private $providerId;

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getDate()
    {
        return $this->date;
    }

    function setDate($d)
    {
        $this->date = $d;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setDescription($d)
    {
        $this->description = $d;
    }

    function getAssistanceId()
    {
        return $this->assistanceId;
    }

    function setAssistanceId($aid)
    {
        $this->assistanceId = $aid;

        if(!is_null($this->assistanceId)) {
            $this->status = "Resolvido";
        }
    }

    function getStatus()
    {
        return $this->status;
    }

    function getProviderName()
    {
        return $this->providerName;
    }

    function setProviderName($pn)
    {
        $this->providerName = $pn;
    }

    function getProviderId()
    {
        return $this->providerId;
    }

    function setProviderId($pid)
    {
        $this->providerId = $pid;
    }
}

interface malfunctionsDAO
{
    public function getAll();
    public function getSpecific($id);

    public function createMalfunction(malfunction $mf);
    public function deleteMalfunction(malfunction $mf);
}
