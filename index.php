<?php
// phpinfo();

class User {
    private $username;
    public function __construct($username) {
        $this->username = $username;
    }
    public function response() {
        return json_encode(array(
            'success' => true,
            'message' => 'User retrieved succesfully',
            'body' => array('username' => $this->username)
        ));
    }
}

$donriddo = new User('donriddo');
echo $donriddo->response();
$db = new PDO("mysql:host=localhost;", "root", "d0nridd0");
$query = 'CREATE DATABASE IF NOT EXISTS learn';
$stmt = $db->prepare($query);
$stmt->execute();
echo "\n";
?>
