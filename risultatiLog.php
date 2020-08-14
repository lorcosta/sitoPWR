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
<!DOCTYPE HTML >
<html lang='it'>
  <head>
    <?php intestazioni('RISULTATI LOG') ?>
    <link rel="prev" href="log.php">
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
          if($_SESSION["logged"]==false){
            //utente anonimo
            if($session){
              echo '<h2>Attenzione! l’elenco dei pagamenti &egrave disponibile solo per gli utenti autenticati.</h2>';
              echo '<p>Effettua il <a href="login.php">login</a> per usufruire di questa funzionalit&agrave.</p>';
            }
          }else{
            //utente loggato, presento i risultati in base alle scelte effettuate
            $posizione=$_REQUEST["posizione"];
            $data=$_REQUEST["data"];
            $error=false;//utilizzo flag per controllare eventuali errori ed evitare di stampare il risultato
                        //delle query se avviene qualche tipo di errore interrogando il db
            $date=date('m');
            $mesePrecedente=date('m',strtotime("-1 months"));
            $mesiPrecedenti=date('m',strtotime("-2 months"));
            $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','pagamenti');
            if (mysqli_connect_errno()){
              echo '<p class="err">Errore: connessione al database fallita.'.mysqli_connect_error().'</p>';
              $error=true;
            }
            if(strcmp($posizione, "tutti")==0){
              if(strcmp($data,"corrente")==0){
                $query="SELECT src,dst,importo,data FROM log WHERE (src=? OR dst=?) AND MONTH(data)=? AND YEAR(data)=YEAR(CURRENT_DATE)";
                $stmt=mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,"ssi",$_SESSION["user"],$_SESSION["user"],$date);
                mysqli_stmt_execute($stmt);
              }elseif(strcmp($data,"corrente-2precedenti")==0){
                $query="SELECT src,dst,importo,data FROM log WHERE (src=? OR dst=?) AND MONTH(data)>=(MONTH(CURRENT_DATE)-3) AND YEAR(data)=YEAR(CURRENT_DATE)";
                $stmt=mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,"ss",$_SESSION["user"],$_SESSION["user"]);
                mysqli_stmt_execute($stmt);
              }else{
                echo '<p class="err">ATTENZIONE! Sembra che tu abbia scelto un\'opzione non valida.</p>';
                echo '<p class="err"><a href="log.php">Ritorna</a> alla pagina di scelta delle condizioni di ricerca e seleziona un campo valido.</p>';
                $error=true;
              }
            }elseif(strcmp($posizione,"ordinati")==0){
              if(strcmp($data,"corrente")==0){
                $query="SELECT src,dst,importo,data FROM log WHERE src=? AND MONTH(data)=? AND YEAR(data)=YEAR(CURRENT_DATE)";
                $stmt=mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,"si",$_SESSION["user"],$date);
                mysqli_stmt_execute($stmt);
              }elseif(strcmp($data,"corrente-2precedenti")==0){
                $query="SELECT src,dst,importo,data FROM log WHERE src=? AND MONTH(data)>=(MONTH(CURRENT_DATE)-3) AND YEAR(data)=YEAR(CURRENT_DATE)";
                $stmt=mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,"s",$_SESSION["user"]);
                mysqli_stmt_execute($stmt);
              }else{
                echo '<p class="err">ATTENZIONE! Sembra che tu abbia scelto un\'opzione non valida.</p>';
                echo '<p class="err"><a href="log.php">Ritorna</a> alla pagina di scelta delle condizioni di ricerca e seleziona un campo valido.</p>';
                $error=true;
              }
            }elseif(strcmp($posizione,"ricevuti")==0){
              if(strcmp($data,"corrente")==0){
                $query="SELECT src,dst,importo,data FROM log WHERE dst=? AND MONTH(data)=? AND YEAR(data)=YEAR(CURRENT_DATE)";
                $stmt=mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,"si",$_SESSION["user"],$date);
                mysqli_stmt_execute($stmt);
              }elseif(strcmp($data,"corrente-2precedenti")==0){
                $query="SELECT src,dst,importo,data FROM log WHERE dst=? AND MONTH(data)>=(MONTH(CURRENT_DATE)-3) AND YEAR(data)=YEAR(CURRENT_DATE)";
                $stmt=mysqli_prepare($con,$query);
                mysqli_stmt_bind_param($stmt,"s",$_SESSION["user"]);
                mysqli_stmt_execute($stmt);
              }else{
                echo '<p class="err">ATTENZIONE! Sembra che tu abbia scelto un\'opzione non valida.</p>';
                echo '<p class="err"><a href="log.php">Ritorna</a> alla pagina di scelta delle condizioni di ricerca e seleziona un campo valido.</p>';
                $error=true;
              }
            }else{
              echo '<p class="err">ATTENZIONE! Sembra che tu abbia scelto un\'opzione non valida.</p>';
              echo '<p class="err"><a href="log.php">Ritorna</a> alla pagina di scelta delle condizioni di ricerca e seleziona un campo valido.</p>';
              $error=true;
            }
            if(!$error){
              //nel resul set ci sono le righe che corrispondono alla query effettuata, metto sotto forma di tabella e stampo
              mysqli_stmt_bind_result($stmt,$src,$dst,$importo,$date);
              echo '<table class="wrapper">
                <caption>Risultati delle condizioni di ricerca selezionate</caption>
                <tr><th>Ordinante</th><th>Ricevente</th><th>Importo</th><th>Data</th></tr>';
              while(mysqli_stmt_fetch($stmt)){
                echo '<tr><td>'.$src.'</td><td>'.$dst.'</td><td>'.number_format(($importo/100), 2, ',', '').' &euro;</td><td>'.$date.'</td></tr>';
              }
              echo '</table>';
              //rilascio la memoria associata al result set
              mysqli_stmt_free_result($stmt);
              mysqli_stmt_close($stmt);
            }
            if(!mysqli_close($con)){
              printf("<p class=\"err\">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
            }
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
