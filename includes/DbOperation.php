<?php
    class DbOperations {
        private $con;
        function __construct() {
            require_once dirname(__FILE__).'/DbConnect.php';
            $db = new DbConnect();

            $this->con = $db->connect();
        }

        /*CRUD -> C -> CREATE */
        public function createUser($firstName, $middleName, $lastname, $email, $password) {
            if ($this->isUserExist($email)) { return 3; }
            $password = md5($password);
            $stmt = $this->con->prepare("INSERT INTO USER (first_name, middle_name, last_name, email, _password)
                                        VALUES (?, ?, ?, ?, ?);");
            $stmt->bind_param("sssss",$firstName,$middleName,$lastname, $email, $password);
            if($stmt->execute()) {
              return 1;
            }
            else return 2;
        }

        public function createEntity($type_id, $user_id, $geolat, $geolong, $image, $note) {
              $query = "INSERT INTO ENTITY (entity_type_id, user_id, geolat, geolong, description)
                 VALUES ($type_id, $user_id, '$geolat', '$geolong', '$note');";

                 if (mysqli_query($this->con, $query)) {
                    $last_id = mysqli_insert_id($this->con);
                    return $last_id;
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($this->con);
                    return -1;
                }
        }

        public function addGasStation($id, $brand) {
            $query = "INSERT INTO GAS_STATION (entity_id, brand) VALUES ($id, '$brand')";
            //$stmt->bind_param("dsssss",$type_id,NOW(),$geolat,$geolong, $address, $note);
            $result = mysqli_query($this->con, $query)  or die ("Couldn't execute query: ".mysqli_error($this->con));

            if ($result) {
              return 1;
            } else {
              return 2;
            }
        }

        public function addMedia($user_id, $entity_id, $content) {
          $query = "INSERT INTO IMAGE (user_id, entity_id, content)
          VALUES ($user_id, $entity_id, '$content');";

          $result = mysqli_query($this->con, $query)
          or die ("Couldn't execute query: ".mysqli_error($this->con));

          if ($result) { return 1; }
          else { return 2; }
        }

        public function addResturant($brand) {
          $idd = 2;
          $query = "INSERT INTO RESTURANT (resturant_id, brand) VALUES ($idd, '$brand')";
          //$stmt->bind_param("dsssss",$type_id,NOW(),$geolat,$geolong, $address, $note);
          $result = mysqli_query($this->con, $query)  or die ("Couldn't execute query: ".mysqli_error($this->con));

          if ($result) {
            return 1;
          } else {
            return 2;

          }
        }

        public function userLogin($email, $pass){
            $password = md5($pass);
            $stmt = $this->con->prepare("SELECT user_id FROM user WHERE email = ? AND _password = ?");
            $stmt->bind_param("ss",$email,$password);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }

        public function getUserByEmail($email){
            $stmt = $this->con->prepare("SELECT user_id AS ID, first_name
                                        FROM user WHERE email = ?");

            $stmt->bind_param("s",$email);

            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        public function downloadImage($id) {
          $sql = "SELECT * FROM photos WHERE id = ?;";
          $stmt = mysqli_prepare($con,$sql);
          mysqli_stmt_bind_param($stmt,"s",$id);

          //mysqli_stmt_execute($stmt);
          $r = mysqli_query($con,$sql);
          $result = mysqli_fetch_array($r);
          //header('content-type: image/jpeg');
          //echo base64_decode($result['image']);

          mysqli_close($con);
          return $result['image']; //base64_decode($result['image']);
        }

        private function isUserExist($email){
          //could return false of prepare fails. Check for that later.
            $stmt = $this->con->prepare("SELECT user_id FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }

    }
