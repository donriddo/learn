<<?php
/**
 * User Model
 */

 require 'db.inc.php';
 try {
     $dsn = "mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB;
     $db = new PDO($dsn, MYSQL_USER, MYSQL_PASSWORD);
 } catch (PDOException $e) {
     echo json_encode($e);
 }
 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $query = 'CREATE TABLE IF NOT EXISTS learn_user (
     user_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
     last_name VARCHAR(40) NOT NULL DEFAULT "",
     first_name VARCHAR(40) NOT NULL DEFAULT "",
     mobile_number VARCHAR(20) NOT NULL DEFAULT "+2341234567890",
     email VARCHAR(40) NOT NULL,
     password VARCHAR(255) NOT NULL,
     address VARCHAR(80),
     UNIQUE (email),
     PRIMARY KEY (user_id)
 )';
 $stmt = $db->prepare($query);
 $stmt->execute();


class User {
    private $attributes = array(
        'email' => array(
            'required' => true,
            'unique' => true,
        ),
        'password' => array(
            'required' => true,
        ),
        'last_name' => array(),
        'first_name' => array(),
        'mobile_number' => array(),
        'address' => array()
    );
    private $email;
    private $password;
    private $last_name;
    private $first_name;
    private $mobile_number;
    private $address;
    private $currentID;

    function __construct($body=NULL, $id=NULL) {
        if (!is_null($id)) $this->currentID = $id;
        if ($body && is_array($body)) {
            $validated = $this->validate_attributes($body);
            if ($validated === true) {
                $this->email = $body['email'];
                $this->password = password_hash($body['password'], PASSWORD_DEFAULT);
                $this->last_name = $body['last_name'] ?? "";
                $this->first_name = $body['first_name'] ?? "";
                $this->mobile_number = $body['mobile_number'] ?? "";
                $this->address = $body['address'] ?? "";
            } else {
                echo json_encode(array("status" => 400, "message" => "Validation Error has occurred", "errors" => $validated));
                exit(1);
            }
        }
    }

    private function validate_attributes($attributes) {
        $errors = array();
        foreach ($this->attributes as $key => $value) {
            $current = array();
            foreach ($this->attributes[$key] as $ckey => $cvalue) {
                switch ($ckey) {
                    case 'required':
                        if (!isset($attributes[$key])) $current[$ckey] = ucfirst($key)." is $ckey";
                        break;
                    case 'unique':
                        global $db;
                        if (isset($attributes[$key])) {
                            try {
                                $query = "SELECT * FROM learn_user WHERE $key=$attributes[$key]";
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                if (count($stmt->fetchAll()) > 0) $current[$ckey] = ucfirst($key)." already exists";
                            } catch (PDOException $e) {

                            }

                        }
                        break;
                    case 'enum':
                        # code...
                        break;
                    default:
                        # code...
                        break;
                }
            }
            if(count($current) > 0) $errors[$key] = $current;
        }
        if (count($errors) > 0) {
            return $errors;
        } else {
            return true;
        }
    }

    public function create() {
        global $db;
        $query = "INSERT INTO learn_user (email, password, last_name, first_name, mobile_number, address) VALUES (?,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(
                $this->email,
                $this->password,
                $this->last_name,
                $this->first_name,
                $this->mobile_number,
                $this->address
        ));
        $query = "SELECT * FROM learn_user WHERE email='$this->email'";
        echo $query;
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

?>
