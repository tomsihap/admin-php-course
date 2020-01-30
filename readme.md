# Réalisation d'un espace administrateur et sessions

## 1. Créer une page Register

Il suffit de créer un formulaire qui enregistre pour l'instant en brut des données utilisateur (nom, prénom, e-mail, mot de passe...). Nous chiffrerons plus tard les mots de passe.

Notre formulaire contiendra les inputs `username`, `email`, `password` et pointera vers `registerHandle.php` :

### `registerHandle.php`
```php
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
```

## 2. Créer une page Login
Il suffit de créer un formulaire qui contiendra deux champs : `email` et `password` et qui pointera vers `loginHandle.php`, qui va **chercher en base de données un user qui possède cet e-mail et ce password** : 


### `loginHandle.php`
```php
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
```

Et voilà ! Il nous suffit maintenant de tester `$user` : s'il contient un user, alors l'user a été trouvé en base de données, donc ses identifiants sont bons, donc on le connecte" ! Si `$user` est `false`, alors l'user n'est pas connecté, ses identifiants ne sont pas encore bons. À la suite de `loginHandle.php` :

```php
if (!$user) {
    Header('Location: login.php');
}
else  {
    Header('Location: espaceAdmin.php');
}
```

Pour l'instant, il n'y a pas vraiment de connexion, juste des redirections en fonction du résultat.

## 3. Garder l'user connecté grâce aux sessions
Il faut maintenant dire à un endroit de notre code que l'utilisateur est connecté : pour cela, on va utiliser la superglobale `$_SESSION`.

**Attention :** N'oubliez pas qu'il faut **absolument** avoir `session_start()` **en haut de chaque fichier de l'application** pour utiliser les sessions !!

Modifions `loginHandle.php` :

```php
if (!$user) {
    $_SESSION['isConnected'] = false;
    Header('Location: login.php');
}
else  {
    $_SESSION['isConnected'] = true;
    Header('Location: espaceAdmin.php');
}
```

Cette variable suffit à nous dire si l'utilisateur a bien entré ses identifiants ou non.

Allons maintenant dans la page `espaceAdmin.php`:


### `espaceAdmin.php`
```php
<?php
// Testons si la session isConnected existe et est égale à true :
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true)
    echo "Bienvenue dans l'espace administrateur.";
else {
    // Si ça n'est pas le cas, on redirige vers la page de login :
    header('Location: index.php');
}
?>
```

## Utiliser la variable de session pour tester si l'utilisateur est logué partout dans le site

Cette simple variable nous suffit à tester si l'utilisateur est logué. Par exemple, dans une navbar, on pourrait tester s'il est logué pour afficher soit `Se connecter / Créer un compte`, soit `Se déconnecter` :

```php
<?php if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) : ?>
    <li class="nav-item">
        <a class="nav-link" href="logout.php">Déconnexion</a>
    </li>
<?php else : ?>
    <li class="nav-item">
        <a class="nav-link" href="login.php">Connexion</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="login.php">Créer un compte</a>
    </li>
<?php endif; ?>
```

## Déloguer un user
Il suffit de faire, dans une page `logout.php`, un `session_destroy()`, qui va donc vider la variable de session :

```php
<?php
session_start();
session_destroy();
header('Location: index.php');
?>
```