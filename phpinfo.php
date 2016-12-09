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
$okay = array(1,2);;
// echo json_encode($okay);
// echo json_encode("(".join(array_fill_keys(array_keys($okay), "?"), ',').")");
echo json_encode(join(array_keys(array('email' => "donriddo", "password" => "don")), ','));
$ok = "(1,2";
$ok .= ",3,4,5)";
// echo json_encode($ok);
?>
