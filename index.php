
<!DOCTYPE html>
<html lang="en">
 <head>
        <script src="https://ajax.googleapis.com/ajax/libs/d3js/6.3.1/d3.min.js"></script>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    	    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		
		<link rel="stylesheet" type="text/css" href="./css/index.css">
		
        <title>Neptune</title>
        <?php session_start();
        ?>
    </head>
<body>
	 <header>
		<nav class="navbar navbar-expand-sm navbar-custom" id="transparent">
			<div class="container-fluid">
				<a class="navbar-brand active" href="">Neptune</a>
				<button class="navbar-toggler" type="button"
					data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
					aria-controls="navbarNavDropdown" aria-expanded="false"
					aria-label="Toggle navigation">
					<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-justify" viewBox="0 0 16 16">
  						<path fill-rule="evenodd" d="M2 12.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
					</svg>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavDropdown">
					<ul class="navbar-nav">
					
						<li class="nav-item"><a class="nav-link active"
							aria-current="page" href="./?page=home_page">Accueil</a></li>


                        <!-- Block -->
						<li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
							href="#" id="navbarDropdownMenuLink" role="button"
							data-bs-toggle="dropdown" aria-expanded="false">Hotel</a>
							<ul class="dropdown-menu"
								aria-labelledby="navbarDropdownMenuLink">
								<li><a class="dropdown-item" href="./?page=room">Chambres</a></li>
								<li><a class="dropdown-item" href="./?page=contact">Nous contacter</a></li>								
							</ul>
						</li>
						<!-- Fin Block -->
						
						
							<li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
							href="#" id="navbarDropdownMenuLink" role="button"
							data-bs-toggle="dropdown" aria-expanded="false"> <?php if(isset($_SESSION['prenom'])){echo $_SESSION['prenom'];}else{echo 'Mon Compte';}?></a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<?php 
							if(!isset($_SESSION["prenom"])){
							     ?>
        							<li><a class="dropdown-item" href="./?page=connexion">Se connecter</a></li>
        							<li><a class="dropdown-item" href="./?page=inscription">S'inscrire</a></li>
							     <?php    
							}else{
								if(isset($_SESSION['admin'])){
								    if($_SESSION['admin']){
								        ?>
								        <li><a class="dropdown-item" href="./?page=admin" style="color:rgb(255,100,100);"> Administration Client</a></li>
								        <li><a class="dropdown-item" href="./?page=roomManagement" style="color:rgb(255,100,100);">Administration Chambre</a></li>
								        <li><a class="dropdown-item" href="./?page=commentAdmin" style="color:rgb(255,100,100);">Administration Commentaire</a></li>
								        <?php 
								    }
								}
								?>
        							<li><a class="dropdown-item" href="./?page=memberarea"> Mon Compte</a></li>
        							<li><a class="dropdown-item" href="./?page=deconnexion"> Deconnexion</a></li>
								<?php 
							}
							?>
						</ul>
					</li>
					</ul>
			
				</div>
			</div>
		</nav>
	</header>
	<main>
		<?php
		
        /*
         * Inclût les pages en fonction de l'URL.
         */
		
		if (!\array_key_exists('page', $_GET)){
		    require './pages/home_page.php';
		}else{

            $page = $_GET['page'];
            
            require './pages/bdd.php';
            
            switch ($page) {
                case 'inscription':
                    require './pages/inscription.php';
                    break;
                case 'connexion':
                    require './pages/connexion.php';
                    break;
                case 'room':
                    require './pages/room.php';
                    break;
                case 'commentAdmin':
                    require './pages/commentAdministration.php';
                    break;
                case 'roomManagement':
                    require './pages/roomManagement.php';
                    break;
                case 'contact': 
                    require './pages/contact.php';
                    break;
                case 'reservation':
                    require './pages/page_reservation.php';
                    break;
                case 'bdd':
                    require './pages/bdd.php';
                    break;
                case 'memberarea':
                    require './pages/membearea.php';
				    break;
                case 'admin':
                    require './pages/Admin_page.php';
                    break;
                case 'deconnexion':
                    require './pages/deconnexion.php';
                    break;
                case 'addRoom':
                    require './pages/addRoomPage.php';
                    break;
                default:
                    require './pages/home_page.php';
            }
        }
        ?>
	</main>

</body>
</html>

