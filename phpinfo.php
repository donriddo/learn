<?php
$attributes = array(
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

// $errors = array();
// foreach ($attributes as $key => $value) {
//     $current = array();
//     foreach ($attributes[$key] as $ckey => $cvalue) {
//         $current[$ckey] = ucfirst($key)." is $ckey";
//     }
//     if(count($current) > 0) $errors[$key] = $current;
// }
// echo json_encode($errors);
if (is_null(1)) {
    echo "NULL is a NULL";
} else {
    echo "NULL isn't a NULL";
}
?>
