<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
//Donner la date du jour
$Datetime = new\DateTime();
$todaysDateTime= $Datetime->format('d/m/Y');

//donner la date d'anniversaire
$birthdate = new \DateTime('2002-09-21');
echo $birthdate->format('d/m/Y');

//cela nous donne notre age par rapport à l'âge actuel
$interval = $birthdate->diff($Datetime);
echo "Vous avez " . $interval->format('%Y')." ans";

//rajouter un an a la date, si on veut rajouter un mois on met après DateInterval 'P1M'(plus un mois), on peut aussi mettre plus dix jour ('P10D')
$birthdate->add(new DateInterval('P1Y'));
echo $birthdate->format('Y-m-d');

//utiliser pour rajouter une année a $date
$date = new \DateTime('2002-09-21');
$date->add(new DateInterval('P1Y'));

  if ($date>$birthdate){
    echo"L'abonnement est toujours valide";
  }
  
$families=["brother", "sister", "cousin", "uncle"];
//array_push:pousser une variable dans un tableau
array_push($families, "mother");
//var_dump afficher à l'écran
var_dump($families);

//pareil que array push mais il est le plus utilisé des deux
$families[]="father";
var_dump($families);

echo $families[0];
echo $families[1];

?>

<?php
$families=[
  'brothers'=>["Nassim", "Alex", "Hinata"],
  'sisters'=>["Jeanne"], 
  'mother'=>"Marie",
];

// print_r($families); :si on utilise print r il faut rajouter les balises html pre
//print_r($families['sisters']); :sélectionner les soeurs de ta famille
echo $families['mother']; //afficher le nom de la mère
echo count($families['brothers']);//afficher le numéro du 'brothers' dans le tableau
?>
<br>
<ul>
<?php
$teacher = [
  'name' => 'SOYER',
  'firstname' => 'Alexandre'
];

$student = [
  'name' => 'ALDERSON',
  'firstname' => 'Elliot',
];

$users = [
  $teacher,
  $student
];
echo '<h2>Fait avec for (moins bien)</h2>';
echo '<ul>';//constamment utiliser des '' quand on utilise de l'html
//aussi mettre echo avant donc echo '<li>'
for($i=0; $i<2; $i++){
 echo '<li>'.$users[$i]['firstname'].' '.$users[$i]['name'].'</li>';
}
echo '</ul>';
echo '<br>';
echo '<h2>Fait avec Foreach </h2>';
echo '<ul>';//constamment utiliser des '' quand on utilise de l'html
//aussi mettre echo avant donc echo '<li>'
foreach($users as $user){ //j'ai une liste (users)que je met dans un 'user' unique, elle fonctionne de grossièrement de la même manière que le for. Le foreach te donne directement la valeur.(foreach=pour chaque utilisateurs) 
 echo '<li>'.$user['firstname'].' ' .$user['name'].'</li>';
}//il faut absolument mettre celui qu'on a instancié en second(dans notre cas $user), vu qu'on fait pour chaque individuellement (pas pour le premier 'users' qui contient l'ensemble)
echo '</ul>';
echo'<h2>While</h2>'; //très peu utilisé, à évité dans la mesure du posible(je parle du while).De plus risque de boucle infini, donc absolument respecter CHACUNE des étages ci-dessous!!!!!!!!!!!
$i=0;
echo '<ul>';
while ($i<2){
  echo '<li>' .$users[$i]['firstname'].' '.$users[$i]['name'] . '</li>';
  $i++;
}

echo'</ul>';
?>

</body>
</html> -->