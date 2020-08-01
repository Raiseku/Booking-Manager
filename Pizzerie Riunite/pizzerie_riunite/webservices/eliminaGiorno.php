<?php

	include_once('config.php');

    //Ottengo il giorno e il nome della pizzeria tramite il metodo GET.
    //Il giorno è presente nell'URL della pagina, il nome della pizzerria all'interno dei Cookie

    $giorno = $_GET['giorno'];
    $nomePizzeria = $_GET['nomephp'];

    //Se i due valori sono effettivamente presenti
    if(!empty($giorno) && !empty($nomePizzeria)){

        //Elimino il giorno selezionato dall'utente sulla tabella relativa alla pizzeria con cui ha effettuato il login
    	$sql = "DELETE FROM $nomePizzeria WHERE giorno = '$giorno'";

    	$result = mysqli_query($conn, $sql);

    	if($result){

                $sql_delete = "DELETE FROM prenotazioni WHERE giorno = '$giorno' AND pizzeria = '$nomePizzeria'";
                $result_delete = mysqli_query($conn,$sql_delete);
                if($result_delete){}
                
                $json = array('status' => 0, 'msg' => 'Eliminato tutto con successo');

    	} else {
    		$json = array('status' => 0, 'msg' => 'Errore sulla delete *');
    	}
    
} else {
        $json = array('status' => 0, 'msg' => 'Campi vuoti');
}

//Chiudo la connessione
mysqli_close($conn);

//Ritorno i dati
echo json_encode($json);


?>