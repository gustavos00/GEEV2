<?php
require_once '../config.php';
require_once '../dao/lentDaoMS.php';
session_start();

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

$lentDao = new lentDAOMS($pdo);
$isLent = $lentDao->checkIfIsLent($_POST['selectedEquipmentId']);
if($isLent) {
    if(checkFullDate($_POST['finalDate'])) {
        $lent = new lent();
        $lent->setFinalDate($_POST['finalDate']);
        $lent->setEquipmentId($_POST['selectedEquipmentId']);

        $lentDao->returnEquipment($lent);
    } else {
        echo 'a';
    }
} else {
    echo 'b';
}
