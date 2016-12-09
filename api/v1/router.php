<?php
// require_once 'MyAPI.class.php';
// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

// try {
//     $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
//     echo $API->processAPI();
// } catch (Exception $e) {
//     echo json_encode(Array('error' => $e->getMessage()));
// }

header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

$args = explode('/', rtrim($_REQUEST['q'], '/'));

switch (strtolower($args[0])) {
    case 'user':
        require 'models/User.php';
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $User = new User($_POST);
                echo json_encode($User->create());
                break;
            case 'PUT':
                $putData = fopen("php://input", "r");
                if (empty($putData)) {
                    echo json_encode(array("status" => 400, "message" => "Body cannot be empty"));
                }
                echo json_encode($putData);
                $User = new User($putData, $args[1]);
                echo json_encode($User->update());
                break;
            default:
                echo json_encode(array("status"=> 400, "message" => "HTTP METHOD NOT SUPPORTED"));
                break;
        }
        break;

    default:
        echo json_encode(array('status' => false, 'message' => 'Page Not Found'));
        break;
}

?>
