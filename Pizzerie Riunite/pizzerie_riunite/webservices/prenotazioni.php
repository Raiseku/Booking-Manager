<?php
    include_once('config.php');

    if(isset($_GET['email_Utente'])){
        //id dell'utente di cui si vuole mostrare la lista delle prenotazioni
        $user = $_GET['email_Utente'];
        //creo ed eseguo la query, invio i dati con un json
        $sql = "SELECT * FROM prenotazioni WHERE utente = '$user'";
        $result = mysqli_query($conn, $sql);


        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
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
    }
?>
