<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../class/note.php';
include_once '../class/user.php';
$database = new Database();
$db = $database->getConnection();
$item = new Note($db);
$User = new User($db);
$data = json_decode(file_get_contents("php://input"));
$item->title = $data->title;
$item->body = $data->body;
$item->user_id = $_COOKIE['user_id'];
$item->created = date('Y-m-d H:i:s');

if (!$_COOKIE['user_id']) {
    echo 'Please Login to create note';
} else {
    if ($item->createNote()) {
        try {
            echo 'Note created successfully.';
        } catch (PDOException $e) {
            echo 'Error Creating Note';
        }
    } else {
        echo 'Account could not be created.';
    }
}
