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
<!DOCTYPE HTML>
<html lang='it'>
  <head>
    <?php intestazioni('HOME') ?>
    <link rel="next" href="login.php">
  </head>
  <body>
    <?php avvisoJS(); ?>
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
        <h2>Benvenuto in MONEY TRANSFER! Questa piattaforma ti permetterà di inviare e ricevere
            denaro in modo totalmente sicuro.</h2>
        <img class="wrapper" src="img/e-wallet.jpg" alt="Pagamenti digitali">
        <p>Se non sei ancora autenticato accedi subito alla pagina di <a href="login.php">LOGIN</a>
        e inserisci le tue credenziali, potrai accedere a tutte le funzionalit&agrave; offerte. <br>
        Se sei un utente puoi effettuare pagamenti verso i negozianti, se invece sei un negozio
        puoi effettuare pagamenti verso chiunque.</p>
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
