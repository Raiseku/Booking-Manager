<?php
    include_once('config.php');
    //dati passati dal controller
    $cliente = $_POST["cliente"];
    $luogo = $_POST["luogo"];
    $data = $_POST["data"];
    $orario = $_POST["orario"];
    $postiP = $_POST["postiP"];

    //creo ed eseguo la query
    $sql_trovo_dati = "SELECT nPostiLiberi, ora_apertura, ora_chiusura FROM $luogo WHERE giorno ='$data'";
    $result_dati = mysqli_query($conn,$sql_trovo_dati);

    //prendo i risultati della query
    if(mysqli_num_rows($result_dati) > 0){
 		while($row = mysqli_fetch_assoc($result_dati)){
            $postiL = $row['nPostiLiberi'];
            $ora_apertura = $row['ora_apertura'];
            $ora_chiusura = $row['ora_chiusura'];
        }
    //operazioni per valutare il rispetto dei vincoli dell'inserimento
    $test_posti = (int)$postiL - (int)$postiP;
    $orario_nuovo_spezzato = explode(":",$orario);
    $orario_apertura_spezzato = explode(":",$ora_apertura);
    $orario_chiusura_spezzato = explode(":",$ora_chiusura);
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

    //se i controlli sono positivi procede all'inserimento
    if($test_posti>=0 && $chiusura_ok=="true" && $apertura_ok=="true"){
        $h = (int)$orario_nuovo_spezzato[0];
        $m = (int)$orario_nuovo_spezzato[1];
        $sql = "INSERT INTO prenotazioni(id, utente, pizzeria, giorno,numero_posti_prenotati, ora, minuti) VALUES ('', '$cliente', '$luogo', '$data', $postiP, $h, $m)";
        $aggiornamento = "UPDATE $luogo SET nPostiLiberi = $test_posti WHERE giorno ='$data'";
        if (mysqli_query($conn, $sql) && mysqli_query($conn, $aggiornamento)) {
            $json = array('status'=> 1, 'Risultato' => 'Prenotazione effettuata!');
        } else {
            $json = array('status' => 0, 'Risultato' => 'Errore durante update');
        }
    }else{
        //messaggi di errore personalizzati in base al vincolo non rispettato
        if($test_posti<0){
            
            $json = array('status' => 0, 'Risultato' => "Hai prenotato troppi posti! I posti disponibili sono: ". $postiL);
        }else if($apertura_ok=="false"){
            $json = array('status' => 0, 'Risultato' => 'Hai prenotato troppo presto! La pizzeria apre alle: ' . $ora_apertura);
        }else if($chiusura_ok=="false"){
            $json = array('status' => 0, 'Risultato' => 'Hai prenotato troppo tardi! La pizzeria chiude alle: ' . $ora_chiusura);
        }else{
            $json = array('status' => 0, 'Risultato' => 'I dati inseriti non sono corretti ');
        }
        
    }
    } else {
        $json = array('status' => 0, 'Risultato' => 'Siamo spiacenti ma la pizzeria non ha disposto prenotazioni per quel giorno');
    }
	
    


mysqli_close($conn);

echo json_encode($json);
?>

