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
            $habits = $habit->getHabit();
            echo json_encode(["status" => "success", "habits" => $habits]);
            break;
        case 'POST':
            $postData = file_get_contents("php://input");
            $data = json_decode($postData, true);

            $habit->addHabit($data["name"], $data["frequency"], $data["startDate"]);
            echo json_encode(["status" => "success"]);
            break;
        case 'PUT':
            $putData = file_get_contents("php://input");
            $data = json_decode($putData, true);

            $habit->updateHabit($data["editHabitId"], $data["editName"], $data["editFrequency"], $data["editStartDate"]);
            echo json_encode(["status" => "success"]);
            break;
        case 'DELETE':
            $delData = file_get_contents("php://input");
            $data = json_decode($delData, true);

            $habit->deleteHabit($data["deleteHabitId"]);
            echo json_encode(["status" => "success"]);
            break;
    }
?>