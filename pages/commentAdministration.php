

<?php 


$sql = $dbh->prepare('SELECT * FROM comments, clients WHERE comments.Client_id = clients.id');
$sql->execute();
$commentaires= $sql->fetchAll();


if (array_key_exists('delete', $_POST)) {
    $commentId = $_POST['delete'];
    // Injection SQL possible avec : 3; DROP DATABASE epsi
    // $statement = $dbh->prepare("DELETE FROM `epsi`.`contact` WHERE id = $contactId;");
    $statement = $dbh->prepare("DELETE FROM `Neptune`.`comments` WHERE Com_id = ?");
    $statement->execute(array($commentId));
    echo "<script type='text/javascript'>document.location.replace('./?page=commentAdmin');</script>";
    unset($_POST['delete']);
}
?>
<br>
<br>
<table class="table table-dark table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Commentaire</th>
      <th scope="col">Client</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($commentaires as $commentaire):?>
    <tr>
      <td scope="row"><?php echo $commentaire['Com_id']?></td>
      <td scope="row"><?php echo $commentaire['Commentaire']?></td>
      <td scope="row"><?php echo $commentaire['nom']?> <?php echo $commentaire['prenom']?></td>
			<td>
				<form action="" method="post">
					<input type="hidden" name="delete" value="<?php echo $commentaire['Com_id']?>"><input class="btn btn-primary" type="submit" value="Effacer">
				</form>
			</td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>





