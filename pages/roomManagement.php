<?php 
if (!isset($_GET['modificationchambre'])) {
?>
<?php
/**
 * R�cup�ration des utilisateurs
 */
$statement = $dbh ->prepare('SELECT * FROM `Neptune`.`chambres`;');
$statement->execute(); // execute le SQL dans la base de donn�es (MySQL / MariaDB)

$chambres = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
 
 <?php

 
/**
 * V�rifier que l'utilisateur a posté un formulaire de suppression
 *
 * @param id de du contact à supprimer
 */
if (array_key_exists('delete', $_POST)) {
    $chambresId = $_POST['delete'];
    // Injection SQL possible avec : 3; DROP DATABASE epsi
    // $statement = $dbh->prepare("DELETE FROM `epsi`.`contact` WHERE id = $contactId;");
    $statement = $dbh->prepare("DELETE FROM `Neptune`.`chambres` WHERE id = ?");
    $statement->execute(array($chambresId));
    header("Refresh:0");
    unset($_POST['delete']);
}
?>
  <a href="./?page=addRoom" class="btn btn-primary" id="add">Ajouter une chambre</a>
<table class="table table-dark table-striped" style="margin-top: 2%">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Capacité</th>
      <th scope="col">Exposition</th>
      <th scope="col">Douche</th>
      <th scope="col">Étage</th>
      <th scope="col">Tarif_id</th>
      <th scope="col">Image</th>
      <th scope="col">Modifier</th>
      <th scope="col">effacer</th>
    </tr>
  </thead>
  <tbody>
  
    <?php 
    if (!empty($chambres)) {
    foreach ($chambres as $chambre){ ?>
    <tr>
      <td><?php echo $chambre['id']; ?></td>
      <td><?php echo $chambre['capacite']; ?></td>
      <td><?php echo $chambre['exposition']; ?></td>
      <td><?php echo $chambre['douche']; ?></td>
      <td><?php echo $chambre['etage']; ?></td>
      <td><?php echo $chambre['tarif_id']; ?></td>
      <td> <img alt="" src="<?php echo $chambre['Liens']?>" style="width: 100px; height:50px;"> </td>
      <td> 
      
      <form action="" method="post"> 
        	  <a href="./?page=roomManagement&modificationchambre=<?php echo $chambre['id'];?>" class="btn btn-primary">Modifier</a>
     	</form>
      </td>
      <td>
     	<form action="" method="post"> 
          	<input type="hidden" name="delete" value="<?php echo $chambre['id']; ?>">
        	<input class="btn btn-primary" type="submit" value="Effacer"> 
     	</form>
     </td> 
     	
    </tr>
      <?php }}?>
    
      
  </tbody>
</table>

<?php 
} else {
    $statement = $dbh->prepare('SELECT * FROM chambres where id = :id', array(PDO::PARAM_STR, PDO::PARAM_STR));
    $statement->execute(array(':id' => $_GET['modificationchambre']));
    $chambres = $statement->fetchAll();
   
    
    if (sizeof($chambres) == 1) {
        $chambre = $chambres[0];
        /********************* modification de la capacité ****************************/
        if(isset($_POST['Capacity']) AND !empty ($_POST['Capacity']) AND $_POST['Capacity'] != $chambre['capacite']){
            $Capacity = htmlspecialchars($_POST['Capacity']);
            $changeCapacity = $dbh->prepare("UPDATE chambres SET capacite = ? WHERE id = ?");
            $changeCapacity->execute(array($Capacity,$chambre['id']));
            echo "<script type='text/javascript'>document.location.replace('./?page=roomManagement&modificationchambre=".$_GET['modificationchambre']."') ;</script>";
        }
        
        /************************** modification de l'exposition***************************/
        if(isset($_POST['Exposition']) AND !empty ($_POST['Exposition']) AND $_POST['Exposition'] != $chambre['exposition']){
            $Exposition = htmlspecialchars($_POST['Exposition']);
            $changeExposition = $dbh->prepare("UPDATE chambres SET exposition = ? WHERE id = ?");
            $changeExposition->execute(array($Exposition,$chambre['id']));
            echo "<script type='text/javascript'>document.location.replace('./?page=roomManagement&modificationchambre=".$_GET['modificationchambre']."') ;</script>";
        }
        
        /***************************** modification douche*****************************/

        if(isset($_POST['Shower'])){
                $Shower = intval($_POST['Shower']);  
                $changeShower = $dbh->prepare( "UPDATE chambres SET douche = ? WHERE id = ?");
                $changeShower->execute(array($Shower, $chambre['id']));
            echo "<script type='text/javascript'>document.location.replace('./?page=roomManagement&modificationchambre=".$_GET['modificationchambre']."') ;</script>";
        }
        /********************************* modification etage******************************************/
        if(isset($_POST['Floor']) AND !empty ($_POST['Floor']) AND $_POST['Floor'] != $chambre['etage']){
            $Floor = htmlspecialchars($_POST['Floor']);
            $changeFloor = $dbh->prepare("UPDATE chambres SET etage = ? WHERE id = ?");
            $changeFloor->execute(array($Floor ,$chambre['id']));
            echo "<script type='text/javascript'>document.location.replace('./?page=roomManagement&modificationchambre=".$_GET['modificationchambre']."') ;</script>";
        }
        /*****************************modification Description ********************************/
        if(isset($_POST['Description']) AND !empty ($_POST['Description']) AND $_POST['Description'] != $chambre['description']){
            $Description = htmlspecialchars($_POST['Description']);
            $changeExposition = $dbh->prepare("UPDATE chambres SET description = ? WHERE id = ?");
            $changeExposition->execute(array($Description ,$chambre['id']));
            echo "<script type='text/javascript'>document.location.replace('./?page=roomManagement&modificationchambre=".$_GET['modificationchambre']."') ;</script>";
        }
        /******************************** modification de l'image **************************/
        if(isset($_POST['Image']) AND !empty ($_POST['Image']) AND $_POST['Image'] != $chambre['Liens']){
            $Image = htmlspecialchars($_POST['Image']);
            $changeImage = $dbh->prepare("UPDATE chambres SET Liens = ? WHERE id = ?");
            $changeImage->execute(array($Image ,$chambre['id']));
            echo "<script type='text/javascript'>document.location.replace('./?page=roomManagement&modificationchambre=".$_GET['modificationchambre']."') ;</script>";
        }
        }
        ?>
        
<div class="inscription">

<div style="display: flex; justify-content: center; margin-top: 3%;">
<form class="row g-2" method="post" style="width: 80%;">
<div class="col-md-6">
    <label for="" class="form-label">Capacité de la chambre:</label>
    <input type="text" class="form-control" id="Capacity" name="Capacity" value="<?php echo $chambre['capacite'];?>" >  
  </div>
  <div class="col-md-6">
    <label for="inputState" class="form-label">Exposition</label>
    <select id="inputState" class="form-select" name="Exposition" itemid="">
       <option value="rempart" <?php echo (isset($chambre['exposition'])&& $chambre['exposition'] === "rempart")? "selected" :" "?>>Rempart</option>
       <option value="port" <?php echo (isset($chambre['exposition'])&& $chambre['exposition'] === "port")? "selected" :" "?>>Port</option>
    </select>
  </div>
 <div class="col-md-6">
    <label for="inputState" class="form-label">Avec douche :</label>
    <select id="inputState" class="form-select" name="Shower" itemid="">
       <option value="1" <?php echo (isset($chambre['douche'])&& $chambre['douche'] === 1)? "selected" :" "?>>Oui</option>
       <option value="0" <?php echo (isset($chambre['douche'])&& $chambre['douche'] === 0)? "selected" :" "?>>Non</option>
    </select>
  </div>
  <div class="col-md-6">
    <label for="inputFloor" class="form-label">A l'etage :</label>
    <input type="number" class="form-control" id="Floor" name="Floor" value="<?php echo $chambre['etage'];?>">
  </div>
  <div class="mb-15">
  <label for="exampleFormControlTextarea1" class="form-label">Description</label>
  <textarea class="form-control" id="Description" name="Description" rows="3" ><?php echo $chambre['description']?></textarea>
</div>
<div style="display: flex; flex-direction: column;justify-content: center;align-items: center ">
<h4>Photo de la chambre :</h4>
  <img alt="" src="<?php echo $chambre['Liens']?>" style="width: 50%;">
  </div>
  <div class="col-md-12">
    <label for="inputFloor" class="form-label">Modifier le lien de l'image:</label>
    <input type="text" class="form-control" id="Image" name="Image" value="<?php echo $chambre['Liens'];?>">
  </div>
	
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Mettre a jour mon profil</button>
  </div>
  
</form>
</div>
</div>


<?php 
        }
?>