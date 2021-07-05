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
    if((count((array)$data)) == 3) {

        switch($who) {
            case 'brand':
                $brand = new brandsDAOMS($pdo);
                $brandContent = $data->content;
    
                $newBrand = new brand();
                $newBrand->setBrandName($brandContent);
        
                if($data->action == "create") {        
                    $alreadyExist = $brand->checkIfExist($brandContent);   

                    if(!$alreadyExist) {
                
                        $brand->createBrand($newBrand);
                    } else {
                    $return = array(
                        'status' => 400,
                        'message'=> 'Já existe uma categoria com esse nome.'
                    );
                    http_response_code(400);
                    print_r(json_encode($return));
                }
                    
                } else {
                    $brand->deleteBrand($newBrand);
                }
                
    
                $_SESSION['successMessage'] = "A marca " . $brandContent . " foi criada com sucesso.";
                break;    
            case 'state':
                $state = new statesDAOMS($pdo);
                $stateContent = $data->content;
    
                $newState = new state();
                $newState->setState($stateContent);
                
                if($data->action == "create") {
                    $alreadyExist = $state->checkIfExist($stateContent);
                    if(!$alreadyExist) {
                        $state->createState($newState);
                    } else {
                        $return = array(
                            'status' => 400,
                            'message'=> 'Já existe um estado com esse nome.'
                        );
                        http_response_code(400);
                        print_r(json_encode($return));
                    }
                } else {
                    $state->deleteState($newState);
                }
    
                $_SESSION['successMessage'] = "O estado " . $stateContent . " foi criada com sucesso.";
                break;
        };
    } else if ((count((array)$data)) == 4) {
        $category = new categorysDAOMS($pdo);
        $newCategory = new category();

        $categoryName = $data->content;
        $categoryCode = $data->code;
        
        $newCategory->setCategoryName($categoryName);
        $newCategory->setCategoryCode($categoryCode);
        
        if($data->action == "create") {
            $alreadyExist = $category->checkIfExist($categoryName);
            if(!$alreadyExist) {
                $category->createCategory($newCategory);
            } else {
                $return = array(
                    'status' => 400,
                    'message'=> 'Já existe uma categoria com esse nome.'
                );
                http_response_code(400);
                print_r(json_encode($return));
            }
        } else {
            $category->deleteCategory($newCategory);
        }
    
        $_SESSION['successMessage'] = "A categoria " . $categoryName . " foi criada com sucesso.";
    } else {
    }
}
