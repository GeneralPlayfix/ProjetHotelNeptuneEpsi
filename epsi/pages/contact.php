<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>On reste en contact !</h1>
<br>
<br>
<br>
<pre>
    
  <?php

  print_r($_POST);
  ?>
</pre>
<form action="" method="post">
  <label for="">Prénom</label>
  <br>
  <input type="text" name="firstname" id="n" value="Florian">
  <br>
  <br>
  <label for="">Nom de famille</label>
  <br>
  <input type="text" name="lastname"value="Fournier" id="n" >
  <br>
  <br>
  <label for=""> Date de naissance :</label>
  <input type="date" name="birthdate">
  <br>
  <br>
  <label for="">message</label>
  <br>
  <textarea name="message" id="" cols="30" rows="10"></textarea>
  <br>
  <br>
  <label for="">email</label>
  <input type="email" name="email" id="">
  <br>
    <br>
  <label for="">Inscription newslatter</label>
  <br>
  <input type="checkbox">
  <br>
    <br>
  <br>
  <input type="submit" value="Envoyer"id="n" >
</form> 
<?php
// 0. Vérifier que les variables existent, aka tous mes champs requis
$fields = ['firstname', 'lastname', 'birthdate', 'message'];

foreach ($fields as $field) { // firstname, lastname, ...
    if (! array_key_exists($field, $_POST) || empty($_POST[$field])) {
        echo "<blockquote>Tous les champs doivent être rempli !</blockquote>";
        exit();
    }
}

// 1. Récupération des variables
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$message = $_POST['message'];
$email = $_POST['email'];
$birthdate = $_POST['birthdate'];

// condition particulière champ non requis
if (array_key_exists('newsletter', $_POST)) {
    $newsletter = $_POST['newsletter'] === 'on';
} else {
    $newsletter = 0;
}

// 2. connexion à la base de données
$dsn = 'mysql:host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// 3. Insérer des données dans la base
$stmt = $dbh->prepare(
"INSERT INTO `epsi`.`contact` 
    (firstname, lastname, message,email, newsletter, birthdate) VALUES 
    ('$firstname', '$lastname', '$message','$email','$newsletter', '$birthdate')
;");

if (!$stmt) {
    print_r($dbh->errorInfo());
}

// 4. Afficher un message à l'utilisateur
$isCreated = $stmt->execute();

if ($isCreated) {
    echo "Merci de votre message";
} else {
    echo "Déso, ça n'a pas marché !";
}
?>
</body>
</html>