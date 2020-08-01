<?php

    include_once('config.php');


    //Ottengo i dati necessari alla giusta esecuzione delle query
    $nomePizzeria = trim(strtolower($_POST['nomePizzeriaPhp'])); //Tolgo sia gli spazi che le maiuscole al nome pizzeria inserito dall'utente
    $password = trim($_POST['passwordPhp']); //Tolgo gli spazi alla password
    $indirizzo = $_POST['indirizzoPhp'];


    //Se i dati sono effettivamente presenti [Controllo omissibile in quanto è già stato fatto su app.js]
    if(!empty($nomePizzeria) && !empty($password) && !empty($indirizzo)){
    
    //Inserisco la pizzeria appena creata dall'utente all'interno di "lista_pizzerie" per far si che l'utente possa effettivamente prenotare su di essa 
    $sql_lista = "INSERT INTO lista_pizzerie (nome_pizzeria, password, indirizzo) VALUES ('$nomePizzeria', '$password', '$indirizzo');";

    $result_lista = mysqli_query($conn, $sql_lista);

    if($result_lista){
        $json = array('info' => 'Registrazione Avvenuta con successo! Puoi effettuare il Login');

        //Creo la pizzeria appena registrata dall'utente all'interno del database che conterrà i dati dei singoli giorni
        $sql = "CREATE TABLE $nomePizzeria (
            giorno DATE,
            nPostiLiberi INT,
            nPostiTotali INT,
            ora_apertura VARCHAR(10), 
            ora_chiusura VARCHAR(10), 
            numero_tavoli INT,
            PRIMARY KEY (giorno)
        )";

        $result = mysqli_query($conn, $sql);

        //Controllo necessario per effettiva esecuzione della query
        if($result){} 

    } else { 

        //Se la query non viene effettuata significa che è già presente una pizzeria con il nome scelto dell'utente, quindi ritorno l'errore
        $json = array('info' => 'Nome già presente, riprovare con uno nuovo');
    }


//Chiudo la connessione
mysqli_close($conn);

//Ritorno i dati
echo json_encode($json);
}
?>