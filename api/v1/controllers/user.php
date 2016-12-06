<?php

if (count($args) > 2) {
    echo json_encode(array('status' => false, 'message' => 'Page Not Found'));
} elseif (count($args) == 2 && !is_numeric($args[1])) {
    echo json_encode(array('status' => false, 'message' => 'Page Not Found'));
} else {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (count($args) < 2) {
                echo json_encode(array("status"=> 200, "message" => "Users retrieved succesfully"));
                exit(0);
            }
            echo json_encode(array("status"=> 200, "message" => "User $args[1] retrieved succesfully"));
            break;

        default:
            # code...
            break;
    }
}

?>
