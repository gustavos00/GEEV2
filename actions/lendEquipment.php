<?php
require_once '../dao/equipmentsDaoMS.php';
require_once '../config.php';

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

if(isset($_POST['responsibleUser'])) {
    if(!isset($_POST) ) { //Se não existir ou se não for válida

    }
}
var_dump($_POST);
