<!-- 1 - STRUCTURE DE BASE
     2 - LIER FEUILLE DE STYLE
     3 - AJOUTER UNE LIAISON VERS NOTRE PETIT FAVICON -->

     <?php
// IS RECEIVED SHORTCUT
if(isset($_GET['q0'])){
    //VARIABLE
    $shortcut = htmlspecialchars($_GET['q']);

    // IS A SHORTCUT ?
    $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=itf8', 'root', 'prout');
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));

    while($result = $req->fetch()){
        if($result['x'] != 1){
            header('location: ../?error=true&message=Adresse url non connue');
            exit();
        }
    }

    // REDIRECTION 
    $req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
    $req->execute(array($shortcut));
    
    while($result = $req->fetch()){

        header('location: '.$result['url']);
        exit();

    }
}

// IS SENDING A FORM 
if(isset($_POST['url'])) {
    // VARIABLE
    $url = $_POST['url'];

    // VERIFICATION URL VALIDE
    if(!filter_var($url, FILTER_VALIDATE_URL)) { //https://www.php.net/manual/fr/function.filter-var.php
        // PAS UN LIEN
        header('location: ../?error=true&message=Adresse url non valide');
        exit();
    }

    // SHORTCUT HASHAGE SENS UNIQUE
    $shortcut = crypt($url, rand());

    // HAS BEEN ALREADY SEND ?
    $bdd = new PDO('
        mysql:host=localhost;dbname=bitly;charset=utf8',
        'root', 'prout');
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
    $req->execute(array($url));

    while($result = $req->fetch()){

        if($result['x'] != 0) {
            header('location: ../?error=true&message=Adresse déja raccourcie');
            exit();
        }

    }

    // SENDING
    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)');
    $req->execute(array($url, $shortcut));

    header('location: ../.short='.$shortcut);
    exit();




}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Raccourcisseur d'url express</title>
        <link rel="stylesheet"  type="text/css" href="/udemy/design/default.css">
        <link rel="icon" type="image/png" href="/udemy/pictures/favico.png">
    </head>
    <body>
        <!-- PRESENTATION -->
        <section id="hello">
            
            <!-- CONTAINER -->
            <div class="container" >
                
                <header>
                    <img src="/udemy/pictures/logo.png" alt="logo" id="logo">
                </header>

                <h1>Une url longue ? Raccourcissez-là</h1>
                <h2>Largement meilleur et plus court que les autres</h2>

                <!-- FORM -->
                <form method="post" action="../udemy/index.php">
                    <input type="url" name="url" placeholder="Coller un lien à raccourcir">
                    <input type="submit" value="Raccourcir">
                </form>

                <?php if(isset($_GET['error']) && isset($_GET['message'])) { ?>
                    <div class="center" >
                        <div id="result" >
                            <b><?php echo htmlspecialchars($_GET['message']); ?></b>
                        </div>
                    </div>
                <?php } else if(isset($_GET['short'])) {
                    ?>
                    <div class="center" >
                        <div id="result" >
                            <b>URL RACCOURCIE :</b>
                            http://localhost/?q=
                            <?php echo htmlspecialchars($_GET['short']); ?>
                        </div>
                    </div>
                    <?php } ?>

            </div>  

        </section>

        <!-- BRANDS -->
        <section id="brands" >

            <!-- CONTAINER -->
            <div class="container" >
                <h3>Ces marques nous font confiance</h3>
                <img src="/udemy/pictures/1.png" alt="1" class="picture">
                <img src="/udemy/pictures/2.png" alt="2" class="picture">
                <img src="/udemy/pictures/3.png" alt="3" class="picture">
                <img src="/udemy/pictures/4.png" alt="4" class="picture">
            </div>

        </section>

        <!-- FOOTER -->
        <footer>
            <img src="/udemy/pictures/logo2.png" alt="logo" id="logo" >
            <br>
            2022 © Bitly <br>
            <a href="#">Contact</a> - <a href="#">A propos</a>
        </footer>
    </body>
</html>