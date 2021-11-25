<?php
if (!isset($_SESSION['admin'])) {
    echo "<script type='text/javascript'>document.location.replace('./?page=home_page');</script>";
} else {
    if (!isset($_GET['modification'])) {
?>
        <?php



        /**
         * R�cup�ration des utilisateurs
         */
        $statement = $dbh->prepare('SELECT * FROM `Neptune`.`clients`;');
        $statement->execute(); // execute le SQL dans la base de donn�es (MySQL / MariaDB)
        $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <?php

        /**
         * V�rifier que l'utilisateur a post� un formulaire de suppression
         *
         * @param id de du contact � supprimer
         */
        if (array_key_exists('delete', $_POST)) {
            $contactId = $_POST['delete'];
            // Injection SQL possible avec : 3; DROP DATABASE epsi
            // $statement = $dbh->prepare("DELETE FROM `epsi`.`contact` WHERE id = $contactId;");
            $statement = $dbh->prepare("DELETE FROM `Neptune`.`clients` WHERE id = :id;");
            $statement->bindParam(':id', $contactId, PDO::PARAM_INT);
            $statement->execute();
            header("Refresh:0");
            unset($_POST['delete']);
        }

        //search bar

        if (isset($_POST['search']) and !empty($_POST['search'])) {
            $search = htmlspecialchars($_POST['search']);
            $statement = $dbh->prepare("SELECT * FROM clients WHERE UPPER(nom) LIKE ('%$search%') OR UPPER(prenom) LIKE UPPER('%$search%') or UPPER(ville) LIKE UPPER('%$search%') OR UPPER(email) LIKE UPPER('%$search%')");
            $statement->execute();
            $clients = $statement->fetchAll();
            $clientsCount = $statement->rowCount();
            if ($clientsCount === 0) {
                $message = "Il n'y a pas de résultats à votre recherche";
            }
        }

        ?>
        <br>
        <form action="" method="post" class="searchBar">
            <div class="wrap">
                <div class="search">
                    <input type="text" class="searchTerm" placeholder="Rechercher..." name="search">
                    <button type="submit" class="searchButton">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>


        <br>

        <?php if ((isset($_POST['search']) and !empty($_POST['search']) and isset($search) and !empty($search) and $clientsCount != 0)
            || !(isset($_POST['search']) and !empty($_POST['search']))
        ) { ?>

            <a href="#down" Id="up" style="margin: 1%;"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-down-square-fill" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5a.5.5 0 0 1 1 0z" />
                </svg></a>
            <table class="table table-dark table-striped" style="margin-top: 2%">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Civilité</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Adresse</th>
                        <th scope="col">CodePostal</th>
                        <th scope="col">Ville</th>
                        <th scope="col">Mail</th>
                        <th scope="col">Modification</th>
                        <th scope="col">Suppression</th>

                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (!empty($clients)) {
                        foreach ($clients as $client) { ?>
                            <tr>
                                <td><?php echo $client['id']; ?></td>
                                <td><?php echo $client['civilite']; ?></td>
                                <td><?php echo $client['nom']; ?></td>
                                <td><?php echo $client['prenom']; ?></td>
                                <td><?php echo $client['adresse']; ?></td>
                                <td><?php echo $client['codePostal']; ?></td>
                                <td><?php echo $client['ville']; ?></td>
                                <td><?php echo $client['email']; ?></td>
                                <td>
                                    <form action="" method="post">
                                        <a href="./?page=admin&modification=<?php echo $client['id']; ?>" class="btn btn-primary">Modifier</a>
                                    </form>
                                </td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="delete" value="<?php echo $client['id']; ?>"> <input class="btn btn-primary" type="submit" value="Effacer">
                                    </form>
                                </td>

                            </tr>
                    <?php }
                    } ?>


                </tbody>
            </table>
            <a href="#up" id="down" style="margin: 1%; float: right;"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-up-square-fill" viewBox="0 0 16 16">
                    <path d="M2 16a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2zm6.5-4.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 1 0z" />
                </svg></a>
            <div style="height: 10px;"></div>
        <?php
        } else {
            if (isset($message)) {
                echo '<p class="searchError">' . $message . '</p> ';
            }
        }
    } else {
        $statement = $dbh->prepare('SELECT * FROM clients where id = :id', array(
            PDO::PARAM_STR,
            PDO::PARAM_STR
        ));
        $statement->execute(array(
            ':id' => $_GET['modification']
        ));
        $clients = $statement->fetchAll();
        //var_dump($clients);

        if (sizeof($clients) == 1) {
            $client = $clients[0];
            /********************* modification du prénom ****************************/
            if (isset($_POST['Firstname']) and !empty($_POST['Firstname']) and $_POST['Firstname'] != $client['prenom']) {
                //cr�ation et s�curisation de la varianle firstname
                $Firstname = htmlspecialchars($_POST['Firstname']);
                $changeFirstname = $dbh->prepare("UPDATE clients SET prenom = ? WHERE id = ?");
                $changeFirstname->execute(array($Firstname, $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }
            /********************* modification du nom****************************/
            if (isset($_POST['Lastname']) and !empty($_POST['Lastname']) and $_POST['Lastname'] != $client['nom']) {
                $Lastname = htmlspecialchars($_POST['Lastname']);
                $changeLastname = $dbh->prepare("UPDATE clients SET nom = ? WHERE id = ?");
                $changeLastname->execute(array($Lastname, $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }

            /************************** modification email***************************/
            if (isset($_POST['Email']) and !empty($_POST['Email']) and $_POST['Email'] != $client['email']) {
                $email = htmlspecialchars($_POST['Email']);
                $changeEmail = $dbh->prepare("UPDATE clients SET email = ? WHERE id = ?");
                $changeEmail->execute(array($email, $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }
            /********************************* modification adresse ****************************/

            if (isset($_POST['Address']) and !empty($_POST['Address']) and $_POST['Address'] != $client['adresse']) {
                $Address = htmlspecialchars($_POST['Address']);
                $changeAddress = $dbh->prepare("UPDATE clients SET adresse = ? WHERE id = ?");
                $changeAddress->execute(array($Address,  $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }
            /***********************************modification ville *************************************************/
            if (isset($_POST['City']) and !empty($_POST['City']) and $_POST['City'] != $client['ville']) {
                $City = htmlspecialchars($_POST['City']);
                $changeCity = $dbh->prepare("UPDATE clients SET ville = ? WHERE id = ?");
                $changeCity->execute(array($City, $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }
            /************************************* Modification code postal********************************************/
            if (isset($_POST['PostalCode']) and !empty($_POST['PostalCode']) and $_POST['PostalCode'] != $client['codePostal']) {
                $PostalCode = htmlspecialchars($_POST['PostalCode']);
                $changeCity = $dbh->prepare("UPDATE clients SET codePostal = ? WHERE id = ?");
                $changeCity->execute(array($PostalCode, $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }
            /***************************************Modification civilite**************************************/
            if (isset($_POST['Civility']) and !empty($_POST['Civility']) and $_POST['Civility'] != $client['civilite']) {
                $civilite = htmlspecialchars($_POST['Civility']);
                $changeCivilite = $dbh->prepare("UPDATE clients SET civilite = ? WHERE id = ?");
                $changeCivilite->execute(array($civilite, $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }
            /***************************************Modification pays**************************************/
            if (isset($_POST['Country']) and !empty($_POST['Country']) and $_POST['Country'] != $client['pays_id']) {
                $Country = intval($_POST['Country']);
                $changeCivilite = $dbh->prepare("UPDATE clients SET pays_id = ? WHERE id = ?");
                $changeCivilite->execute(array($Country, $client['id']));
                echo "<script type='text/javascript'>document.location.replace('./?page=admin&modification=" . $_GET['modification'] . "') ;</script>";
            }
        ?>

            <div class="inscription">

                <div style="display: flex; justify-content: center; margin-top: 3%;">
                    <form class="row g-2 " method="post" style="width: 90%;">
                        <div class="col-md-6">
                            <label for="" class="form-label">Prénom :</label> <input type="text" class="form-control" id="Firstname" name="Firstname" value="<?php echo $client['prenom']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword4" class="form-label">Nom :</label> <input type="text" class="form-control" id="Password" name="Lastname" value="<?php echo $client['nom'];; ?>">
                        </div>
                        <div class="col-w">
                            <label for="inputEmail4" class="form-label">Email :</label> <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $client['email']; ?>">
                        </div>
                        <div class="col-12">
                            <label for="inputAddress" class="form-label">Adresse :</label> <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St" name="Address" value="<?php echo $client['adresse']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity" class="form-label">Ville</label> <input type="text" class="form-control" id="inputCity" name="City" value="<?php echo $client['ville']; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="inputState" class="form-label">Pays</label> <select id="inputState" class="form-select" name="Country" itemid="<?php echo $client['pays_id']; ?>">
                                <?php $query = $dbh->prepare("SELECT * FROM pays");
                                $query->execute();
                                $pays = $query->fetchAll();
                                foreach ($pays as $nation) {
                                ?>
                                    <option value="<?php echo $nation['id']; ?>" <?php echo (isset($client['pays_id']) && $client['pays_id'] === $nation['id']) ? "selected" : " " ?>> <?php echo $nation['nom']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputZip" class="form-label">Code Postal</label> <input type="text" class="form-control" id="inputZip" name="PostalCode" value="<?php echo $client['codePostal']; ?>">
                        </div>

                        <div class="col-w">
                            <label for="inputState" class="form-label">Cicilité</label> <select id="inputState" class="form-select" name="Civility">
                                <option <?php echo (isset($client['civilite']) && $client['civilite'] === "Monsieur") ? "selected" : " " ?>>Monsieur</option>
                                <option <?php echo (isset($client['civilite']) && $client['civilite'] === "Madame") ?   "selected" : " " ?>>Madame</option>
                                <option <?php echo (isset($client['civilite']) && $client['civilite'] === "Mademoiselle") ? "selected" : " " ?>>Mademoiselle</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Mettre à jour mon
                                profil</button>
                        </div>
                    </form>

                </div>
            </div>

<?php
        }
    }
}
?>