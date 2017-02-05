<?php
   require_once '../includes/DbOperation.php';
   $response  = array();

   if($_SERVER['REQUEST_METHOD']=='POST') { //make sure we have a post request
        if(isset($_POST["type"])) { //make sure the image_name field is present
            $type = $_POST["type"];
            $db = new DbOperations();

            $current_entity_id = -1;
            if (checkEntity()) { //check if i have all reuired entity fields
                $user_id = $_POST["user"];
                $geolat = $_POST["geolat"];
                $geolong = $_POST["geolong"];
                $type_id = $_POST["entity_type"];
                $image = $_POST["image"];
                $note = (isset($_POST["note"])) ? $_POST["note"] : NULL;

                $current_entity_id = uploadEntity($db, $type_id, $user_id, $geolat, $geolong, $image, $note);
                $db->addMedia($user_id, $current_entity_id, $image);
            }

            if(strcmp($type,"gas station") == 0) {
                if (isset($_POST["brand"])) {
                  $brand = $_POST["brand"];
                  $result = $db->addGasStation($current_entity_id, $brand);
                  if ($result == 1) {
                    $response['error'] = false;
                    $response['message'] = "successfully added Gas Station" ;
                    echo json_encode($response);
                  } else {
                    $response['error'] = true;
                    $response['message'] = "Operation Unexpectedly failed" ;
                    echo json_encode($response);
                  }
                } else {
                  requiredFieldsError($type);
                }
            } else if(strcmp($type,"resturant") == 0) {
              echo "type = resturant";
            } else if(strcmp($type,"stop sign") == 0) {
              echo "type = stop sign";
            } else if(strcmp($type,"traffic light") == 0) {
              echo "type = traffic light";
            } else if(strcmp($type,"traffic camera") == 0) {
              echo "type = traffic camera";
            } else if(strcmp($type,"road construction") == 0) {
              echo "type = road construction";
            }
      } else {
        $response['error'] = true;
        $response['message'] = "No Type specified.";
        echo json_encode($response);
      }
   } else {
     //not a post request
   }

   function uploadEntity($db, $type_id, $user_id, $geolat, $geolong, $image, $note) {
    $result = $db->createEntity($type_id, $user_id, $geolat, $geolong, $image, $note);
     if ($result > 0) {
       /*$response['error'] = false;
       $response['message'] = "Operation successfully executed" ;
       echo json_encode($response);*/
       return $result;
     } else {
       $response['error'] = true;
       $response['message'] = "Operation Unexpectedly failed" ;
       echo json_encode($response);
     }
   }

   function requiredFieldsError() {
     $response['error'] = true;
     $response['message'] = "Required fields are missing for " + $type;
      echo json_encode($response);
   }

   function checkEntity() {
     if(isset($_POST["geolat"]) and isset ($_POST["geolong"]) and isset($_POST["image"])) {
       return true;
     }
     return false;
   }
 ?>
