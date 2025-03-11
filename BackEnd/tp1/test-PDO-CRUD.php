<?php
// Connexion à la base de données
require_once("initPDO.php");

class User {
    public $props;

    public static $users;

    public function __construct() {
        $this->props = array();
    }

    public function __set(string $key, mixed $value) {
        $this->props[$key] = $value;
    }

    public function __get(string $key) {
        return $this->props[$key];
    }

    public static function getAllUsers($pdo) {
        $request = $pdo->prepare("SELECT * FROM users");
        $request->execute();
    
        return $request->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
    }

    public static function showUserAsTable() {
        echo("<h2>Liste des Utilisateurs</h2>");
        echo("<table>");
            echo("<thead>");
                echo("<tr>");
                    echo("<th>ID</th>");
                    echo("<th>Nom d'utilisateur</th>");
                    echo("<th>Email</th>");
                    echo("<th>Actions</th>");
                echo("</tr>");
            echo("</thead>");
            echo("<tbody>");
                foreach (STATIC::$users as $user){
                    $user->toHtml();
                }
            echo("</tbody>");
        echo("</table>");
    }

    public function toHtml() {
        echo "<tr>";
        echo "<form method='post'>";
        echo "<td>{$this->id}</td>";
        
        if (isset($_POST['edit']) && $_POST['edit'] == $this->id) {
            echo "<td><input type='text' name='name' value='{$this->name}' required></td>";
            echo "<td><input type='email' name='email' value='{$this->email}' required></td>";
            echo "<td>
                    <input type='hidden' name='update_id' value='{$this->id}'>
                    <button type='submit' name='update'>Confirmer</button>
                    <button type='button' name='update' onclick='history.back()'>Annuler</button>
                  </td>";
        } else {
            echo "<td>{$this->name}</td>";
            echo "<td>{$this->email}</td>";
            echo "<td>
                    <form method='post'>
                        <input type='hidden' name='edit' value='{$this->id}'>
                        <button type='submit'>Modifier</button>
                    </form>
                    <form method='post'>
                        <input type='hidden' name='delete_id' value='{$this->id}'>
                        <button type='submit' name='delete'>Supprimer</button>
                    </form>
                  </td>";
        }
    
        echo "</form>";
        echo "</tr>";
    }    
}

User::$users = User::getAllUsers($pdo);



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_id"]) && isset($_POST["name"], $_POST["email"])) {
    $updateQuery = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
    $updateQuery->execute([
        ":id" => $_POST["update_id"],
        ":name" => trim($_POST["name"]),
        ":email" => trim($_POST["email"])
    ]);

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
} else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"], $_POST["email"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $insertQuery = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $insertQuery->execute([
            ":name" => $name,
            ":email" => $email
        ]);

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    } else {
        $error = "Veuillez remplir tous les champs correctement.";
    }
} else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $deleteQuery = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $deleteQuery->execute([
        ":id" => $_POST["delete_id"]
    ]);

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}

/*** Fermeture de la connexion ***/
$pdo = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td { 
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: rgb(240, 240, 240);
        }
        .form-container {
            width: 50%;
            margin: 20px auto;
            text-align: center;
        }
        input, button {
            padding: 8px;
            margin: 5px;
            width: 90%;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php 
    User::showUserAsTable(); 
    ?>
    <div class="form-container">
        <h3>Ajouter un Utilisateur</h3>
        <?php if(isset($error)) { 
            echo $error; 
        }?>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Ajouter</button>
        </form>
    </div>

</body>
</html>