<?php

	include_once('config.php');
    //id passato dal controller della prenotazione da eliminare
    $id = $_GET['id'];

    //query per prendere il nome della pizzeria
    $sql = "SELECT pizzeria FROM prenotazioni WHERE id='$id' ";
    $row =  mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $nome_pizzeria = $row['pizzeria'];

    //query per prendere il numero dei posti prenotati
    $sql1="SELECT numero_posti_prenotati FROM prenotazioni WHERE id='$id' ";
    $row1 = mysqli_fetch_assoc(mysqli_query($conn, $sql1));
    $posti = $row1['numero_posti_prenotati'];

    //query per prendere il giorno della prenotazione
    $sql2="SELECT giorno FROM prenotazioni WHERE id='$id'";
    $row2 = mysqli_fetch_assoc(mysqli_query($conn, $sql2));
    $data = $row2['giorno'];

    //query per prendere i posti liberi della pizzeria in quel giorno
    $sql3= "SELECT nPostiLiberi FROM $nome_pizzeria WHERE giorno='$data'";
    $row3 = mysqli_fetch_assoc(mysqli_query($conn, $sql3));
    $postiLiberi = $row3['nPostiLiberi'];

    //calcolo i nuovi posti liberi e li aggiorno
    $postiDaAggiornare = (int)$postiLiberi+(int)$posti;
    $sql4 = "UPDATE $nome_pizzeria SET nPostiLiberi = $postiDaAggiornare WHERE giorno='$data' ";
    $row4 = mysqli_query($conn, $sql4);

    //elimino la prenotazione
	$sql5 = "DELETE FROM prenotazioni WHERE id = '$id'";
	$result = mysqli_query($conn, $sql5);

    //controllo il corretto funzionamento delle query e invio il risultato
	if($result){
		$json = array('status' => 1, 'msg' => 'Prenotazione eliminata correttamente');
	} else {
		$json = array('status' => 0, 'msg' => 'Errore');
	}


    mysqli_close($conn);

	echo json_encode($json);


?>