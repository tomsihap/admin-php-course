<?php
require ('db.php');

$query = "  SELECT * FROM user
            WHERE email = :email
            AND password = :password";

$response = $bdd->prepare($query);
$response->execute([
    'email' => $_POST['email'],
    'password' => $_POST['password'],
]);

$user = $response->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    Header('Location: login.php');
}
else  {
    Header('Location: espaceAdmin.php');
}