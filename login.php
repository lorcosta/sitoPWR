<?php
  $session=true;
  if(session_status()===PHP_SESSION_DISABLED){
    $session=false;
  }elseif(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
    //effettuare controlli di sessione
  }
  if($session){
    if(!isset($_SESSION["user"])){
      $_SESSION["user"]="ANONIMO";
    }
    if(!isset($_SESSION["saldo"])){
      $_SESSION["saldo"]=0.00;
    }
    if(!isset($_SESSION["logged"])){
      $_SESSION["logged"]=false;
    }
  }
  require_once "repetitiveScripts.php";//require per includere il file e scatenare eccezione fatale nel caso non venga incluso, once controlla che venga incluso una sola volta
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//IT"
  “http://www.w3.org/TR/html4/strict.dtd">
<html lang='it'>
  <head>
    <?php intestazioni('HOME') ?>
    <link rel="next" href="login.php">
  </head>
  <body>
    <div class="grid-container">
      <div class="theHeaderLeft">
        <?php myHeaderLeft(); ?>
      </div>
      <div class="theHeader">
        <?php  myHeader(); ?>
      </div>
      <div class="theHeaderRight">
        <?php myHeaderRight();?>
      </div>
      <div class="theMenu">
        <?php menu(); ?>
      </div>
      <div class="theMain">
        <form action="paga.php" method="post">
          <p>Username:</p>
          <div class="social">
            <img src="img/utente.png" alt="Icona utente">
            <input type="text" name="user" placeholder="Inserisci qui il tuo username" size="30">
          </div>
          <p>Password:</p>
          <div class="social">
            <img src="img/password.png" alt="Icona utente">
            <input type="password" name="password" placeholder="Inserisci qui la tua password" size="30">
          </div>
          <div class="bottoni">
            <input type="submit" name="ok" value="OK">
            <input type="reset" name="reset" value="PULISCI">
          </div>
        </form>
      </div>
      <div class="theFooterLeft">
        <?php footerLeft(); ?>
      </div>
      <div class="theFooter">
        <?php footer(); ?>
      </div>
      <div class="theFooterRight">
        <?php footerRight(); ?>
      </div>
    </div>
  </body>
</html>
