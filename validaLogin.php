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
    <?php intestazioni('VALIDA LOGIN') ?>
    <link rel="prev" href="login.php">
    <link rel="next" href="paga.php">
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
        //$con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','pagamenti');
        if(isset($_REQUEST['user']) && isset($_REQUEST['password'])){
          $con=mysqli_connect('192.168.64.2','uReadOnly','posso_solo_leggere','pagamenti');
          if (mysqli_connect_errno()){
            printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
          }
          $query="SELECT `nick`,`pwd` FROM `usr` WHERE `nick`=? AND `pwd`=?";
          if($stmt=mysqli_prepare($con,$query)){
            mysqli_stmt_bind_param($stmt,"ss",$_REQUEST['user'],$_REQUEST['password']);//associo a ? l'user e la password
            if(mysqli_stmt_execute($stmt)==TRUE){//eseguo lo statement
              //esecuzione senza errori
              //controllo sul risultato, se c'è una riga allora tutto a posto
              if(mysqli_num_rows(mysqli_stmt_get_result($stmt))==0){
                //il login non corrisponde a nessuno Username
                die('<p class="err">ERRORE! I dati inseriti non corrispondono ad un utente registrato</p><p class="err">Ritorna alla pagina di <a href="login.php">LOGIN</a></p>');
              }else{
                //utente loggato con successo
                mysqli_stmt_close($stmt);//chiudo la connessione
                if(!mysqli_close($con)){
                  printf("<p>Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
                }
                //TODO crea il cookie per tenere memorizzato l'username con expiration fra 5 giorni
                $name="login_username";
                $value=$_REQUEST['username'];
                $expires=date("d/m/Y H:i:s");//capire il formato ideale della data
                //TODO decidere cosa fare per i flag opzionali
                setcookie($name , $value, $expires, $path, $domain, $secure, $httponly);
                //redirect a paga.php
                echo 'window.location.href="paga.php";</script>';
              }
              mysqli_stmt_close($stmt);//chiudo la connessione
              if(!mysqli_close($con)){
                printf("<p>Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
              }
            }else{
              //errore di esecuzione nella query
              echo '<p class="err">Errore nell\'esecuzione della query: Error code-->'.mysqli_stmt_errno($stmt).' '.mysqli_stmt_error($stmt).'</p>';
            }
          }else{
            //errore nella preparazione della query
            echo '<p class="err">Errore nella preparazione della query! Ricarica la pagina di <a href="login.php">LOGIN</a> ed esegui l\'accesso.</p>';
          }
        }else{
          if(!isset($_REQUEST['user'])) echo '<p class="err">ATTENZIONE! Nessun username inserito per il login.</p>';
          if(!isset($_REQUEST['password'])) echo '<p class="err">ATTENZIONE! Nessuna password inserita per il login.</p>';
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
