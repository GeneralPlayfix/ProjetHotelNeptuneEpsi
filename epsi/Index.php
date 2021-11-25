<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
?>
<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">

    <!-- CSS Reset -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">

    <!-- Milligram CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

    <title>Classe 3</title>
</head>
<body>
<a href=""></a>

<header>
    <nav class="container">
        <ul>
            <li><a href="/epsi/">Accueil</a></li>
            <li><a href="/epsi/?page=contact">Contact</a></li>
            <li><a href="/epsi/?page=about">À propos</a></li>
            <li><a href="/epsi/?page=bdd">Base de données</a></li>
            <li><a href="/epsi/?page=admin">Admin</a></li>
            <li><a href="/epsi/?page=file">file uploader</a></li>
            <li><a href="/epsi/?page=upload">upload</a></li>

        </ul>
    </nav>
</header>

<div class="container">
    <form action="" method="get">
<!--        <label for="inputName">La page de destination</label>-->
<!--        <input type="text" name="page" id="inputName">-->

        <label for="inputSelect">La page de destination</label>
        <select name="page" id="inputSelect">
            <option value="contact">Contact</option>
            <option value="about">À propos</option>
            <option value="database">Base de données</option>
            <option value="admin">Admin</option>
            <option value="upload">Upload</option>

        </select>
        <input type="submit" value="Envoyer">
    </form>
    <?php
            session_start();
    if (array_key_exists('firstnames', $_POST)){
        $firstname = $_POST['firstnames'];
        $_SESSION['firstname'] = $firstname ;
    }
    ?>
    <?php if (! array_key_exists('firstname', $_SESSION)) { ?>
        <form action="" method="post">
            <input type="text" name="firstnames" id="">
            <input type="submit" value="Envoyer">
        </form>
    <?php } else { ?>
        <p>BONSOIR, <?php echo $_SESSION['firstname'];?></p>
    <?php }?>    
    <main>
        <?php
        /**
         * Inclût les pages en fonction de l'URL.
         */
        if (\array_key_exists('page', $_GET)) {

            $page = $_GET['page'];

            switch ($page) {
                case 'contact':
                    require './pages/contact.php';
                    break;
                    case 'upload':
                        require './pages/upload.php';
                        break;
                case 'about':
                    require  './pages/about.php';
                    break;
                case 'bdd':
                    require  './pages/bdd.php';
                    break;
                case 'admin':
                    require './pages/admin.php';
                    break;
                case 'page';
                    require './pages/page.php';
                    break;    
                case 'file';
                    require './pages/file.php';
                    break;    
                default:
                    require './pages/Home_page.php';
            }

//            if ($page === "contact") {
//                require './pages/contact.php';
//            } elseif ($page === "about") {
//                require './pages/about.php';
//            } elseif ($page === "array") {
//                require './pages/about.php';
//            } else {
//                require './pages/homepage.php';
//            }
        }
        ?>
    </main>
</div>

<footer>
    <br>
    <?php echo "<br>"; ?>
</footer>

</body>
</html>