<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {
  if(isset($_POST['firstname']) and isset($_POST['lastname'])
    and isset($_POST['email']) and isset($_POST['password']))  {
                //operate the data further
                $db = new DbOperations();

                $firstName = $_POST["firstname"];
                $middleName = (isset($_POST["middlename"])) ? $_POST["middlename"] : "N/A";
                $lastname = $_POST["lastname"];
                $email = $_POST["email"];
                $password = $_POST["password"];

                $result = $db->createUser($firstName, $middleName, $lastname, $email, $password);
                if ($result == 2) { onFailure(); }
                else if ($result == 3) { duplicateUser(); }
                else { onSuccess($email, $db); }

              }
              else{
                  $response['error'] = true;
                  $response['message'] = "Required fields are missing";
                  echo json_encode($response);
              }
    } else{
    $response['error'] = true;
    $response['message'] = "Invalid Request";
    echo json_encode($response);
}

function duplicateUser() {
  $response['error'] = true;
  $response['message'] = "User email already exists.";
  echo json_encode($response);
}

function onSuccess($email, $db) {
  $user = $db->getUserByEmail($email);
  $response['error'] = false;
  $response['id'] = $user['ID'];
  $response['name'] = $user['first_name'];
  $response['email'] = $email;
  echo json_encode($response);
}

function onFailure() {
  $response['error'] = true;
  $response['message'] = "An enexpected error occured ";
  echo json_encode($response);
}
