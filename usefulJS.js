"use strict";
/**
Riporta a vuoti tutti i campi di login all'interno di un form passando come
parametri l'ID del form, l'ID del campo username e l'ID del campo password.
*/
function cleanForm(idElement,username,password){
  document.getElementById(username).defaultValue="";
  document.getElementById(username).placeholder="Inserisci qui il tuo username";
  document.getElementById(password).value="";
}
/**
Controlla che i campi di login di un form non siano lasciati vuoti passando come
parametri l'ID del form, l'ID del campo username e l'ID del campo password.
*/
function checkLogin(form, text){
  if(document.getElementById(text).value.length==0){
    window.alert("ATTENZIONE! Nessun valore inserito nel campo "+text+".");
    window.location.href="login.php";
  }
}
/**
Controlla che il valore numerico inserito per il trasferimento di denaro sia congruo,
riceve in input l'id del form e l'id del campo che contiene la somma da trasferire.
*/
function checkPayment(form,idNumber){
  if(document.getElementById(idNumber).value.length==0){
    window.alert("ATTENZIONE! Nessun valore inserito nel campo di pagamento.");
    window.location.href="paga.php";
  }
  var regexp=/^\d{1,}((\,)?\d{1,2})?$/;
  var importo=document.getElementById(idNumber).value;
  importo=importo.replace(".",",")
  if(!regexp.test(importo)){
    window.alert("ATTENZIONE! Inserire un numero intero o con al più 2 cifre decimali separate da una virgola.");
  }
}
