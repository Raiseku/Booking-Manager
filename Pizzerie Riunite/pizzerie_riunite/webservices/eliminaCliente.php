<?php

	include_once('config.php');

    //Ottengo il nome del cliente da eliminare
    $Cliente = $_GET['nomephp'];

    //Se questo valore è effettivamente presente
    if(!empty($Cliente)){

        //Elimino la tabella creata dal database
    	$sql = "DELETE FROM clienti WHERE email = '$Cliente'"; 

    	$result = mysqli_query($conn, $sql);

    	if($result){
    		$json = array('status' => 1, 'msg' => 'Il cliente è stato eliminato correttamente');

            //elimino tutte le prenotazioni dell'utente
            $sql_prenotazioni = "SELECT * FROM prenotazioni WHERE utente ='$Cliente'";
            $result_prenotazioni = mysqli_query($conn,$sql_prenotazioni);

            if(mysqli_num_rows($result_prenotazioni)>0){
                while($row = mysqli_fetch_assoc($result_prenotazioni)){
                    $id = $row['id'];
                    $pizzeria = $row['pizzeria'];
                    $giorno = $row['giorno'];
                    $postiPrenotati = $row['numero_posti_prenotati'];

                    $sql_verifica = "SELECT nPostiLiberi FROM $pizzeria WHERE giorno = '$giorno'";
                    $result_verifica = mysqli_fetch_assoc(mysqli_query($conn,$sql_verifica));
                    $nPostiAggiornare = $result_verifica['nPostiLiberi'];

                    $postiNuovi = (int)$nPostiAggiornare + (int)$postiPrenotati;
                    $sql4 = "UPDATE $pizzeria SET nPostiLiberi = $postiNuovi WHERE giorno='$giorno' ";
                    $row4 = mysqli_query($conn, $sql4);

                    $sql5 = "DELETE FROM prenotazioni WHERE id = '$id'";
                    $result1 = mysqli_query($conn, $sql5);
 
                }
            }


            if($result){
                
                $json = array('status' => 1, 'msg' => 'Il client è stato eliminato correttamente');
            } else {
                $json = array('status' => 0, 'msg' => 'Errore');
            }


    	} else {
            //Se il dato del Get non è presente ritorno un errore
    		$json = array('status' => 0, 'msg' => 'Errore delete');
    	}
    }

    //Chiudo la connessione
    mysqli_close($conn);

    //Ritorno i dati
	echo json_encode($json);


?>