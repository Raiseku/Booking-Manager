<?php
    include_once('config.php');


    if(isset($_GET['nomePizzeria'])){

    //Salvo il nome della pizzeria passato da js
    $pizzeria = $_GET['nomePizzeria'];

    //Seleziono tutti i dati presenti nella tabella della pizzeria
    $sql = "SELECT * FROM $pizzeria";
    $result = mysqli_query($conn, $sql);

    //Se la query ha generato più di una riga
    if(mysqli_num_rows($result) > 0){
        //Finchè ci sono righe
        while($row = mysqli_fetch_assoc($result)){

            //Carico l'array $data con tutti i giorni presenti nel database per mostrarli poi all'interno dell'HTML
            $giorno = $row['giorno'];
            $nPostiLiberi = $row['nPostiLiberi'];
            $nPostiTotali = $row['nPostiTotali'];
            $ora_apertura = $row['ora_apertura'];
            $ora_chiusura = $row['ora_chiusura'];
            $numero_tavoli = $row['numero_tavoli'];
            $data[] = array('giorno' => $giorno, 'nPostiLiberi' => $nPostiLiberi, 'nPostiTotali' => $nPostiTotali, 'ora_apertura' => $ora_apertura, 'ora_chiusura' => $ora_chiusura, 'numero_tavoli' => $numero_tavoli);
        }
        
        //Carico la variabile di ritorno json con tutti i dati ottenuti nel ciclo precedente
        $json = array('status' => 1, 'data' => $data);
        
    } else {
        //Se non è stata trovata nessuna riga
        //Da notare che la prima volta che l'utente accede non ci saranno giorni presenti, così come se li elimina tutti manualmente
        //Quindi non è impossibile che ci si possa trovare all'interno di questo caso.
        $json = array('status' => 0, 'data' => 'Nessuna riga è stata trovata');
    }

//Chiudo la connessione
mysqli_close($conn);

//Ritorno i dati
echo json_encode($json);
}
?>
