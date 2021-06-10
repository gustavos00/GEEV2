<?php

class brand
{
    private $id;
    private $brandName;

    function getId()
    {
        return $this->id;;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getBrandName()
    {
        return $this->brandName;
    }

    function setBrandName($bn)
    {
        $this->brandName = $bn;
    }
}

interface brandsDAO
{
    public function getAll();
    public function getIdByName($n);
    public function createBrand(brand $b);
}
