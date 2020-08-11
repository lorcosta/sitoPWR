"use strict";
/**
Riporta a vuoti tutti i campi di login all'interno di un form passando come
parametri l'ID del form, l'ID del campo username e l'ID del campo password
*/
function cleanForm(idElement,username,password){
  document.getElementById(username).defaultValue="";
  document.getElementById(username).placeholder="Inserisci qui il tuo username";
  document.getElementById(password).value="";
}
/**
Controlla che i campi di login di un form non siano lasciati vuoti passando come
parametri l'ID del form, l'ID del campo username e l'ID del campo password
*/
function checkLogin(form, text){
  if(document.getElementById(text).value.length==0){
    window.alert("ATTENZIONE! Nessun valore inserito nel campo "+text+".");
    window.location.href="login.php";
  }
}
