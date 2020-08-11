<?php
  $session=true;
  if(session_status()===PHP_SESSION_DISABLED){
    $session=false;
  }elseif(session_status()!=PHP_SESSION_ACTIVE){
    session_start();
    //effettuare controlli di sessione
  }
  if($session){
    $_SESSION=array();//svuoto l'array contenente le variabili di sessione
    $cookie = session_get_cookie_params();//salvo il cookie con i dati per l'username
    setcookie( session_name(), '' , time()-50000 ,$cookie["path"],$cookie["domain"],$cookie["secure"],$cookie["httponly"]);
    session_destroy();//distruggo la sessione
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
    <?php intestazioni('LOGOUT') ?>
    <link rel="prev" href="paga.php">
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
        if($_SESSION["logged"]==false){//ho cambiato lo stato da logged a no logged e quindi posso affermare che tutto ha funzionato
          echo '<h2 class="succ">Logout avvenuto con successo.</h2>';
          echo '<p>Per usufruire di tutti i servizi effettua nuovamente il <a href="login.php">login</a>.</p>';
        }else{
          echo '<p class="err">Qualcosa è andato storto durante il logout, <a href="logout.php">riprova</a>.</p>';
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
