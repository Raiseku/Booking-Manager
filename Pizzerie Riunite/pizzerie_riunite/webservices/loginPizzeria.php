<?php
    include_once('config.php');

    //Ottengo i dati necessari alla giusta esecuzione delle query
    $nomePizzeria = trim(strtolower($_POST['nomePizzeriaPhp'])); //Tolgo sia gli spazi che le maiuscole al nome pizzeria inserito dall'utente
    $password = trim($_POST['passwordPhp']); //Tolgo gli spazi alla password
    

    //Controllo sulla validità delle credenziali omesso in quanto il controllo stesso viene effettuato in app.js

    //Controllo se le credenziali che ha inserito l'utente sono effettivamente presenti all'interno della tabella lista_pizzerie
    $sql = "SELECT * FROM lista_pizzerie WHERE nome_pizzeria = '$nomePizzeria' AND password = '$password'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        //Se l'utente ha inserito le giuste credenziali, effettua il Login
        $json = array('info' => 'Login avvenuto con successo');
    } else {
        //Se l'utente ha inserito le credenziali sbagliate lo si invita a riprovare
        $json = array('info' => 'Credenziali sbagliate, riprovare');
    }


//Chiudo la connessione
mysqli_close($conn);

//Ritorno i dati
echo json_encode($json);
?>