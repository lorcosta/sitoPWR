<?php
$author="Lorenzo Costa";
function intestazioni($title){
  global $author;//per rendere visibile la variabile all'interno della function
  echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';//w3c raccomanda di inserire l'indicazione del charset entro 1024 byte dall'inizio del documento
  echo '<link rel="stylesheet" href="default.css" type="text/css" id="stylesheet">';
  echo '<link rel="icon" href="img/favicon.ico" type="image/png">';
  echo '<meta name="Author" content="'.$author.'">';
  echo '<meta name="keywords" lang="IT" content="pagamenti">';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
  echo '<meta http-equiv="cache-control" content="no-cache">';//non permetto caching delle pagine visto che sono dinamiche
  echo '<title>'.$title.'</title>';
  //aggiungere in ogni singola pagina la consultazione logica del sito web
  //<link rel="prev" href="previous.php">
  //<link rel="next" href="next.php">
}
function avvisoJS(){
  echo '<noscript>ATTENZIONE! Questo sito usa JavaScript per l\'aggiornamento dei dati in tempo reale.
  Il tuo browser non supporta JavaScript (oppure ne è stata disabilitata l\'esecuzione) e quindi tale
  funzionalità non sarà disponibile.</noscript>';
}
function myHeaderLeft(){
  echo '<a href="home.php"><img src="img/logo.jpg" alt="Logo: Money Transfer"></a>';
}
function myHeader(){
  echo '<p>';
  echo 'MONEY TRANSFER: i tuoi pagamenti digitali sicuri';
  echo '</p>';
}
function myHeaderRight(){
  echo 'User: '.strtoupper($_SESSION["user"]);
  $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','pagamenti');
  $query="SELECT saldo FROM usr WHERE nick=?";//rimuove $importo da chi effettua il pagamento-->$_SESSION["user"]=$mittente
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,"s",$_SESSION["user"]);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt,$saldo);
  mysqli_stmt_fetch($stmt);
  //rilascio la memoria associata al result set
  mysqli_stmt_free_result($stmt);
  mysqli_stmt_close($stmt);
  $_SESSION["saldo"]=intval($saldo)/100;
  echo '<br>Saldo: '. number_format($_SESSION["saldo"], 2, ',', '') . " &euro;";
}
function menu(){
  echo '  <ul id="menu">';
  echo '    <li><a href="home.php">HOME</a></li>';
  if($_SESSION["logged"]==false){
    echo '    <li><a href="login.php">LOGIN</a></li>';
  }else{
    echo '    <li><a href="javascript:void(0);">LOGIN</a></li>';// this will maintain your css for link like others but when you click it won't redirect.
  }
  echo '    <li><a href="paga.php">PAGA</a></li>';
  echo '    <li><a href="log.php">LOG</a></li>';
  if($_SESSION["logged"]==true){
    echo '    <li><a href="logout.php">LOGOUT</a></li>';
  }else{
    echo '    <li><a href="javascript:void(0);">LOGOUT</a></li>';
  }
  echo '  </ul>';
}
function footerLeft(){
  echo '<p>Ti trovi sulla pagina: '.basename($_SERVER['PHP_SELF']).'</p>';//Given a string containing the path to a file or directory, this function will return the trailing name component.

}
function footer(){
  global $author;//per rendere visibile la variabile all'interno della function
  echo '<p>Autore: '.$author;
  echo ' - Contatti: <a href="mailto:s249739@studenti.polito.it">s249739@studenti.polito.it</a></p>';
}
function footerRight(){
  echo '<div class="social">';
  echo '  <a href="http://validator.w3.org/check?uri=referer">';
  echo '  <img style="border:0;width:88px;height:31px" src="img/valid-html401.png" alt="Valid HTML 4.01 Transitional"></a>';
  echo '  <a href="http://jigsaw.w3.org/css-validator/check/referer">';
  echo '  <img style="border:0;width:88px;height:31px" src="img/vcss.png" alt="CSS Valido!"/></a>';
  echo '  </div>';
}
 ?>
