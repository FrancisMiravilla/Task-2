<?php

    class Habit{
        private $conn;
        private $table = "habit";

        public function __construct($db){
            $this->conn = $db;
        }

        public function getHabit(){
            $query = "SELECT * FROM " . $this->table . " WHERE status = 'Pending'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function addHabit($name, $frequency, $startDate){
            $query = "INSERT INTO " . $this->table . " (name, frequency, start_date) VALUES (:name, :frequency, :startDate)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([":name" => $name, ":frequency" => $frequency, ":startDate" => $startDate]);
        }

        public function updateHabit($id, $name, $frequency, $startDate){
            $query = "UPDATE " . $this->table . " SET name = :name, frequency = :frequency, start_date = :startDate WHERE habit_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([":name" => $name, ":frequency" => $frequency, ":startDate" => $startDate, ":id" => $id]);
        }

        public function deleteHabit($id){
            $query = "DELETE FROM " . $this->table . " WHERE habit_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([":id" => $id]);
        }

        public function updateHabitByStatus($id, $status){
            $query = "UPDATE " . $this->table . " SET status = :status WHERE habit_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(["status" => $status,":id" => $id]);
        }

        public function getCompletedHabit(){
            $query = "SELECT * FROM " . $this->table . " WHERE status = 'Completed'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>