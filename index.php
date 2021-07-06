<?php 
require_once './config.php';
require_once './dao/userDaoMS.php';
session_start();

function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function generateTokent() {
    return bin2hex(openssl_random_pseudo_bytes(16));
}

if(!isset($_SESSION['registred'])) {
    $token = generateTokent();

    while (strlen($token) > 64) { 
        $token = generateTokent();
    }

    $user = new usersDaoMS($pdo);
    $newUser = new user();
    $newUser->setIp(getUserIP());
    $newUser->setToken($token);

    $user->insertData($newUser);
    $_SESSION['registred'] = $token;
} 

header('Location: ./pages/home.php');
die();