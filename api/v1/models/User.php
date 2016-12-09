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
    private $currentID;
    private $body = array();

    function __construct($body=NULL, $id=NULL) {
        if (!empty($body)) $this->body = $body;
        if (!is_null($id)) $this->currentID = $id;
        if (is_array($body) && !empty($body)) {
            $validated = $this->validate_attributes($body);
            if ($validated === true) {
                $this->body['email'] = $body['email'];
                $this->body['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
                if (isset($body['last_name'])) $this->body['last_name'] = $body['last_name'];
                if (isset($body['first_name'])) $this->body['first_name'] = $body['first_name'];
                if (isset($body['mobile_number'])) $this->body['mobile_number'] = $body['mobile_number'];
                if (isset($body['address'])) $this->body['address'] = $body['address'];
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
                                $query = "SELECT * FROM learn_user WHERE $key="
                                . (is_numeric($attributes[$key]) ? $attributes[$key] : "'".$attributes[$key]) ."'";
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                if (count($stmt->fetchAll()) > 0) $current[$ckey] = ucfirst($key)." already exists";
                            } catch (PDOException $e) {
                                echo $e;
                                continue;
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
        if (empty($this->body)) {
            echo json_encode(array("status" => 400, "message" => "Body cannot be empty"));
            exit(1);
        }
        global $db;
        $query = "INSERT INTO learn_user (" . join(array_keys($this->body), ',')
        . ") VALUES (" . join(array_fill_keys(array_keys($this->body), "?"), ',') . ")";
        echo json_encode($query);
        $stmt = $db->prepare($query);
        $stmt->execute(array_values($this->body));
        $query = "SELECT * FROM learn_user WHERE email='".$this->body['email']."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function read() {
        global $db;
        $query = "SELECT * FROM learn_user WHERE user_id=$this->currentID";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        global $db;
        $query = "UPDATE learn_user SET (";
        $num_attr = count($this->body);
        echo json_encode($this->body);
        foreach ($this->body as $key => $value) {
            if ($num_attr-- > 1) {
                $query .= "$key = $value,";
            } else {
                $query .= "$key = $value)";
            }
        }
        $query .= "WHERE id=$this->currentID";
        return $query;
    }

}

?>
