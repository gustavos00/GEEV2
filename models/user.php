<?php

class user
{
    private $id;
    private $ip;
    private $token;

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getIp()
    {
        return $this->ip;
    }

    function setIp($ip)
    {
        $this->ip = $ip;
    }

    function getToken()
    {
        return $this->token;
    }

    function setToken($t)
    {
        $this->token = $t;
    }
}

interface usersDAO
{
    public function insertData(user $u);
}
