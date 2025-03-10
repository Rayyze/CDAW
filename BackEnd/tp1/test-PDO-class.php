<?php
// Connexion à la base de données
require_once("initPDO.php");

class User {
    public int $id;
    public string $name;
    public string $email;

    public function __construct($id, $name, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public static function getAllUsers($pdo) {
        $request = $pdo->prepare("SELECT * FROM users");
        $request->execute();

        $users = [];
        while ($row = $request->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['name'], $row['email']);
        }

        return $users;
    }
}

users[] = User::getAllUsers($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"], $_POST["email"])) {
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

    <h2>Liste des Utilisateurs</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->id) ?></td>
                    <td><?= htmlspecialchars($user->name) ?></td>
                    <td><?= htmlspecialchars($user->email) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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