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
    <?php intestazioni('ELABORAZIONE PAGAMENTO') ?>
    <link rel="prev" href="paga.php">
    <link rel="next" href="confermaPagamento.php">
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
        if($session){
          if($_SESSION["logged"]==true){
            $_SESSION["error"]=false;//flag che mi serve per capire se avvengono errori e fermarmi quindi in questa pagina
            if(!isset($_REQUEST["destinatario"]) && (!isset($_REQUEST["importo"]) || $_REQUEST["importo"]=="")){
              echo '<p class="err">ATTENZIONE! Non &egrave stato selezionato alcun destinatario e inserito alcun importo per il pagamento.</p>';
              echo '<p class="err"><a href="paga.php">Torna alla pagina di pagamento</a> e seleziona un destinatario e inserisci l\'importo della transazione.</p>';
              $_SESSION["error"]=true;
            }elseif(!isset($_REQUEST["destinatario"])){
              echo '<p class="err">ATTENZIONE! Non &egrave stato selezionato alcun destinatario per il pagamento.</p>';
              echo '<p class="err"><a href="paga.php">Torna alla pagina di pagamento</a> e seleziona un destinatario.</p>';
              $_SESSION["error"]=true;
            }elseif(!isset($_REQUEST["importo"]) || $_REQUEST["importo"]==""){
              echo '<p class="err">ATTENZIONE! Non &egrave stato selezionato alcun importo per il pagamento.</p>';
              echo '<p class="err"><a href="paga.php">Torna alla pagina di pagamento</a> e inserisci l\'importo della transazione.</p>';
              $_SESSION["error"]=true;
            }
            //controllo che l'importo inserito sia corretto
            $importo=$_REQUEST["importo"];
            $_SESSION["importo"]=$importo;
            $regex="/^\d{1,}((,|.)\d{1,2})?$/";
            if(!preg_match($regex,$importo) && $_SESSION["error"]==false){
              echo '<p class="err">ATTENZIONE! Inserire un numero intero o con al più 2 cifre decimali separate da una virgola.</p>';
              echo '<p class="err"><a href="paga.php">Torna</a> alla pagina di pagamento.</p>';
              $_SESSION["error"]=true;
            }
            //controllo che il destinatario appartenga al mio database
            $mittente=$_SESSION["user"];
            $destinatario=$_REQUEST["destinatario"];
            $_SESSION["destinatario"]=$destinatario;
            $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','pagamenti');
            if (mysqli_connect_errno()){
              printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
              $_SESSION["error"]=true;
            }else{
              $query="SELECT nick FROM `usr` WHERE nick=?";
              if($stmt=mysqli_prepare($con,$query)){
                mysqli_stmt_bind_param($stmt,"s",$destinatario);
                mysqli_stmt_execute($stmt);
                $numRighe=mysqli_num_rows(mysqli_stmt_get_result($stmt));
                if($numRighe==0 && $_SESSION["error"]==false){
                  echo '<p class="err">ATTENZIONE! Hai appena selezionato un destinatario che non è presente nell\'elenco!</p>';
                  echo '<p class="err"><a href="paga.php">Torna</a> alla pagina di pagamento e seleziona un destinatario disponibile.</p>';
                  $_SESSION["error"]=true;
                }elseif($numRighe>1){
                  echo '<p class="err">ATTENZIONE! Sembra ci sia un problema di ridondanza nei destinatari.</p>';
                  echo '<p class="err">Seleziona un destinatario diverso o <a href="mailto:s249739@studenti.polito.it">contatta</a> gli amministratori.</p>';
                  $_SESSION["error"]=true;
                }
                mysqli_stmt_close($stmt);
                //chiudo la connessione
                if(!mysqli_close($con)){
                  printf("<p>Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
                  $_SESSION["error"]=true;
                }
              }
            }
            //se sono arrivato qui i due input sono stati inseriti e sono corretti
            //eseguo il pagamento vero e proprio
            require_once "eseguiPagamento.php";
            if(!$_SESSION["error"]){
              eseguiPagamento($mittente,$destinatario,$importo);
            }
            if(!$_SESSION["error"]){//non sono avvenuti errori perciò posso reindirizzare alla pagina confermaPagamento.php che da le info riassuntive
              echo '<script>window.location.href="confermaPagamento.php";</script>';
            }
          }else{
            //impossibile arrivare a questo punto perchè se l'utente accede a questa pagina senza essere loggato viene
            //automaticamente reindirizzato alla pagina di login
            echo '<p class="err">ATTENZIONE! Non sei registrato sul sito.</p>';
            echo '<p class="err"><a href="login.php">Ritorna</a> alla pagina di login per accedere a tutte le funzionalit&agrave.</p>';
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
