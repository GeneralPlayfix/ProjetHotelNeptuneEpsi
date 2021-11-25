<?php
$query = $dbh->prepare('SELECT * FROM tarifs');
$query -> execute();
$tarifs = $query->fetchAll();

    if(isset($_POST['add'])){ 
        if(!empty($_POST['Capacity']) && !empty($_POST['Exposition']) && !empty($_POST['Shower']) && !empty($_POST['Floor']) && !empty($_POST['Description']) && !empty($_POST['Image'])){ 
        $capacity = htmlspecialchars($_POST['Capacity']);
        $exposition = htmlspecialchars($_POST['Exposition']);
        $shower = htmlspecialchars($_POST['Shower']);
        $floor = intval($_POST['Floor']);
        $tarifs = htmlspecialchars($_POST['tarifs']);
        $description =htmlspecialchars($_POST['Description']);
        $image = htmlspecialchars($_POST['Image']); 
        $insertRoom = $dbh->prepare("INSERT INTO chambres (capacite, exposition, douche, etage, tarif_id, description, Liens) VALUES (?, ?, ?, ?, ?, ?,?)");
        $insertRoom -> execute(array($capacity, $exposition,$shower, $floor, $tarifs, $description, $image));
        echo "<script type='text/javascript'>document.location.replace('./?page=home_page');</script>";
        }else{
            $message = "Tout les champs doivent être rempli !";
        }
    }
        
?>

<div class="inscription1">

<div style="display: flex; justify-content: center; margin-top: 3%;">
	<form class="row g-3 mt-3 w-75" method="post" style="width: 80%;">

        <div class="form-floating">
          <input type="text" class="form-control" id="Capacity" placeholder="Capacite" value="<?php if(isset($capacity) AND !empty($capacity)){echo $capacity;}?>" name="Capacity">
          <label for="floatingPassword">Capacité de la nouvelle chambre : </label>
        </div>
     	<div class="form-floating col-md-6">
          <select class="form-select" id="floatingSelect" aria-label="Pays" name="Exposition">
          	<option selected>Choisir</option>
          	<option value="port"> Port </option>
          	<option value="rempart">Rempart</option>
          </select>
          <label for="floatingSelect">Exposition </label>
        </div>
          <div class="form-floating col-md-6">
          <select class="form-select" id="floatingSelect" aria-label="Pays" name="Shower">
          	<option selected>Choisir</option>
          	<option value="1"> oui </option>
          	<option value="0">non</option>
          </select>
          <label for="floatingSelect">Douche </label>
        </div>
		<div class="form-floating col-md-12">
  			<input type="number" class="form-control"  name="Floor" id="floatingInput" placeholder="30400"  value="<?php if (isset($floor) AND !empty($floor)){echo $floor;}?>">
  			<label for="floatingInput">Etage </label>
		</div>
		
         <div class="form-floating col-md-12">
          <select class="form-select" id="floatingSelect" aria-label="tarif" name="tarifs">
          	<option selected>Choisir</option>
          <?php 
  	             foreach($tarifs as $tarif){
  	             ?>
  					<option value="<?php echo $tarif['id'] ?>"><?php echo $tarif['prix'] ?> euros </option>
  				<?php
  	             }
  	             ?>
          </select>
          <label for="floatingSelect">Tarifs </label>
        </div> 
        <div class="form-floating">
          <textarea class="form-control" placeholder="Description" id="floatingTextarea2" style="height: 100px" name="Description"><?php if (isset($description) AND !empty($description)){echo $description;}?></textarea>
          <label for="floatingTextarea2">Description</label>
          
        </div>
        	<div class="form-floating col-md-12">
  			<input type="text" class="form-control" name="Image" id="floatingInput" placeholder="https://i.ibb.co/gyWhjkF/Cambre-id-2-2.jpg"  value="<?php if (isset($image) AND !empty($image)){echo $image;}?>">
  			<label for="floatingInput">Lien de l'image :</label>
		</div>
		<p style="color:white;">Liens de type : <strong>https://i.ibb.co/gyWhjkF/Cambre-id-2-2.jpg</strong></p>
        	
  <div class="col-12">
    <button type="submit" class="btn btn-primary" name="add">Ajouter la chambre.</button>
  </div>
  <br>
</form>
</div>
  <br>
</div>
<br>
<?php 

if(isset($message) && !empty($message)){
    echo $message;
}
?>
