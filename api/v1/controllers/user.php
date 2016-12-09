<?php
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
    username VARCHAR(40) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(80),
    UNIQUE (username),
    PRIMARY KEY (user_id)
)';
$stmt = $db->prepare($query);
$stmt->execute();
if (count($args) > 2) {
    echo json_encode(array('status' => false, 'message' => 'Page Not Found'));
} elseif (count($args) == 2 && !is_numeric($args[1])) {
    echo json_encode(array('status' => false, 'message' => 'Page Not Found'));
} else {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (count($args) < 2) {
                $query = "SELECT * FROM learn_user";
                $stmt = $db->prepare($query);
                $stmt->execute();
                echo json_encode(array("status"=> 200, "message" => "Users retrieved succesfully", "body" => $stmt->fetchAll()));
                exit(0);
            }
            $query = "SELECT * FROM learn_user WHERE user_id=$args[1]";
            $stmt = $db->prepare($query);
            $stmt->execute();
            echo json_encode(array("status"=> 200, "message" => "User $args[1] retrieved succesfully", "body" => $stmt->fetchAll()));
            break;

        case 'POST':
            $query = "INSERT INTO learn_user (username, password) VALUES (?,?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT)));
            echo json_encode(array("status"=> 200, "message" => "User created succesfully", "body" => $_POST));
            break;

        case 'PUT':
            echo json_encode(array("status"=> 200, "message" => "User updated succesfully"));
            break;

        case 'DELETE':
            echo json_encode(array("status"=> 200, "message" => "User deleted succesfully"));
            break;
        default:
            echo json_encode(array("status"=> 400, "message" => "HTTP METHOD NOT SUPPORTED"));
            break;
    }
}

?>
