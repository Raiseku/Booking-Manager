<?php

	include_once('config.php');

	//Ottengo il giorno e il nome della pizzeria che l'utente vuole modificare
    $giorno = $_GET['giorno'];
    $nomePizzeria = $_GET['nomephp'];

    //Controllo omissibile in quanto su app.js già si effettua il controllo
    if(!empty($giorno)){

    	//Seleziono tutti i dati dalla pizzeria dell'utente dove il giorno è quello scelto da quest'ultimo
    	$sql = "SELECT * FROM $nomePizzeria WHERE giorno = '$giorno'";
    	$result = mysqli_query($conn, $sql);

    	//Se la query ritorna più di una riga
	    if(mysqli_num_rows($result) > 0){
	    	//Finchè ci sono dati
	        while($row = mysqli_fetch_assoc($result)){
	            $giorno = $row['giorno'];
	            $nPostiLiberi = $row['nPostiLiberi'];
	            $nPostiTotali = $row['nPostiTotali'];
	            $ora_apertura = $row['ora_apertura'];
	            $ora_chiusura = $row['ora_chiusura'];
	            $numero_tavoli = $row['numero_tavoli'];

	            //Creo l'array di dati relativi al giorno selezionato dall'utente, si ottengono tutti i dati nella tabella
	            $data = array('giorno' => $giorno, 'nPostiLiberi' => $nPostiLiberi, 'nPostiTotali' => $nPostiTotali, 'ora_apertura' => $ora_apertura, 'ora_chiusura' => $ora_chiusura, 'numero_tavoli' => $numero_tavoli);
	        }
	    }
	        
	        //json è la variabile di ritorno, se l'array data è stato caricato correttamente allora inserirò i dati ottenuto all'interno della variabile json
	        $json = $data;
	        
	    } else {
	        $json = array('status' => 0, 'data' => 'Nessuna riga è stata trovata');
	    }

//Chiudo la connessione
mysqli_close($conn);

//Ritorno i dati
echo json_encode($json);

?>