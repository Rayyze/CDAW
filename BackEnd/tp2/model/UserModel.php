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

    public function password() {
        return $this->pwd;
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
        if (isset($result) && count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

    public function createUser($user) {
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("INSERT INTO users (name, email, pwd) VALUES (:name, :email, :pwd)");
        $request->bindValue(":name", $this->name);
        $request->bindValue(":email", $this->email);
        $password_hash = password_hash($this->pwd, PASSWORD_BCRYPT);
        $request->bindValue(":pwd", $password_hash);
        $request->execute();

        $this->id = $pdo->lastInsertId();
    }

    public function updateUser() {
        if (!array_key_exists('id', $this->props)) {
            return false;
        }
        
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("UPDATE users SET name = :name, email = :email, pwd = :pwd WHERE id = :id");
        $request->bindValue(":id", $this->id);
        $request->bindValue(":name", $this->name);
        $request->bindValue(":email", $this->email);
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $request->bindValue(":pwd", $password_hash);
        return $request->execute();
    }

    public function deleteUser() {
        if (!array_key_exists('id', $this->props)) {
            return false;
        }

        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $request->bindValue(":id", $this->id);
        return $request->execute();
    }

    // Dans le cadre de l'exercice on supposera que l'email est unique même si MySQL n'a pas été configuré ainsi
    public static function tryLogin(string $email) {
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $request->bindValue(":email", $email);
        $request->execute();

        $result = $request->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "UserModel");
        if (isset($result) && count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }
}