<?php

class category
{
    private $id;
    private $categoryName;
    private $categoryCode;

    function getId()
    {
        return $this->id;;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getCategoryName()
    {
        return $this->categoryName;
    }

    function setCategoryName($cn)
    {
        $this->categoryName = $cn;
    }

    function getCategoryCode()
    {
        return $this->categoryCode;
    }

    function setCategoryCode($cc)
    {
        $this->categoryCode = $cc;
    }
}

interface categorysDAO
{
    public function getAll();
    public function getIdByName($n);

    public function createCategory(category $c);
    public function getRetiredCategoryId();

    public function deleteCategory(category $c);
    public function checkIfExist($n);
}
