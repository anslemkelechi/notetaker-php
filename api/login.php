<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../class/user.php';
$database = new Database();
$db = $database->getConnection();
$item = new User($db);
$data = json_decode(file_get_contents("php://input"));
$item->email = $data->email;
$item->password = $data->password;

if ($item->login()) {
    // create array
    $emp_arr = array(
        "id" =>  $item->id,
        "email" => $item->email,
        "password" => $item->password,
    );
    $cookie_name = "user_id";
    $cookie_value = $item->id;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

    http_response_code(200);
    echo json_encode($emp_arr);
    echo "Login Successful";
} else {
    http_response_code(404);
    echo json_encode("Login Unsuccessful.");
}
