<?php
require_once '../config.php';
require_once '../dao/brandsDaoMS.php';
require_once '../dao/statesDaoMS.php';
require_once '../dao/categorysDaoMS.php';
session_start();

function checkInput($i) {
    return (trim($i) != "");
}

$data = json_decode(file_get_contents("php://input"));
$who = $data->who;

if(checkInput($data->content)) {
    if((count((array)$data)) == 2) {
        switch($who) {
            case 'brand':
                $brand = new brandsDAOMS($pdo);
                $brandContent = $data->content;
    
                $newBrand = new brand();
                $newBrand->setBrandName($brandContent);
                $brand->createBrand($newBrand);
    
                $_SESSION['successMessage'] = "A marca " . $brandContent . " foi criada com sucesso.";
                break;    
            case 'state':
                $state = new statesDAOMS($pdo);
                $stateContent = $data->content;
    
                $newState = new state();
                $newState->setState($stateContent);
                $state->createState($newState);
    
                $_SESSION['successMessage'] = "O estado " . $stateContent . " foi criada com sucesso.";
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
    
        $_SESSION['successMessage'] = "A categoria " . $categoryName . " foi criada com sucesso.";
    }
} else {
}
