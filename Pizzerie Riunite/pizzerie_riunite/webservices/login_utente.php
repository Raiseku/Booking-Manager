<?php
    include_once('config.php');
    //dati passati dal controller
    $emailUtente = $_POST['emailUtentePhp'];
    $passwordUtente = $_POST['passwordUtentePhp'];
    
    //creo ed eseguo la query che và a ricercare le credenziali nella tabella di login
    $sql = "SELECT * FROM clienti WHERE email = '$emailUtente' AND password = '$passwordUtente'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){
        $json = array('info' => 'Login avvenuto con successo');
    } else {
        $json = array('info' => 'Credenziali sbagliate, riprovare');
    }


mysqli_close($conn);

echo json_encode($json);
?>