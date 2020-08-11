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
    if($_SESSION["logged"]==true){
        header('Location:paga.php');
    }
  }
  require_once "repetitiveScripts.php";//require per includere il file e scatenare eccezione fatale nel caso non venga incluso, once controlla che venga incluso una sola volta
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//IT"
  “http://www.w3.org/TR/html4/strict.dtd">
<html lang='it'>
  <head>
    <?php intestazioni('LOGIN') ?>
    <link rel="prev" href="home.php">
    <link rel="next" href="validaLogin.php">
    <script type="text/javascript" src="usefulJS.js"></script>
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
        <form id="formLogin" action="validaLogin.php" method="POST">
          <p>Username:</p>
          <div class="social">
            <img src="img/utente.png" alt="Icona utente">
            <?php if(isset($_COOKIE["login_username"])){
              echo '<input type="text" id="user" name="user" value="'.$_COOKIE["login_username"].'" size="30" onchange="checkLogin(\'formLogin\',\'user\')">';
            }else{
              echo '<input type="text" id="user" name="user" placeholder="Inserisci qui il tuo username" size="30" onchange="checkLogin(\'formLogin\',\'user\')">';
            } ?>
          </div>
          <p>Password:</p>
          <div class="social">
            <img src="img/password.png" alt="Icona utente">
            <input type="password" id="password" name="password" placeholder="Inserisci qui la tua password" size="30" onchange="checkLogin('formLogin','password')">
          </div>
          <div class="bottoni">
            <input type="submit" name="ok" value="OK">
            <input type="reset" name="reset" value="PULISCI" onclick="cleanForm('formLogin','user','password')">
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
