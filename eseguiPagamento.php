<?php
$author="Lorenzo Costa";
function eseguiPagamento($mittente,$destinatario,$importo){
  $con=mysqli_connect('172.17.0.60','uReadWrite','SuperPippo!!!','pagamenti');
  if (mysqli_connect_errno()){
    echo '<p class="err">Errore: connessione al database fallita.'.mysqli_connect_error().'</p>';
    $_SESSION["error"]=true;
  }
  $importo=$importo*100;//formato da usare nel database
  //update importo $mittente
  $query="UPDATE `usr` SET `saldo`=(`saldo`-?) WHERE `nick`=?";//rimuove $importo da chi effettua il pagamento-->$_SESSION["user"]=$mittente
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,"is",number_format($importo, 2, '.', ''),$mittente);
  mysqli_stmt_execute($stmt);
  //rilascio la memoria associata al result set
  mysqli_stmt_free_result($stmt);
  mysqli_stmt_close($stmt);

  //eseguo nuovo update importo $destinatario
  $query="UPDATE `usr` SET `saldo`=(`saldo`+?) WHERE `nick`=?";//aggiunge $importo a chi riceve il pagamento-->$destinatario
  $stmt=mysqli_prepare($con,$query);
  mysqli_stmt_bind_param($stmt,"is",number_format($importo, 2, '.', ''),$destinatario);
  mysqli_stmt_execute($stmt);
  //rilascio la memoria associata al result set
  mysqli_stmt_close($stmt);

  //Registro transazione nella tabella di log
  $query="INSERT INTO `log` (`src`, `dst`, `importo`, `data`) VALUES (?, ?, ?, ?)";
  $stmt=mysqli_prepare($con,$query);
  $date=$_SESSION["data"]=date("Y/m/d H:i:s");
  mysqli_stmt_bind_param($stmt,"ssis",$mittente,$destinatario,$importo,$date);
  mysqli_stmt_execute($stmt);
  //rilascio la memoria associata al result set
  mysqli_stmt_free_result($stmt);
  mysqli_stmt_close($stmt);
  //chiudo la connessione
  if(!mysqli_close($con)){
    printf("<p class=\"err\">Errore di chiusura della connessione, impossibile rilasciare le risorse.</p>");
    $_SESSION["error"]=true;
  }

}
 ?>
