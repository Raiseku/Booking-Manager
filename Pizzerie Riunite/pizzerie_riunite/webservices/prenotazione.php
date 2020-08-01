<?php

	include_once('config.php');
	//id della prenotazione da visualizzare inviato dal controller
    $id = $_GET['id'];
    
    //creo ed eseguo la query, invio un json con i dati
    if(!empty($id)){
    	$sql = "SELECT * FROM prenotazioni WHERE id = '$id'";
    	$result = mysqli_query($conn, $sql);


	    if(mysqli_num_rows($result) > 0){
	        $row = mysqli_fetch_assoc($result);
	        $luogo = $row['pizzeria'];
            $giorno = $row['giorno'];
            $numero_posti_prenotati = $row['numero_posti_prenotati'];
            $ora = $row['ora'];
            $minuti = $row['minuti'];
            $id = $row['id'];
            $data[] = array('giorno' => $giorno, 'numero_posti_prenotati' => $numero_posti_prenotati, 'nome_pizzeria' => $luogo, 'giorno' => $giorno, 'ora' => $ora, 'minuti' => $minuti, 'id' => $id);
	        
	    }
	        $json = array('status' => 1, 'data' => $data);
	        
	        
	    } else {
	        $json = array('status' => 0, 'data' => 'Nessuna riga è stata trovata');
	    }

mysqli_close($conn);

echo json_encode($json);

?>
