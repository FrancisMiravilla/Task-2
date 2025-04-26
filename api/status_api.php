<?php

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");

    include "database.php";
    include "../class/Habit.php";

    $database = new Database();
    $db = $database->getConnection();

    $habit = new Habit($db);

    $method = $_SERVER["REQUEST_METHOD"];

    switch ($method){
        case 'GET':
            $habits = $habit->getCompletedHabit();
            echo json_encode(["status" => "success", "habits" => $habits]);
            break;
        case 'POST':
            $postData = file_get_contents("php://input");
            $data = json_decode($postData, true);

            $habit->updateHabitByStatus($data["id"], $data["status"]);
            echo json_encode(["status" => "success"]);
            break;

    }
?>