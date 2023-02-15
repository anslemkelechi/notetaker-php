<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../class/note.php';
$database = new Database();
$db = $database->getConnection();
$item = new Note($db);
$item->user_id = $_COOKIE['user_id'] ?? null;

if ($item->getSingleNote()) {
    // create array
    $emp_arr = array(
        "id" =>  $item->id,
        "title" => $item->title,
        "body" => $item->body,
        "user_id" => $item->user_id,
        "created" => $item->created
    );

    http_response_code(200);
    echo json_encode($emp_arr);
} else {
    http_response_code(404);
    echo json_encode("No Note Found, Please Login.");
}
