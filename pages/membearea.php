<?php 
$query = $dbh->prepare('SELECT * FROM pays');
$query -> execute();
$Pays = $query->fetchAll();  
if(!isset($_SESSION['id'])){
echo "<script type='text/javascript'>document.location.replace('./?page=connexion');</script> ";
die();
}           
    //requête bdd préparée
    $requser = $dbh->prepare("SELECT * FROM clients WHERE id = ?");
    //j'execute la requete ci dessus
    $requser->execute(array($_SESSION['id']));
    //récupération des données
    $user = $requser->fetch();
    //var_dump($user);
    
    //si la variable firstname existe est qu'elle n'est pas vide et qu'elle est différente de prenom
    if(isset($_POST['Firstname']) AND !empty ($_POST['Firstname']) AND $_POST['Firstname'] != $user['prenom']){
        //création et sécurisation de la varianle firstname
        $Firstname = htmlspecialchars($_POST['Firstname']);
        $insertpseudo = $dbh->prepare("UPDATE clients SET prenom = ? WHERE id = ?");
        $insertpseudo->execute(array($Firstname, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
    }
    //Meme modification pour le nom
    if(isset($_POST['Lastname']) AND !empty ($_POST['Lastname']) AND $_POST['Lastname'] != $user['nom']){
        $Lastname = htmlspecialchars($_POST['Lastname']);
        $insertpseudo = $dbh->prepare("UPDATE clients SET nom = ? WHERE id = ?");
        $insertpseudo->execute(array($Lastname, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
    }
    /***********************************email****************************************************/
    if(isset($_POST['Email']) AND !empty ($_POST['Email']) AND $_POST['Email'] != $user['email']){
        $email = htmlspecialchars($_POST['Email']);
        $query2 = $dbh -> prepare('SELECT email FROM clients WHERE email = ?');
        $query2 -> execute(array($email));
        $response = $query2->rowCount();
        if($response === 0){
        $changeEmail = $dbh->prepare("UPDATE clients SET email = ? WHERE id = ?");
        $changeEmail->execute(array($email, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
        }else{
            $msg = "Cette email est déjà associé à un compte, veuillez un saisir une autre !";
        }
    }
    /***********************************Mot de passe********************************************/
    if(isset($_POST['Password']) AND !empty ($_POST['Password']) AND isset($_POST['Password2']) AND !empty ($_POST['Password2'])){
        $Password = $_POST['Password'];
        $Password2 = $_POST['Password2'];
        
        if($Password == $Password2){
            $insertPassword = $dbh->prepare('UPDATE clients SET mdp = ? WHERE id = ?');
            $insertPassword->execute(array($Password, $_SESSION['id']));
            echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
        }else{
            $msg= "Les mots de passe ne correspondent pas";    
        }
        
    }
    /****************************************adresse********************************************/
    if(isset($_POST['Address']) AND !empty ($_POST['Address']) AND $_POST['Address'] != $user['adresse']){
        $Address = htmlspecialchars($_POST['Address']);
        $insertpseudo = $dbh->prepare("UPDATE clients SET adresse = ? WHERE id = ?");
        $insertpseudo->execute(array($Address, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
    }
    /***************************************Ville**************************************************/
    if(isset($_POST['City']) AND !empty ($_POST['City']) AND $_POST['City'] != $user['ville']){
        $City = htmlspecialchars($_POST['City']);
        $insertpseudo = $dbh->prepare("UPDATE clients SET ville = ? WHERE id = ?");
        $insertpseudo->execute(array($City, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
    }
     /***************************************Pays**************************************************/
    if(isset($_POST['Country']) AND !empty ($_POST['Country']) AND $_POST['Country'] != $user['pays_id']){
        $Country = htmlspecialchars($_POST['Country']);
        $insertCountry = $dbh->prepare("UPDATE clients SET pays_id = ? WHERE id = ?");
        $insertCountry->execute(array($Country, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
    }
    /*Code postal*/
    if(isset($_POST['PostalCode']) AND !empty ($_POST['PostalCode']) AND $_POST['PostalCode'] != $user['codePostal']){
        $PostalCode = htmlspecialchars($_POST['PostalCode']);
        $insertpseudo = $dbh->prepare("UPDATE clients SET codePostal = ? WHERE id = ?");
        $insertpseudo->execute(array($PostalCode, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
    }
    /*civilit�e a faire aussi*/
    /***************************************Ville**************************************************/
    if(isset($_POST['Civility']) AND !empty ($_POST['Civility']) AND $_POST['Civility'] != $user['civilite']){
        $Civility = htmlspecialchars($_POST['Civility']);
        $insertcivility = $dbh->prepare("UPDATE clients SET civilite = ? WHERE id = ?");
        $insertcivility->execute(array($Civility, $_SESSION['id']));
        echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
    }
?>
<div class="inscription">
<div style="display: flex; justify-content: center; margin-top: 3%;">
<form class="row g-2" method="post" style="width: 80%;">
<div class="col-md-6">
    <label for="" class="form-label">Prénom :</label>
    <input type="text" class="form-control" id="Firstname" name="Firstname" value="<?php echo $user['prenom'];?>" >  
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Nom :</label>
    <input type="text" class="form-control" id="Password" name="Lastname" value="<?php echo $user['nom'];?>" >
  </div>
  <div class="col-w">
    <label for="inputEmail4" class="form-label">Email :</label>
    <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $user['email'];?>" >
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Mot de passe :</label>
    <input type="password" class="form-control" id="Password" name="Password">
  </div>
  <div class="col-md-6">
    <label for="" class="form-label">Confirmation mot de passe :</label>
    <input type="password" class="form-control" id="Password2" name="Password2">  
  </div>
  <div class="col-12">
    <label for="inputAddress" class="form-label">Adresse :</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St" name="Address" value="<?php echo $user['adresse'];?>">
  </div>
  <div class="col-md-6">
    <label for="inputCity" class="form-label">Ville</label>
    <input type="text" class="form-control" id="inputCity" name="City" value="<?php echo $user['ville'];?>">
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Pays</label>
    <select id="inputState" class="form-select" name="Country" itemid="">
      <?php 
        
      foreach($Pays as $pays){
      ?>
       <option value="<?php echo $pays['id']?>"  <?php if($pays['id'] === $user['pays_id']){echo "selected";}else { " " ;}?> ><?php echo $pays['nom'] ?></option>
      <?php
      }
      ?>  
    </select>
  </div>
  <div class="col-md-2">
    <label for="inputZip" class="form-label">Code Postal</label>
    <input type="text" class="form-control" id="inputZip" name="PostalCode" value="<?php echo $user['codePostal'];?>">
  </div>

  <div class="col-w">
    <label for="inputState" class="form-label">Civilité</label>
    <select id="inputState" class="form-select" name="Civility">
      <option <?php  if($user['civilite'] === "Monsieur"){echo "selected";}else{" ";}?>>Monsieur</option>
      <option  <?php if($user['civilite'] === "Madame"){echo "selected";}else{" ";}?>>Madame</option>
      <option  <?php if($user['civilite'] === "Mademoiselle"){echo "selected";}else{" ";}?>>Mademoiselle</option>
    </select>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Mettre a jour mon profil</button>
  </div>
</form>
</div>
</div>
<?php if(isset($msg)) {echo $msg;}?>
