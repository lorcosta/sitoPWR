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
    <?php intestazioni('LOG') ?>
    <link rel="prev" href="confermaPagamento.php">
    <link rel="next" href="risultatiLog.php">
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
        <?php
          if($_SESSION["logged"]==false){
            //utente anonimo
            if($session){
              echo '<h2>Attenzione! l’elenco dei pagamenti &egrave disponibile solo per gli utenti autenticati.</h2>';
            }
          }else{
            //utente loggato, form per effettuare scelta
            echo '<form name="formRicerca" action="risultatiLog.php" method="GET">
              <div class="numberWrapper">
              <label for="posizione">Seleziona quali pagamenti vuoi visualizzare: </label>
              <select id="posizione" name="posizione">
                <option value="tutti">Tutti i pagamenti</option>
                <option value="ordinati">Pagamenti ordinati</option>
                <option value="ricevuti">Pagamenti ricevuti</option>
              </select>
              </div>
              <br><br>
              <div class="numberWrapper">
              <label for="data">Seleziona il periodo nel quale vuoi ricercare i pagamenti: </label>
              <select id="data" name="data">
                <option value="corrente">Mese corrente</option>
                <option value="corrente-2precedenti">Mese corrente e 2 mesi precedenti</option>
              </select>
              </div>
              <div class="numberWrapper">
              <div class="bottoni">
                <input type="submit" name="cerca" value="CERCA">
                <input type="reset" name="pulisci" value="PULISCI">
              </div>
              </div>
            </form>';

          }
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
