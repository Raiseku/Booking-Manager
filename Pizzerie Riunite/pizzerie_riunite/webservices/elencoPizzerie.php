<?php

	include_once('config.php');
    //crea ed esegue la query
	$sql = "SELECT nome_pizzeria FROM lista_pizzerie WHERE 1";
	$result = mysqli_query($conn, $sql);

    //prendo i dati dalla query
    if(mysqli_num_rows($result) > 0){
    	while($row = mysqli_fetch_assoc($result)){
        
        $luogo = $row['nome_pizzeria'];

        $data[] = array( 'nome_pizzeria' => $luogo);
        
    }
        //li inserisco in un json
        $json = array('status' => 1, 'data' => $data);
        
        
    } else {
        $json = array('status' => 0, 'data' => 'Nessuna riga è stata trovata');
    }

mysqli_close($conn);

echo json_encode($json);

?>
