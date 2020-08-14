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
        header('Location:paga.php');//se arrivo alla pagina di login ma sono già loggato mi riporta a paga.php
    }
  }
  require_once "repetitiveScripts.php";//require per includere il file e scatenare eccezione fatale nel caso non venga incluso, once controlla che venga incluso una sola volta
 ?>
<!DOCTYPE HTML >
<html lang='it'>
  <head>
    <?php intestazioni('VALIDA LOGIN') ?>
    <link rel="prev" href="login.php">
    <link rel="next" href="paga.php">
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
        if(empty($_REQUEST['user'])){
          //utente vuoto
          echo '<p class="err">ATTENZIONE! Nessun username inserito per il login.</p>';
        }elseif(empty($_REQUEST['password'])){
          //password vuota
          echo '<p class="err">ATTENZIONE! Nessuna password inserita per il login.</p>';
        }elseif($session){
          $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','pagamenti');
          if(isset($_REQUEST['user']) && isset($_REQUEST['password'])){
            if (mysqli_connect_errno()){
              printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
            }
            $query="SELECT `saldo` FROM `usr` WHERE `nick`=? AND `pwd`=?";
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
                  //memorizzo nella sessione username e saldo
                  mysqli_stmt_execute($stmt);//chiamo di nuovo l'execute
                  mysqli_stmt_bind_result($stmt,$saldo);
                  mysqli_stmt_fetch($stmt);
                  $_SESSION["logged"]=true;
                  $_SESSION["user"]=$_REQUEST['user'];
                  //chiudo la connessione
                  mysqli_stmt_close($stmt);
                  if(!mysqli_close($con)){
                    printf('<p class="err">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>');
                  }
                  //creo il cookie per tenere memorizzato l'username con expiration fra 5 giorni
                  $name="login_username";
                  $value=$_REQUEST['user'];
                  $expires=time()+(86400*5);//5 giorni, 86400 secondi al giorno
                  $path="/";
                  $domain="http://195.231.2.153:29739";
                  $secure=true;
                  echo '<script>document.cookie="'.$name.'='.$value.';expires='.gmdate(DATE_COOKIE,$expires).';path='.$path.'"</script>';
                  echo '<script>window.location.href="paga.php";</script>';//redirect a paga.php
                }
                mysqli_stmt_close($stmt);//chiudo la connessione
                if(!mysqli_close($con)){
                  printf('<p class="err">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>');
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
        }else {
           echo "<p class=\"error\">Errore!</p> <p>Le sessioni sono disabilitate.</p>";
           echo "<p>Torna <a href=\"login.php\">indietro</a>.</p>";
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
