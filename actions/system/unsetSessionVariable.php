<?php
session_start();

$data = json_decode(file_get_contents("php://input"));
$variable = $data->variable;

if(isset($_SESSION[$variable])) {
    unset($_SESSION[$variable]);
}