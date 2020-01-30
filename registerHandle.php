<?php
require ('db.php');

$query = "  INSERT INTO user (username, email, password)
            VALUES (:username, :email, :password)";

$response = $bdd->prepare($query);
$response->execute([
    'username' => $_POST['username'],
    'email' => $_POST['email'],
    'password' => $_POST['password'],
]);

Header('Location: index.php');