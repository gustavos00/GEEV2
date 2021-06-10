<?php

class state
{
    private $id;
    private $state;

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getState()
    {
        return $this->state;
    }

    function setState($s)
    {
        $this->state = $s;
    }
}

interface statesDAO
{
    public function getAll();
    public function getIdByName($n);

    public function createState(state $s);
}
