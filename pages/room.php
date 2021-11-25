<?php
if (!isset($_GET['viewroom'])) {
?>
    <br>
    <form class="d-flex" id="roomSearch" method="post">

        <div style="display: flex; justify-content: center; flex-direction: column; width: 100%;">

            <div class="form-check form-check-inline">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="Order[]" id="inlineRadio1" value="priceDown"> <label class="form-check-label" for="inlineRadio1"> Prix décroissant </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="Order[]" id="inlineRadio2" value="priceUp"> <label class="form-check-label" for="inlineRadio2">Prix croissant</label>
                </div>
            </div>
            <div class="form-check form-check-inline">
                <label>Capacité de la chambre : <span style="opacity: 0;">a</span> <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="AddFilter[]" value="bed2"> <label class="form-check-label" for="inlineCheckbox1">2</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="AddFilter[]" value="bed3"> <label class="form-check-label" for="inlineCheckbox2">3</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="AddFilter[]" value="bed4"> <label class="form-check-label" for="inlineCheckbox3">4</label>
                    </div>

            </div>
            <div style="display: flex; flex-direction: row;">
                <label for="" style="margin-left: 3%;">Prix</label> <span style="opacity: 0;">a</span>
                <div class="mb-3">
                    <input type="number" class="form-control" id="exampleFormControlInput1" name="PriceMin" placeholder="Min">
                </div>
                <div class="mb-3">
                    <input type="number" class="form-control" id="exampleFormControlInput1" name="PriceMax" placeholder="Max">
                </div>
            </div>
            <br>
            <button class="btn btn-primary" type="submit" id="search">Recherche</button>
        </div>
    </form>

    <?php


    //Requete par défaut si aucun critère n'est sélectionné
    $user1 = $dbh->prepare('
SELECT chambres.id, capacite, exposition, douche, etage, tarif_id, description, Liens, prix
FROM chambres, tarifs 
WHERE tarifs.id = chambres.tarif_id ORDER BY id
', array(
        PDO::PARAM_STR,
        PDO::PARAM_STR
    ));
    $user1->execute();
    $response = $user1->fetchAll();

    // Si il y as des critères changeant l'ordre des chambres, remplacer la requette de base par un pré triée
    if (isset($_POST['Order'])) {
        if (array_search("ratingUp", $_POST['Order']) !== FALSE) {

            // TODO
        } else if (array_search("ratingDown", $_POST['Order']) !== FALSE) {

            // TODO
        } else if (array_search("priceUp", $_POST['Order']) !== FALSE) {
            $user1 = $dbh->prepare('
SELECT chambres.id, capacite, exposition, douche, etage, tarif_id, description, Liens, prix
FROM chambres, tarifs 
WHERE chambres.tarif_id = tarifs.id ORDER BY prix ASC
', array(
                PDO::PARAM_STR,
                PDO::PARAM_STR
            ));
            $user1->execute();
            $response = $user1->fetchAll();
        } else if (array_search("priceDown", $_POST['Order']) !== FALSE) {
            $user1 = $dbh->prepare('
SELECT chambres.id, capacite, exposition, douche, etage, tarif_id, description, Liens, prix
FROM chambres, tarifs
WHERE chambres.tarif_id = tarifs.id ORDER BY prix DESC
', array(
                PDO::PARAM_STR,
                PDO::PARAM_STR
            ));
            $user1->execute();
            $response = $user1->fetchAll();
        }
    }


    //On récupére les tarifs pour la sélection des prix
    $user2 = $dbh->prepare('
SELECT *
FROM tarifs
', array(
        PDO::PARAM_STR,
        PDO::PARAM_STR
    ));

    $user2->execute();
    $tarifs = $user2->fetchAll();

    // var_dump($_POST);

    if (!empty($response)) {
        // TODO filter out from research

        $chambres = array();

        if (isset($_POST['AddFilter'])) {

            // Additive loop
            if (isset($_POST['AddFilter'])) {
                $addFilters = array();
                $addFilters = $_POST['AddFilter'];
                foreach ($addFilters as $addFilter) {
                    foreach ($response as $room) {
                        if (($addFilter == "bed2" && $room['capacite'] == 2) || ($addFilter == "bed3" && $room['capacite'] == 3) || ($addFilter == "bed4" && $room['capacite'] == 4))
                        // Add other AddFilters Here
                        {
                            // Prevent duplicates
                            if (!(array_search($room, $chambres) !== FALSE)) {
                                array_push($chambres, $room);
                            }
                        }
                    }
                }
            }
        } else {
            $chambres = $response;
            // var_dump($chambres);
        }

        // Multiplicative loop

        foreach ($response as $room) {
            if (isset($_POST['MultFilter'])) {
                $multFilters = array();
                $multFilters = $_POST['MultFilter'];
                foreach ($multFilters as $filter) {

                    // Add multfilters here
                }
            }
            // Out of the check of multiplicative loop as it has a ifferent name
            if ((isset($_POST['PriceMin']) && $_POST['PriceMin'] != "" && $tarifs[$room['tarif_id'] - 1]['prix'] < doubleval($_POST['PriceMin'])) || (isset($_POST['PriceMax']) && $_POST['PriceMax'] != "" && $tarifs[$room['tarif_id'] - 1]['prix'] > doubleval($_POST['PriceMax']))) {
                // remove room if requirements not met
                if (($key = array_search($room, $chambres)) !== FALSE) {
                    unset($chambres[$key]);
                }
            }
        }



        //G�n�re un petit onglet par chambre dans la liste filtrée par les critères (ou non)
        foreach ($chambres as $row) {
            $capacite = $row['capacite'];
            $exposition = $row['exposition'];
            $douche = $row['douche'];
            $etage = $row['etage'];
            $tarif = $tarifs[$row['tarif_id'] - 1]['prix'];
            $description = $row['description'];
            $image = $row['Liens'];
    ?>

            <div class="card mb-3" style="width: 98%;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?php echo $image; ?>" class="card-img-top" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Description de la chambre</h5>
                            <p class="card-text"> <?php echo $description; ?></p>
                            <h5>Place</h5>
                            <p class="card-text"> <?php echo $capacite ?> </p>
                            <h5>Prix</h5>
                            <p class="card-text"> <?php echo $tarif . " Euros"; ?></p>

                            <a href="./?page=room&viewroom=<?php echo $row['id']; ?>" class="btn btn-primary">Reserver maintenant</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    }
} else {

    $user1 = $dbh->prepare('SELECT * FROM chambres where id = :id', array(
        PDO::PARAM_STR,
        PDO::PARAM_STR
    ));
    $user1->execute(array(
        ':id' => $_GET['viewroom']
    ));
    $response = $user1->fetchAll();

    if (sizeof($response) == 1) {

        $user2 = $dbh->prepare('SELECT * FROM tarifs', array(
            PDO::PARAM_STR,
            PDO::PARAM_STR
        ));
        $user2->execute();
        $tarifs = $user2->fetchAll();

        $response = $response[0];

        $capacite = $response['capacite'];
        $exposition = $response['exposition'];
        $douche = $response['douche'];
        $etage = $response['etage'];
        $tarif = $tarifs[$response['tarif_id'] - 1]['prix'];
        $description = $response['description'];
        $image = $response['Liens'];

        ?>


        <div class="card mb-3" style="width: 98%;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?php echo $image ?>" class="card-img-top" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Description de la chambre</h5>
                        <p class="card-text"> <?php echo $description; ?></p>
                        <h5>Capacité</h5>
                        <p class="card-text"> <?php echo $capacite ?> </p>
                        <div style="display: flex;flex-direction: row;">
                        <h5> douche : </h5> <span style="opacity: 0;">a</span> <p class="card-text"><?php echo (isset($douche)&& $douche ===1) ? "oui": "non" ?></p>
                        <span style="opacity: 0;">aaa</span>
                        <h5>Exposition : </h5><span style="opacity: 0;">a</span> 
                        <p class="card-text"> <?php echo $exposition ?> </p>
                        </div>
                        <h5>
                        Etage :
                        </h5>
                        <p class="card-text"> <?php echo $etage ?> </p>
                        <h5>Prix</h5>
                        <p class="card-text"> <?php echo $tarif . " Euros"; ?> </p>

                    </div>
                </div>
            </div>
        </div>
        <?php
        $error = " ";
        // réservation qui s'envoient en base de donnée
        if (isset($_POST['envoyer'])) {
            // var_dump($_POST);
            if (!empty($_POST['start']) and !empty($_POST['end'])) {
                $start = $_POST['start'];
                $start =date_create(date_create($start)->format("Y-m-d")." 00:00:01");
                $end = $_POST['end'];
                $end = date_create(date_create($end)->format("Y-m-d")." 00:00:01");

                $query = $dbh->prepare('SELECT * FROM planning WHERE chambre_id = ?');
                $query->execute(array(
                    $response['id']
                ));
                $isNotReserved = $query->fetchAll();

                $reserve = FALSE;
                $acompte = FALSE;

                foreach ($isNotReserved as $reservation) {
                    if (new DateTime($reservation['jour']) >= $start and new DateTime($reservation['jour']) <= $end) {
                        $reserve = TRUE;
                        if (($reservation['acompte'] == TRUE || $reservation['paye'] == TRUE)) {
                            $acompte = TRUE;
                        }
                    }
                }
                if ($reserve != TRUE || ($acompte == FALSE && ((isset($_POST['checkboxAcompte']) && $_POST['checkboxAcompte'] == 1) || (isset($_POST['checkboxPayer']) && $_POST['checkboxPayer'] == 1)))) { // TODO ou qu'il n'y as pas d'accompte et qu'on souhaite en faire un
                    if ($start < $end) {
                        for ($i = $start; $i <= $end; $i->add(new DateInterval('P1D'))) {
                            $sql = $dbh->prepare("INSERT INTO planning (chambre_id, jour, paye, client_id, acompte) VALUES (?, ?, ?, ?, ?);");
                            $sql->execute(array(
                                $_GET['viewroom'], 
                                $i->format('Y-m-d')." 00:00:00",
                                isset($_POST['checkboxPayer']) ? $_POST['checkboxPayer'] : 0,
                                $_SESSION['id'],
                                isset($_POST['checkboxAcompte']) ? $_POST['checkboxAcompte'] : 0
                            ));
                        }
                        $message = "Votre réservations à bien été prise en compte";
                    } else {
                        $error = "La date de début ne peut être plus grande que la date de fin";
                    }
                } else {
                    if ($acompte == TRUE) {
                        $error = "La chambre est déjà réservée et prépayée à cette date là";
                    } else {
                        $error = "La chambre est déjà réservée à cette date la, elle peut toutefois etre réservée avec un acompte";
                    }
                }
            }
        }
        ?>
        <?php if (isset($_SESSION) and !empty($_SESSION)) {  ?>
            <div class="section">
                <br>
                <form action="" method="post">
                    <div class="form-group row">
                        <label for="example-date-input" class="col-2 col-form-label">Début</label>
                        <div class="col-10">
                            <input class="form-control" name="start" type="datetime-local" value="" id="example-date-input">
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="example-date-input" class="col-2 col-form-label">Fin</label>
                        <div class="col-10">
                            <input class="form-control" name="end" type="datetime-local" value="" id="example-date-input">
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="checkboxPayer" type="checkbox" id="inlineCheckbox1" value="1"> <label class="form-check-label" for="inlineCheckbox1"> Payer </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="checkboxAcompte" id="inlineCheckbox2" value="1"> <label class="form-check-label" for="inlineCheckbox2"> Acompte</label>
                    </div>
                    <br>
                    <button type="submit" id="button" name="envoyer" class="btn btn-primary">Réserver</button>
                    <br> <br> <a href="#reserved">Voir les réservations</a> <br> <br>

                </form>
                <?php
                if (isset($error) and !empty($error)) {
                ?>
                    <p style="color: red;"><?php echo $error ?></p>
                <?php
                }
                if (isset($message) and !empty($message)) {
                ?>
                    <p style="color: white;"><?php echo $message ?></p>
                <?php } ?>
            </div>
        <?php } ?>
        <div id="Calendrier">
            <?php

            $user1 = $dbh->prepare('SELECT *FROM planning WHERE planning.chambre_id = ?', array(
                PDO::PARAM_STR,
                PDO::PARAM_STR
            ));
            $user1->execute(array(
                $response['id']
            ));
            $calendar = $user1->fetchAll();

            date_default_timezone_set('Europe/Paris');

            if (!isset($_GET['month']))
                $_GET['month'] = date("m") - 1;
            if (!isset($_GET['year']))
                $_GET['year'] = date("Y");
            $jour = 31;
            if ($_GET['month'] == 3 || $_GET['month'] == 5 || $_GET['month'] == 8 || $_GET['month'] == 10) {
                $jour = 30;
            } elseif ($_GET['month'] == 1) {
                $jour = (date('L', strtotime($_GET['year'] . "-01-01")) ? 29 : 28);
            }
            ?>

            <div class="global" id="reserved">
                <a href="" class="targetOf"> <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                    </svg>
                </a> <br>
                <div class="dessus">
                    <a href="./?page=room&viewroom=<?php
                                                    echo $_GET['viewroom'];
                                                    ?>&month=<?php
                    $month = $_GET['month'] - 1;
                    if ($month < 0)
                        echo 11;
                    else
                        echo $month;
                    ?>&year=<?php
                if ($month > 11)
                    echo $_GET['year'] + 1;
                else if ($month < 0)
                    echo $_GET['year'] - 1;
                else
                    echo $_GET['year'] ?>#reserved" class="left"> <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                            <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" />
                        </svg>
                    </a>
                    <p class="date">
                        <?php
                        echo date_create(1 . "-" . ($_GET['month'] + 1) . "-" . $_GET['year'])->format('F') . " " . $_GET['year'];
                        ?>
                    </p>
                    <a href="./?page=room&viewroom=<?php
                                                    echo $_GET['viewroom'];
                                                    ?>&month=<?php
                    $month = $_GET['month'] + 1;
                    if ($month > 11)
                        echo 0;
                    else
                        echo $month;
                    ?>&year=<?php
                if ($month > 11)
                    echo $_GET['year'] + 1;
                else if ($month < 0)
                    echo $_GET['year'] - 1;
                else
                    echo $_GET['year'] ?>#reserved" class="right"> <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-right-square-fill" viewBox="0 0 16 16">
                            <path d="M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v12zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1z" />
                        </svg>
                    </a>
                </div>
                <table>
                    <tr>
                        <?php
                        $rowcount = 7;
                        for ($i = 0; $i < $jour; $i++) {
                            $rowcount--;
                            if ($rowcount < 0) {
                        ?></tr>
                    <tr><?php
                                $rowcount += 7;
                            }

                            $daydate = date_create(($i + 1) . "-" . ($_GET['month'] + 1) . "-" . $_GET['year']);

                            $reserve = FALSE;
                            $accompte = FALSE;

                            foreach ($calendar as $reservation) {
                                if (date_create($reservation['jour'], date_timezone_get($daydate))->format("Y m d") == $daydate->format("Y m d")) {
                                    $reserve = TRUE;
                                    if (($reservation['acompte'] == TRUE || $reservation['paye'] == TRUE)) {
                                        $accompte = TRUE;
                                    }
                                }
                            }
                            // remplacer ce echo par ce que l'on veut mettre dans la case
                            // echo $daydate->format('d-m-Y') . " : " . $reserve . "<br>";
                            if ($reserve) {
                                if ($accompte) {
                                    echo "<th style=\"background-color: rgb(200, 60, 75);\"><p>" . ($i + 1) . "</p>";
                                } else {
                                    echo "<th style=\"background-color: rgb(250, 150, 10);\"><p>" . ($i + 1) . "</p>";
                                }
                            } else {
                                echo "<th style=\"background-color:rgb(75, 125, 200);\"><p>" . ($i + 1) . "</p>";
                            }

                            echo "</th>";
                        }

                        ?>
                    </tr>
                </table>
            </div>
        </div>
        </div>
        </div>
        </div>

        <?php
        // espace commentaire
        $room_id = $response['id'];
        $canComment = FALSE;

        foreach ($calendar as $reservation) {
            if (isset($_SESSION) and !empty($_SESSION)) {
                if ($reservation['client_id'] == $_SESSION['id']) {
                    $canComment = true;
                }
            }
        }
        if (isset($_POST['commentaire']) && (isset($_SESSION['admin']) || $canComment)) {
            if ($_SESSION['admin'] || $canComment) {
                $user2 = $dbh->prepare('INSERT INTO comments (room_id, client_id ,commentaire) VALUES (?, ?, ?)', array(
                    PDO::PARAM_STR,
                    PDO::PARAM_STR
                ));
                $user2->execute(array(
                    $room_id,
                    $_SESSION['id'],
                    $_POST['commentaire']
                ));
                $response = $user2->fetchAll();
            }
        }

        $user2 = $dbh->prepare('SELECT * FROM comments WHERE room_id = ? ORDER BY Com_id ASC', array(
            PDO::PARAM_STR,
            PDO::PARAM_STR
        ));
        $user2->execute(array(
            $room_id
        ));
        $comments = $user2->fetchAll();

        setlocale(LC_TIME, 'fra_fra');

        $user3 = $dbh->prepare('SELECT prenom, nom, id, Admin FROM clients', array(
            PDO::PARAM_STR,
            PDO::PARAM_STR
        ));
        $user3->execute();
        $clients = $user3->fetchAll();

        // var_dump($clients);


        if ($canComment || isset($_SESSION['admin'])) {
            if ($canComment || $_SESSION['admin']) {
        ?>


                <div style="width: 80%; margin-left: 10%; z-index: 0;">
                    <form method="post" style="display: flex; flex-direction: column;">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Commentaire" id="commentaire" name="commentaire" style="height: 100px; width: 1350px;"></textarea>
                            <label for="floatingTextarea2">Ajoutez votre commentaire</label>
                        </div>
                        <div style="margin-top: 1%; float: right;">
                            <button class="btn btn-primary" type="submit">Ajouter</button>
                        </div>
                    </form>
                </div>
                <br>

        <?php
            }
        }
        ?>
        <section class="espaceCommentaire">
            <h2>Espace Commentaire </h2>
        </section>
        <?php

        foreach ($comments as $comment) {

        ?>

            <div class="container space-vert" style="background-color: rgba(64, 93, 197, 0.657);">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="commentaire">
                            <div class="commentaire-texte col-lg-10">
                                <b style=" <?php

                                            foreach ($clients as $client) {
                                                if ($client['id'] == $comment['Client_id']) {
                                                    if ($client['Admin']) {
                                                        echo 'color:red;';
                                                    } else {
                                                        echo 'color:white;';
                                                    }
                                            ?>"><?php

                                                    echo $client['nom'] . " " . $client['prenom'];
                                                }
                                            }

                        ?> </b> Le <?php

                        $dateMySQL = $comment['date_actuelle'];
                        // objet DateTime correspondant :
                        $date = new DateTime($dateMySQL);

                        // affichage de la date au format francophone:
                        echo $date->format('d/m/Y H:i:s');
                        ?>
                                <p style="color: white;"><?php echo $comment['Commentaire'] ?></p>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
?>