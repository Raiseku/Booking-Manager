
<?php
    include_once('config.php');

    if(isset($_POST['nomePizzeria'])){


    // Ottengo i dati relativi ad una giusta esecuzione della query
    $pizzeria = $_POST['nomePizzeria'];
    $data_scelta = $_POST['data_scelta'];

    /*Essendo un giorno appena creato, i posti liberi saranno sicuramente uguali ai posti totali, l'utente quando prenota farà l'update di questo valore contando quello effettivo meno quello prenotato da lui, se il valore scende sotto zero allora la prenotazione non può essere effettuata */
    $nPostiLiberi = $_POST['numPostiTotali'];
    $nPostiTotali = $_POST['numPostiTotali'];
    $numero_tavoli = $_POST['numeroTavoli'];
    $oraApertura = $_POST['ora_apertura'];
    $oraChiusura = $_POST['ora_chiusura'];

    //Controllo che i valori inseriti dall'utente siano effettivamente numeri
    if(is_numeric($nPostiTotali)){
        if(is_numeric($numero_tavoli)){

                //Qualora l'utente avesse inserito dati corretti effettuo la query andando a inserire il nuovo giorno con il valore di quest'ultimo come chiave primaria univoca
                $sql = "INSERT INTO $pizzeria (giorno,nPostiLiberi,nPostiTotali,ora_apertura,ora_chiusura, numero_tavoli) VALUES ('$data_scelta', $nPostiLiberi,$nPostiTotali,  '$oraApertura', '$oraChiusura' , $numero_tavoli);";
                $result = mysqli_query($conn, $sql);

                if($result){
                    //Se il giorno è stato inserito correttamente
                    $json = array('status' => 1, 'data' => 'Inserimento avvenuto con successo');

                } else {
                    //Se la query non viene eseguita allora significa che il giorno è già presente e si riporta l'errore all'utente.
                    //Essendo il giorno già presente si darà comunque la possibilità a quest'ultimo o di cancellare il giorno creato o di modificarlo.
                    $json = array('status' => 0, 'data' => 'Errore, il giorno selezionato è già presente');
                }

            } else {
                $json = array('status' => 0, 'data' => 'Errore, Numero tavoli deve essere un numero');
            }

    } else {
        $json = array('status' => 0, 'data' => 'Errore, Posti totali deve essere un numero');
    }

} else {

    $json = array('status' => 0, 'data' => 'Errore, effettua il login con una Pizzeria');
}


//Chiudo la connessione
mysqli_close($conn);

//Ritorno i dati
echo json_encode($json);
?>



