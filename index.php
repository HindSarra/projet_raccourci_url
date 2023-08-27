<!-- is sending  form -->
<?php
//Shortened URL received
if (isset($_GET['q'])) {

  $shortcut = htmlspecialchars($_GET['q']);

  $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', '');

  $request = $bdd->prepare('SELECT COUNT (*) FROM links where shortcut=?');
  $request->execute(array($shortcut));

  while ($result = $request->fetch()) {
    if ($result['x'] != 1) {
      header('location:index.php?error=true&message= Adress url non connue');
      exit();
    }
  }
  $request = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
  $request->execute(array($shortcut));

  while ($result = $request->fetch()) {

    header('location: ' . $result['url']);
    exit();
  }
}



if (isset($_POST['url'])) {

  //variable
  $url = $_POST['url'];


  //verification
  //function filter var prends 2 params  la var et filter_valid 

  if (!filter_var($url, FILTER_VALIDATE_URL)) {
    header('location:index.php?error=true&message=Adresse url non valide');

    exit();
  }
  //shortcut
  $shortcut = crypt($url, rand());

  //has been
  $bdd = new PDO('mysql:host=localhost;dbname=bitly;chartset=utf8', 'root', '');

  $request = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url=?');
  $request->execute(array($url));

  while ($result = $request->fetch()) {
    if ($result['x'] != 0) {
      header('location:index.php?error=true&message=Adresse dejà raccourcie');
    }
  }
  //sending 
  $request = $bdd->prepare('INSERT INTO links(url,shortcut) VALUES (?,?)');
  $request->execute(array($url, $shortcut));

  header('location:index.php?short=' . $shortcut);
  exit();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Raccoucisseur d'URL express</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel='icon' type="image/png" href="pictures/favico.png">
</head>

<body>
  <section id="hello">
    <div class="container">

      <header>
        <img src="pictures/logo.png" alt="logo" id="logo">
      </header>
      <h1> Une URL longue ? raccoucissez-là ? </h1>
      <h2>Largement meuilleur et plus court que les autres.</h2>
      <form action="index.php" method="post">
        <input type="url" name="url" id="url" placeholder="Collez un lien à raccourcir ">
        <input type="submit" value="Raccourcir">
      </form>
    </div>

    <?php
    if (isset($_GET['error']) && isset($_GET['message'])) { ?>

      <div class="center">
        <div id="result">
          <b><?php echo htmlspecialchars($_GET['message']); ?></b>
        </div>
      </div>
    <?php } elseif (isset($_GET['short'])) { ?>

      <div class="center">
        <div id="result">
          <b>URL Raccourcie:</b> http://localhost/?q=<?php echo htmlspecialchars($_GET['short']); ?>
        </div>
      </div>
    <?php } ?>

    ?>
  </section>
  <section id="brand">
    <div class="container">
      <h3>Ces marques nous font confiance</h3>
      <div class="picture">
        <img src="pictures/1.png" alt="brand-1" class="picture">
        <img src="pictures/2.png" alt="brand-2" class="picture">
        <img src="pictures/3.png" alt="brand-3" class="picture">
        <img src="pictures/4.png" alt="brand-4" class="picture">
      </div>
    </div>
  </section>
  <footer>
    <img src="pictures/logo2.png" id="logo" alt="logo-2">
    <br>2018@Bitly<br>
    <a href="">Contact</a>- <a href="">A propos</a>

  </footer>

</body>

</html>