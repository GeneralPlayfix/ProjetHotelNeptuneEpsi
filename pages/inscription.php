<?php 
if(isset($_SESSION['prenom'])){
    header("Refresh:0; url=./?page=memberarea");
}
if(isset($_POST['envoie'])){
    $lastname =    htmlspecialchars($_POST['nom']);
    $firstname =   htmlspecialchars($_POST['prenom']);
    $email =       htmlspecialchars($_POST['email']);
    $password =    sha1($_POST['mdp']);
    $password2 =   sha1($_POST['mdp2']);
    $address =     htmlspecialchars($_POST['addresse']);
    $city =        htmlspecialchars($_POST['ville']);
    $country =     htmlspecialchars($_POST['pays']);
    $postal_code = intval($_POST['codePostal']);
    $civility =    htmlspecialchars($_POST['civilite']);
    if(!empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['email']) AND !empty($_POST['mdp']) AND !empty($password2) AND !empty($_POST['addresse']) AND !empty($_POST['ville']) AND !empty($_POST['pays']) AND !empty($_POST['codePostal']) AND !empty($_POST['civilite'])){
        $lastnameLength = strlen($lastname);
        if($lastnameLength >= 2 || $lastnameLength <= 128){
            $firstnameLength = strlen($firstname);
            if($firstnameLength >= 2 || $firstnameLength <= 64){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $passwordLength = strlen($password);
                    if($passwordLength >= 4 || $passwordLength <= 30){
                        if($password == $password2){
                            $query = $dbh->prepare('SELECT email FROM clients WHERE email = ?');
                            $query->execute(array($email));
                            $emailCount = $query->rowCount();
                            if($emailCount === 0 ){
                                $sql = $dbh->prepare("INSERT INTO `neptune`.`clients`(civilite, nom, prenom,adresse,codePostal, ville, pays_id, email, mdp) VALUES
                                ('$civility','$lastname', '$firstname','$address','$postal_code','$city','$country','$email','$password');");
                                $sql->execute();
                                $request = $dbh->prepare('SELECT * FROM clients WHERE email = ?');
                                $request -> execute(array($email));
                                $userInfos = $request->fetch();
                                $_SESSION['id'] = $userInfos['id'];
                                $_SESSION['prenom'] = $userInfos['prenom'];
                                $_SESSION['nom']  = $userInfos['nom'];
                                $_SESSION['email']=  $userInfos['email'] ;
                                $_SESSION['admin'] = $userInfos['Admin'] ;
                                echo "<script type='text/javascript'>document.location.replace('./?page=home_page');</script>";
                                
                            }else{
                                $error = "Cet email est déjà associé à un compte, veuillez en saisir un autre !";
                            }
                        }else{
                            $error = "Votre mot de passe et sa confirmation de corresponde pas !";
                        }
                    }else{
                        $error = "Votre mot de passe doit comporter entre 4 et 30 caractères !";
                    }
                }else{
                    $error = "Votre email est invalide !";
                }
            }else{
                $error = "Votre nom doit comporter entre 2 et 128 caractères !";
            }
        }else{
            $error = "Votre nom de famille doit comporter entre 2 et 128 caractères !";
        }
    }else{
        $error = "Tous les champs doivent être remplis !";
    }
}
?>

<br>
<br>
<div class="inscription1">
<div style="display:flex; justify-content: center;align-items: center;flex-direction: column;">

<h3 class="mt-3">Inscription</h3>
<hr>
<form class="row g-3 mt-3 w-75 " method="post">
<div class="form-floating">
  <input type="text" class="form-control" id="prenom" placeholder="prenom" name="prenom">
  <label for="floatingPassword">Prenom</label>
</div>
<div class="form-floating">
  <input type="text" class="form-control" id="nom" placeholder="nom" name="nom">
  <label for="floatingPassword">Nom</label>
</div>
<!-- email -->
<div class="form-floating">
  <input type="email" class="form-control" id="email" placeholder="email" name="email">
  <label for="floatingPassword">Email</label>
</div>
<!-- mot de passe --> 
<div class="form-floating  col-md-6">
  <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="mdp">
  <label for="floatingPassword">Mot de passe</label>
</div>
<div class="form-floating  col-md-6">
  <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="mdp2">
  <label for="floatingPassword">Confirmer mot de passe</label>
</div>
<!--FIN mot de passe -->
<!-- Adresse -->
  <div class="form-floating">
  <input type="text" class="form-control" id="intputAdress" placeholder="34 rue de la vie" name="addresse">
  <label for="floatingPassword">Adresse</label>
</div>
  <div class="form-floating col-md-6">
  <input type="text" class="form-control" placeholder="34 rue de la vie"  name="ville">
  <label for="">Ville</label>
</div>
<div class="form-floating col-md-3">
  <select class="form-select" id="floatingSelect" aria-label="Pays" name="pays">
    <option selected>Choisir</option>
      <option value="1">France</option>
      <option value="2">Grande-Bretagne</option>
      <option value="3">Belgique</option>
	  <option value="4">Suisse</option>
  </select>
  <label for="floatingSelect">Pays </label>
</div>

<div class="form-floating col-md-3">
  <input type="number" class="form-control" id="floatingInput" placeholder="name@example.com" name="codePostal">
  <label for="floatingInput">Code Postal</label>
</div>
<div class="form-floating">
  <select class="form-select" id="floatingSelect" aria-label="Civilite" name="civilite">
    	<option selected>Choisir</option>
    	<option >Monsieur</option>
      	<option >Madame</option>
      	<option >Mademoiselle</option>
  </select>
  <label for="floatingSelect">Civilite </label>
</div>
  <div class="col-12">
    <button type="submit" name="envoie" class="btn btn-primary">Créer mon compte</button>
  </div>
</form>
	<?php 
	if(isset($error)){
	    echo $error;
	}
	?>
</div>
<br>
</div>
<br>
<br>

