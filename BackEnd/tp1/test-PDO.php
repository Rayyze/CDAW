<?php
// initialise une variable $pdo connecté à la base locale
require_once("initPDO.php");    // cf. doc / cours

class User {
    public int $id;
    public string $name;
    public string $email;

    public function __construct($id, $name, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
}

$request = $pdo->prepare("SELECT * FROM users");
$request->execute();

$users = [];
while ($row = $request->fetch(PDO::FETCH_ASSOC)) {
    $users[] = new User($row['id'], $row['name'], $row['email']);
}

/*** close the database connection ***/
$pdo = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <style>
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
            background-color:rgb(240, 240, 240);
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
            <?php foreach ($users as $user){
                echo("<tr><td>$user->id</td><td>$user->name</td><td>$user->email</td></tr>");
            } ?>
        </tbody>
    </table>
</body>
</html>
