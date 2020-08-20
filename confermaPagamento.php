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
    if($_SESSION["logged"]==false){
        header('Location:login.php');//se arrivo alla pagina di elaborazione pagamento e non sono loggato mi riporta a login.php
    }
  }
  require_once "repetitiveScripts.php";//require per includere il file e scatenare eccezione fatale nel caso non venga incluso, once controlla che venga incluso una sola volta
 ?>
<!DOCTYPE HTML >
<html lang='it'>
  <head>
    <?php intestazioni('CONFERMA PAGAMENTO') ?>
    <link rel="prev" href="elaboraPagamento.php">
    <link rel="next" href="log.php">
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
        <?php
        echo '<h2 class="succ">Pagamento avvenuto con successo.</h2>';
        echo '<p>Il pagamento è stato effettuato da <i>'.$_SESSION["user"].'</i> ed è stato ricevuto da <i>'.$_SESSION["destinatario"].'</i>.</p>';
        echo '<p>L\'importo del pagamento &egrave stato di <i>'.number_format($_SESSION["importo"], 2, ',', '').'</i> &euro; ed &egrave stato registrato in data <i>'.$_SESSION["data"].'</i>.</p>';
         ?>
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
