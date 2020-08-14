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
    <?php intestazioni('PAGA') ?>
    <link rel="prev" href="login.php">
    <link rel="next" href="elaboraPagamento.php">
    <script type="text/javascript" src="usefulJS.js"></script>
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
              $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','pagamenti');
              if (mysqli_connect_errno()){
                printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
              }else{
                $query="SELECT COUNT(*) AS individui FROM `usr` WHERE `negozio`=0 ";
                $result=mysqli_query($con,$query);
                if(!$result){
                  printf('<p class="err">Errore: query fallita. %s</p>',mysqli_error($con));
                }
                else{
                  if(mysqli_num_rows($result)==1)
                    if($individui=mysqli_fetch_assoc($result)){
                      $individui=$individui["individui"];
                    }
                }
                $query="SELECT COUNT(*) AS negozi FROM `usr` WHERE `negozio`=1 ";
                $result=mysqli_query($con,$query);
                if(!$result){
                  printf('<p class="err">Errore: query fallita. %s</p>',mysqli_error($con));
                }
                else{
                  if(mysqli_num_rows($result)==1)
                  if($negozi=mysqli_fetch_assoc($result)){
                    $negozi=$negozi["negozi"];
                  }
                }
                echo '<p>Su questa piattaforma sono registrati '.$individui.' individui e '.$negozi.' negozi.</p>';
                echo '<p>Se vuoi usufruire delle funzionalit&agrave offerte effettua il <a href="login.php">login</a>.</p>';
                //chiudere la connessione
                if(!mysqli_close($con)){
                  printf('<p class="err">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>');
                }
              }

            }else{
              echo "<p class=\"error\">Errore!</p> <p>Le sessioni sono disabilitate.</p>";
              echo "<p>Torna <a href=\"home.php\">indietro</a>.</p>";
            }
          }else{
            //utente loggato
            if($session){
              $con=mysqli_connect('172.17.0.60','uReadOnly','posso_solo_leggere','pagamenti');
              if (mysqli_connect_errno()){
                printf('<p class="err">Errore: connessione al database fallita. %s</p>',mysqli_connect_error());
              }else{
                $query='SELECT `negozio` FROM `usr` WHERE `nick`=\'' .$_SESSION["user"].'\'';
                $result=mysqli_query($con,$query);
                if(!$result){
                  printf('<p class="err">Errore: query fallita. %s</p>',mysqli_error($con));
                }else{
                  if(mysqli_num_rows($result)==1)
                    if($tipologia=mysqli_fetch_assoc($result)){
                      $tipologia=$tipologia["negozio"];
                    }
                }
              }
              if($tipologia==0){//utente singolo
                //faccio una query per selezionare tutti i negozianti
                $query="SELECT `nick`,`nome` FROM `usr` WHERE `negozio`=1 ";
                $result=mysqli_query($con,$query);
              }elseif($tipologia==1){//negozio
                $query='SELECT `nick`,`nome` FROM `usr` WHERE `nick`!=\'' .$_SESSION["user"].'\'';
                $result=mysqli_query($con,$query);
              }else{
                //errore generico nella query
                echo '<p class="err">Errore nell\'esecuzione della query: Error code-->'.mysqli_stmt_errno($stmt).' '.mysqli_stmt_error($stmt).'</p>';
                //TODO aggiungere un ritorno a home.php tramite repetitiveScripts.php
              }
              if($_SESSION["saldo"]>0){
                echo '<form id="formPagamento" action="elaboraPagamento.php" method="POST">';
                echo '<table class="wrapper"><caption>Seleziona un destinatario, immetti l\'importo da trasferire ed esegui la transazione</caption><tr><th>Seleziona un destinatario</th><th>Destinatari</th></tr>';
                while($row=mysqli_fetch_assoc($result)){
                  echo '<tr><td><input type="radio" name="destinatario" value="'.$row["nick"].'"></td><td>'.$row["nome"].'</td></tr>';
                }
                echo '</table>';
                echo '<div class="numberWrapper"><label for="importo">Inserisci la quantità di denaro che vuoi trasferire: </label><input type="number" name="importo" id="importo" step="0.01" min="0.01" max="'.$_SESSION["saldo"].'"> <label for="importo">&euro;</label></div>';
                echo '<div class="numberWrapper"><div class="bottoni"><input type="submit" name="paga" id="paga" value="PROCEDI" > <input type="reset" name="reset" value="PULISCI"></div></div>';
                echo '</form>';
              }else{
                echo '<p class="avviso">Ci dispiace, ma non puoi effettuare pagamenti!</p>';
                echo '<p class="avviso">Il tuo saldo risulta negativo, attendi di ricevere pagamenti per poter scambiare ulteriore denaro.</p>';

              }

              //chiudo la connessione
              if(!mysqli_close($con)){
                printf('<p class="err">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>');
              }
            }else {
            echo '<p class="err">Attenzione!</p><p>Sessione disabilitata!</p>';
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
