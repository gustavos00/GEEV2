<?php
require_once '../config.php';
require_once '../dao/brandsDaoMS.php';
require_once '../dao/statesDaoMS.php';
require_once '../dao/categorysDaoMS.php';
session_start();

$data = json_decode(file_get_contents("php://input"));
$who = $data->who;

if((count((array)$data)) == 2) {
    switch($who) {
        case 'brand':
            $brand = new brandsDAOMS($pdo);
            $brandContent = $data->content;

            $newBrand = new brand();
            $newBrand->setBrandName($brandContent);
            $brand->createBrand($newBrand);
            break;

        case 'state':
            $state = new statesDAOMS($pdo);
            $stateContent = $data->content;

            $newState = new state();
            $newState->setState($stateContent);
            $state->createState($newState);
            break;
    };
} else if ((count((array)$data)) == 3) {
    $category = new categorysDAOMS($pdo);
    $newCategory = new category();
    $categoryName = $data->content;
    $categoryCode = $data->code;

    $newCategory->setCategoryName($categoryName);
    $newCategory->setCategoryCode($categoryCode);
    $category->createCategory($newCategory);
} else {
    
}