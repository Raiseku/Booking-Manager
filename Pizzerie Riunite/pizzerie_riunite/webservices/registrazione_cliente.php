<?php
    include_once('config.php');

    //dati passati dal controller per la registrazionee
    $emailCLiente = trim($_POST['emailClientePhp']);
    $passwordCliente = trim($_POST['passwordClientePhp']);
    $indirizzoCliente =  trim($_POST['indirizzoClientePhp']);
    $nomeCliente = trim($_POST['nomeClientePhp']);
    $cognomeCliente = trim($_POST['cognomeClientePhp']);


        //inserisco i dati nel database in modo tale che l'utente possa loggarsi

        $sql_add = "INSERT INTO clienti (email, password, nome, cognome, indirizzo) VALUES ('$emailCLiente', '$passwordCliente', '$nomeCliente', '$cognomeCliente', '$indirizzoCliente');";

        $result_add = mysqli_query($conn, $sql_add);

        if($result_add){
            $json = array('info' => 'Registrazione Avvenuta con successo! Puoi effettuare il Login');

            

        } else { 

            $json = array('info' => 'Email già presente, riprovare con una nuova');
        }



        mysqli_close($conn);

        echo json_encode($json);


    
?>