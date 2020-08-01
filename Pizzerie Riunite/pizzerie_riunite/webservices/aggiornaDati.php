<?php
    include_once('config.php');

    /*Ottengo i dati necessari per l'esecuzione delle query*/

    $nomePizzeria = $_POST['nomePizzeriaPhp'];
    $giorno = $_POST['data_scelta'];
    $nPostiAggiunti = $_POST['numPostiAggiunti'];
    $numero_tavoli_aggiunti = $_POST['nTavoliAggiunti'];
    $oraApertura = $_POST['ora_apertura'];
    $oraChiusura = $_POST['ora_chiusura'];


    //Seleziono il numero di posti liberi, totali e il numero tavoli presenti nella tabella della pizzeria del giorno scelto dall'utente
    $sql_posti_liberi = "SELECT nPostiLiberi, nPostiTotali, numero_tavoli FROM $nomePizzeria WHERE giorno = '$giorno'";
    $result_posti_liberi = mysqli_query($conn,$sql_posti_liberi);

    //Se la query ritorna più di una riga
    if(mysqli_num_rows($result_posti_liberi) > 0){
 		while($row = mysqli_fetch_assoc($result_posti_liberi)){

            //Finchè ci sono righe (in questo caso sempre e solamente una in quanto giorno è chiave primaria e quindi univoco)
            //Ottengo i dati relativi agli attuali posti liberi, totali e tavoli
            $nPostiLiberi = $row['nPostiLiberi'];
            $nPostiTotali = $row['nPostiTotali'];
            $numeroTavoli = $row['numero_tavoli'];
        }
	        
            //Avendo ottenuto i dati presenti nel DB nella query precedente, sommo (sia valori positivi che negativi) il valore che ha passato l'utente
            //nella form del Modifica Giorno andando a creare i nuovi dati da inserire nel giorno modificato
	        $posti_liberi_aggiornati = $nPostiLiberi + $nPostiAggiunti;
	        $posti_totali_aggiornati = $nPostiTotali + $nPostiAggiunti;
	        $numero_tavoli_aggiornati = $numeroTavoli + $numero_tavoli_aggiunti;


            //Controllo che il nuovo valore dei posti liberi, totali e i tavoli presenti siano effettivamente maggiori di 0
            if($posti_liberi_aggiornati >= 0 && $posti_totali_aggiornati >= 0 && $numero_tavoli_aggiornati >= 0 ){


                //Controllo se i posti rimanenti sono abbastanza da soddisfare tutte le prenotazioni per quel giorno

                $sql_prenotazioni = "SELECT numero_posti_prenotati FROM prenotazioni WHERE giorno = '$giorno'";
                $result_prenotazioni = mysqli_query($conn, $sql_prenotazioni);
                $totale_posti_per_giorno = 0;

                if(mysqli_num_rows($result_prenotazioni) > 0){
                    while($row = mysqli_fetch_assoc($result_prenotazioni)){
                        //Effettuo la somma dei posti totali prenotati

                        $nPostiPrenotati = $row['numero_posti_prenotati'];
                        $totale_posti_per_giorno = $totale_posti_per_giorno + $nPostiPrenotati;

                    }

                    //Oppure posti totali?
                    //Se c'è ancora posto per le prenotazioni presenti, allora effettuo l'UPDATE
                    if($posti_liberi_aggiornati >= $totale_posti_per_giorno){

                        $sql = "UPDATE $nomePizzeria SET nPostiLiberi = $posti_liberi_aggiornati, nPostiTotali = $posti_totali_aggiornati, ora_apertura = '$oraApertura', ora_chiusura = '$oraChiusura' , numero_tavoli = $numero_tavoli_aggiornati WHERE giorno = '$giorno'";

                            if (mysqli_query($conn, $sql)) {
                                $json = array('status'=> 1, 'Risultato' => 'Dati aggiornati con successo');
                            } else {
                                $json = array('status' => 0, 'Risultato' => 'Errore durante update');
                            }

                    } else {
                        //Se la pizzeria scala un numero di posti che fa andare in negativo quelli già prenotati dall'utente allora ritorno un errore
                        $json = array('status'=> 0, 'Risultato' => 'Posti troppo pochi per le persone già prenotate, riprova');
                    }
                } else {

                    //Se la query di prima non ha prodotto righe, significa che in una pizzeria per un determinato giorno non ci sono prenotazioni
                    //Quindi si da comunque la possibilità di cambiare a proprio piacimento i dati inseriti precedentemente
                    //Sempre con il vincolo che la somma/differenza che l'utente vuole effettuare, non sia negativa.

                    $sql = "UPDATE $nomePizzeria SET nPostiLiberi = $posti_liberi_aggiornati, nPostiTotali = $posti_totali_aggiornati, ora_apertura = '$oraApertura', ora_chiusura = '$oraChiusura' , numero_tavoli = $numero_tavoli_aggiornati WHERE giorno = '$giorno'";

                            if (mysqli_query($conn, $sql)) {
                                $json = array('status'=> 1, 'Risultato' => 'Dati aggiornati con successo');
                            } else {
                                $json = array('status' => 0, 'Risultato' => 'Errore durante update');
                            }
                }
            } else {
                //Se i dati precedenti sono negativi allora non effettuo la query di modifica e ritorno un errore all'utente
                 $json = array('status'=> 0, 'Risultato' => 'Presenza di valori negativi, riprova');
            }
    } else {
        $json = array('status' => 0, 'Risultato' => '0 righe ritornate');
    }

//Chiudo la connessione
mysqli_close($conn);

//Ritorno il dato json creato precedentemente
echo json_encode($json);
?>

