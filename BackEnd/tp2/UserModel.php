<?php
class UserModel {
    public $props;

    public function __construct() {
        $this->props = array();
    }

    public function __set(string $key, mixed $value) {
        $this->props[$key] = $value;
    }

    public function __get(string $key) {
        return $this->props[$key];
    }

    public static function getAllUsers() {
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("SELECT * FROM users");
        $request->execute();
    
        return $request->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "UserModel");
    }

    public static function getUserById(int $id) {
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $request->bindValue(":id", $id);
        $request->execute();

        $result = $request->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "UserModel");
        if (isset($result)) {
            return $result[0];
        } else {
            return null;
        }
    }

    public function createUser($user) {
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $request->bindValue(":name", $this->name);
        $request->bindValue(":email", $this->email);
        $request->execute();

        $this->id = $pdo->lastInsertId();
    }

    public function updateUser() {
        if (!isset($this->id)) {
            return false;
        }
        
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $request->bindValue(":id", $this->id);
        $request->bindValue(":name", $this->name);
        $request->bindValue(":email", $this->email);
        return $request->execute();
    }

    public function deleteUser() {
        if (!isset($this->id)) {
            return false;
        }

        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $request->bindValue(":id", $this->id);
        return $request->execute();
    }
}