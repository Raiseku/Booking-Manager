<?php

	include_once('config.php');

    //Ottengo il nome della pizzeria da eliminare
    $nomePizzeria = $_GET['nomephp'];

    //Se questo valore è effettivamente presente
    if(!empty($nomePizzeria)){

        //Elimino la tabella creata dal database
    	$sql = "DROP TABLE $nomePizzeria"; 

    	$result = mysqli_query($conn, $sql);

    	if($result){
    		$json = array('status' => 1, 'msg' => 'La pizzeria è stata eliminata correttamente');

            /*Se la tabella relativa alla pizzeria è stata eliminata allora elimino anche le credenziali sulla lista pizzerie*/
            $sql_credenziali = "DELETE FROM lista_pizzerie WHERE nome_pizzeria = '$nomePizzeria'";
            $result_credenziali = mysqli_query($conn,$sql_credenziali);

            if($result_credenziali){
                //Se sia la tabella della pizzeria che la riga presente in lista_pizzerie sono state eliminate
                $json = array('status' => 1, 'msg' => 'La pizzeria è stata eliminata correttamente');

                //Una volta elimina le credenziali sulla lista pizzeria

                $sql_delete = "DELETE FROM prenotazioni WHERE pizzeria = '$nomePizzeria'";
                $result_delete = mysqli_query($conn,$sql_delete);
                if($result_delete){
                    $json = array('status' => 0, 'msg' => 'Pizzeria e prenotazioni eliminate');

                } else {
                    $json = array('status' => 0, 'msg' => 'Errore sulla Delete');
                }
                

            } else {
                $json = array('status' => 0, 'msg' => 'Errore');
            }


    	} else {
            //Se il dato del Get non è presente ritorno un errore
    		$json = array('status' => 0, 'msg' => 'Errore sul Get Pizzeria');
    	}
    }

    //Chiudo la connessione
    mysqli_close($conn);

    //Ritorno i dati
	echo json_encode($json);


?>