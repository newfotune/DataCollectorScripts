<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['email']) and isset($_POST['password'])){
        $db = new DbOperations();

        if($db->userLogin($_POST['email'], $_POST['password'])){
            $user = $db->getUserByEmail($_POST['email']);
            $response['error'] = false;
            $response['id'] = $user['ID'];
            $response['name'] = $user['first_name'];
            $response['email'] = $_POST['email'];
        } else {
            $response['error'] = true;
            $response['message'] = "Invalid username or password";
        }
    }else{
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }
}

echo json_encode($response);
