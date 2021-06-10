<?php

class provider
{
    private $id;
    private $name;
    private $obs;

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
}

interface providersDao
{
    public function getAll();
    public function getSpecific($id);
    
    public function getIdByName($n);
}
