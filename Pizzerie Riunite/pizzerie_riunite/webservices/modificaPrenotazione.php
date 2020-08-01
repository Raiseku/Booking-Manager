<?php
    include_once('config.php');
    //dati passati dal controller
    $id = $_POST['id'];
    $posti_prenotati = $_POST['postiDaSostituire'];
    $nuovoOrario = $_POST['orario'];

    //creo ed eseguo una query per ricavare i dati di quella prenotazione da modificare
    $sql_trovo_dati = "SELECT pizzeria, giorno, numero_posti_prenotati FROM prenotazioni WHERE id ='$id'";
    $result_dati = mysqli_query($conn,$sql_trovo_dati);

    if(mysqli_num_rows($result_dati) > 0){
 		while($row = mysqli_fetch_assoc($result_dati)){
            $luogo = $row['pizzeria'];
            $giorno = $row['giorno'];
            $postiVecchi = $row['numero_posti_prenotati'];
        }
    } else {
        $json = array('status' => 0, 'Risultato' => '0 righe ritornate');
    }
	
    //trovo i dati della pizzeria relativi a quel giorno
	$sql_trovo_dati2 = "SELECT nPostiLiberi, ora_apertura, ora_chiusura FROM $luogo WHERE giorno='$giorno'";
	$result_dati2 = mysqli_query($conn,$sql_trovo_dati2);    

	if(mysqli_num_rows($result_dati2) > 0){
 		while($row2 = mysqli_fetch_assoc($result_dati2)){
            $postiLiberi = $row2['nPostiLiberi'];
            $apertura = $row2['ora_apertura'];
            $chiusura = $row2['ora_chiusura'];
        }
    } else {
        $json = array('status' => 0, 'Risultato' => '0 righe ritornate');
    }

    //eseguo dei controlli per verificare la correttezza dell'aggiornamento
    $postidavisualizzare = (int)$postiLiberi + (int)$postiVecchi;
    $test_posti = (int)$postiLiberi + (int)$postiVecchi - (int)$posti_prenotati;
    $orario_nuovo_spezzato = explode(":",$nuovoOrario);
    $orario_apertura_spezzato = explode(":",$apertura);
    $orario_chiusura_spezzato = explode(":",$chiusura);
    $apertura_ok = 'false';
    $chiusura_ok = 'false';

    if((int)$orario_nuovo_spezzato[0] == (int)$orario_apertura_spezzato[0]){
    	if((int)$orario_nuovo_spezzato[1] > (int)$orario_apertura_spezzato[1]){
    		$apertura_ok = 'true';
    	}
    }else if((int)$orario_nuovo_spezzato[0] > (int)$orario_apertura_spezzato[0]){
    	$apertura_ok = 'true';
    }


    if((int)$orario_nuovo_spezzato[0] < (int)$orario_chiusura_spezzato[0]){
    	$chiusura_ok = 'true';
    }else if((int)$orario_nuovo_spezzato[0] == (int)$orario_chiusura_spezzato[0]){
    	if((int)$orario_nuovo_spezzato[1] < (int)$orario_chiusura_spezzato[1]){
    		$chiusura_ok = 'true';
    	}
    }

    //se i test sono positivi aggiorno i dati e invio il risultato 
    if($test_posti>=0 && $chiusura_ok=="true" && $apertura_ok=="true"){
    	$sql = "UPDATE prenotazioni SET numero_posti_prenotati = $posti_prenotati, ora =$orario_nuovo_spezzato[0], minuti= $orario_nuovo_spezzato[1] WHERE id='$id'";
       	  	

		$aggiornamento = "UPDATE $luogo SET nPostiLiberi = $test_posti";
		if (mysqli_query($conn, $sql) && mysqli_query($conn, $aggiornamento)) {
		    $json = array('status'=> 1, 'Risultato' => 'Dati aggiornati con successo');
		} else {
		    $json = array('status' => 0, 'Risultato' => 'Errore durante update');
		}
    }else{
        //messaggi di errore personalizzati in base al vincolo violato
    	if($test_posti<0){	
    		$json = array('status' => 0, 'Risultato' => "Hai prenotato troppi posti! I posti disponibili sono: ". $postidavisualizzare);
    	}else if($apertura_ok=="false"){
    		$json = array('status' => 0, 'Risultato' => 'Hai prenotato troppo presto! La pizzeria apre alle: ' . $apertura);
    	}else if($chiusura_ok=="false"){
    		$json = array('status' => 0, 'Risultato' => 'Hai prenotato troppo tardi! La pizzeria chiude alle: ' . $chiusura);
    	}else{
    		$json = array('status' => 0, 'Risultato' => 'I dati inseriti non sono corretti ');
    	}
    	
    }

mysqli_close($conn);

echo json_encode($json);
?>

