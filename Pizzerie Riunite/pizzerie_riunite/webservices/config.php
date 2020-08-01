<?php


//Specifico che tutte le tipologie di accessi possono essere accettate
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Valori relativi al nome del database su cui è stato costruito il progetto
$db_name = "pizzerie_riunite";
$mysql_username = "root";
$mysql_password = "";
$server_name = "127.0.0.1";

//Creo la variabile di connessione al database
$conn = mysqli_connect($server_name,$mysql_username,$mysql_password, $db_name);

//Se sono presenti errori nella connessione
if(mysqli_connect_errno()){
    echo('Connessione Fallita'.mysqli_connect_error());
    exit();
}


?>



