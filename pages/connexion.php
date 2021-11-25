<?php 
if(isset($_SESSION['prenom'])){
    echo "<script type='text/javascript'>document.location.replace('./?page=memberarea');</script>";
}
if(isset($_POST['envoie'])){
    $email = htmlspecialchars($_POST['email']);
    $mdp = sha1($_POST['mdp']);
    if (!empty($email) AND !empty($mdp)){
        $query = $dbh->prepare('SELECT * FROM clients WHERE email = ? AND mdp = ?');
        $query->execute(array($email, $mdp));
        $userExist= $query->rowCount();
        if($userExist === 1){
            $userInfos = $query->fetch();
            $_SESSION['id'] = $userInfos['id'];
            $_SESSION['prenom'] = $userInfos['prenom'];
            $_SESSION['nom']  = $userInfos['nom'] ;
            $_SESSION['email']=  $userInfos['email'] ;
            $_SESSION['admin'] = $userInfos['Admin'] ;
            echo "<script type='text/javascript'>document.location.replace('./?page=home_page');</script>";
            die();
        }else{
            $error = "Le mot de passe ou l'indentifiant est incorrect";
        }
    }else{
        $error = "Tous les champs doivent être remplis !";
    }
}else{
}

?>
<br>
<br>
<div class="inscription1">
<form action="" method="post">
<div style="display:flex; justify-content: center;align-items: center;flex-direction: column;">
<h3 class="mt-3">Connexion</h3>
<hr class="hr">
    <div class="form-floating mb-3 w-50">
      <input type="text" class="form-control" id="floatingInput" placeholder="email" name="email">
      <label for="floatingInput">Email</label>
    </div>
    <div class="form-floating w-50">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="mdp">
      <label for="floatingPassword">Password</label>
      <div class="col-12">
    	<br> 	
    <button type="submit" name="envoie" class="btn btn-primary" >Se connecter</button>
  </div>
    </div>
    
</div>
</form>
<?php if(isset($error)){
    echo $error;
    }?>
</div>

