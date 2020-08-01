
var myApp = angular.module("pizzerieRiunite", ["ngRoute"]);


 myApp.config(function($httpProvider) {
      //Enable cross domain calls
      $httpProvider.defaults.useXDomain = true;

      //Remove the header used to identify ajax call  that would prevent CORS from working
      delete $httpProvider.defaults.headers.common['X-Requested-With'];
  });



//Tutte le funzioni saranno disponibili dopo che l'utente avrà effettuato l'accesso, quindi il valore
//all'interno dei cookie sarà corretto

//Funzione che permette l'accesso alla pagina di creazione del giorno
function creaGiorno(){
        window.location.href = "http://localhost/pizzerie_riunite/#/creaGiorno/" + $.cookie("nomePizzeria");
}


//Funzione che permette l'accesso alla pagina di visualizzazione della lista giorni
function visualizzaLista(){
        window.location.href = "http://localhost/pizzerie_riunite/#/visualizzaLista/" + $.cookie("nomePizzeria"); 
}

//Funzione che permette la corretta eliminazione di una pizzeria 
function eliminaCliente(){
        window.location.href = "http://localhost/pizzerie_riunite/#/eliminaCliente/" + $.cookie("email_Utente"); 
}   

//Funzione che permette la corretta eliminazione di una pizzeria 
function eliminaPizzeria(){
        window.location.href = "http://localhost/pizzerie_riunite/#/eliminaPizzeria/" + $.cookie("nomePizzeria"); 
}   

//Funzione che permette di ritornare alla pagina iniziale, viene richiamata per esempio dopo che la pizzeria è stata eliminata e quindi
//L'utente viene invitato a effettuare il login o una nuova registrazione
function paginaIniziale(){
        window.location.href = "http://localhost/pizzerie_riunite/#/";
}   



//funzione per tornare alla visualizzazione delle prenotazioni
function visualizzaPrenotazioni(){
        window.location.href = "http://localhost/pizzerie_riunite/#/visualizzaPrenotazioni/" + $.cookie("email_Utente"); 
}
//funzione per andare alla creazione di una prenotazione
function creaPrenotazione(){
        window.location.href = "http://localhost/pizzerie_riunite/#/creaPrenotazione/" + $.cookie("email_Utente"); 
}



myApp.config(function($routeProvider){
    $routeProvider

//controller che gestisce il login
        .when('/', {
            templateUrl : 'Templates/login.html',
            controller : 'loginCtrl'
        })


        //Controller relativo alla visualizzazione Lista, viene passato anche il nome della pizzeria sull' URL
        .when('/visualizzaLista/:nomePizzeria', { 
            templateUrl : 'Templates/visualizzaLista.html',
            controller : 'visualizzaCtrl'
        })

//controller che gestisce la registrazione
        .when('/registrazione', {
            templateUrl : 'Templates/registrazione.html',
            controller : 'registrazioneCtrl'
        })

//controller che gestisce la visualizzazione delle Prenotazioni
		.when('/visualizzaPrenotazioni/:email_Utente', {
		templateUrl : 'Templates/visualizzaPrenotazioni.html',
		controller : 'visualizzaPrenotazioniCtrl'
		})

//controller che gestisce l'eliminazione delle Prenotazioni
		.when('/eliminaPrenotazione/:id', {
		templateUrl : 'Templates/eliminaPrenotazione.html',
		controller : 'eliminaPrenotazioneCtrl'
		})

//controller che gestisce la modifica delle Prenotazioni
		.when('/modificaPrenotazione/:id', {
		templateUrl : 'Templates/modificaPrenotazione.html',
		controller : 'visualizzaPrenotazioneCtrl'
		})

//controller che gestisce la creazione delle Prenotazioni
        .when('/creaPrenotazione/:email_Utente',{
            templateUrl : 'Templates/creaPrenotazione.html',
            controller : 'creaPrenotazioneCtrl'
        })

        //Controller relativo alla creazione di un nuovo giorno, viene passato il nome della pizzeria sull'URL
        .when('/creaGiorno/:nomePizzeria', {
            templateUrl: 'Templates/creaGiorno.html',
            controller: 'creaGiornoCtrl'
        })

        //Controller per la modifica del giorno passato tramite URL
        .when('/modificaGiorno/:giorno', {
            templateUrl: 'Templates/modificaGiorno.html',
            controller: 'visualizzaGiornoCtrl'
        })

        //Controller per l'eliminazione del giorno passato tramite URL
        .when('/eliminaGiorno/:giorno', {
            templateUrl: 'Templates/eliminaGiorno.html',
            controller: 'eliminaGiornoCtrl'
        })

        //Controller per la cancellazione della pizzedria passata tramite URL
        .when('/eliminaPizzeria/:nomePizzeria', {
            templateUrl : 'Templates/eliminaPizzeria.html',
            controller : 'eliminaPizzeriaCtrl'
        })

        //Controller per la cancellazione del cliente
        .when('/eliminaCliente/:email_Utente', {
            templateUrl : 'Templates/eliminaCliente.html',
            controller : 'eliminaClienteCtrl'
        })

        .otherwise({
            //Se l'utente inserisce manualmente un url non presente si effettua il redirect alla pagina iniziale
            redirectTo: '/'
        })


		
});



//Definisco il controller per la visualizzazione, creando una funzione che ha come parametri lo 
//$scope, che permette di mettere in comunicazione il controller con l'html, $http per l'inivio e la ricezione di dati Online
//e $routeParams per ottenere i dati dall'URL
myApp.controller("visualizzaCtrl", function($scope, $http, $routeParams){

    //Apro una richiesta HTTP specificando URL, parametro aggiuntivo (nome pizzeria) e specifico il metodo Get
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/getDatiPizzeria.php",
        /*Specifico che il parametro giorno è uguale al parametro ottenuto dall'URL passato dalla pagina precedente*/
        params: {nomePizzeria:$routeParams.nomePizzeria},
        method: "get"

    //Se la richiesta ha successo allora, tramite response che contiene il valore di ritorno del file getDatiPizzeria.php (di tipo JSON) 
    //Ottengo i dati dall'oggetto JSON ottenuto
    }).then(function(response){
        $scope.posts = response.data;
        $("#pizzeria").html($.cookie("nomePizzeria"));
        console.log($scope.posts);

    });

});


/*Controller quando si clicca Modifica  sul singolo giorno */
myApp.controller("visualizzaGiornoCtrl", function($scope, $http, $routeParams){
   
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/modificaGiorno.php",
        /*Specifico che il parametro giorno è uguale al parametro ottenuto dall'URL passato dalla pagina precedente e ottenuto tramite GET*/
        params: {nomephp: $.cookie("nomePizzeria"), giorno:$routeParams.giorno},
        method: "get"

    }).then(function(response){

        //Lavoro con il json ritornato ottenendo il contenuto del campo 'data'
        $scope.posts = response.data;
        console.log($scope.posts);
    });

});


//Definisco il controllo per l'eliminazione di un giorno
myApp.controller("eliminaGiornoCtrl", function($scope, $http, $routeParams){
   
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/eliminaGiorno.php",
        /*Specifico che il parametro giorno è uguale al parametro ottenuto dall'URL passato dalla pagina precedente e ottenuto tramite GET*/
        params: {nomephp: $.cookie("nomePizzeria"), giorno:$routeParams.giorno},
        method: "get"

    }).then(function(response){

        $scope.posts = response;
        console.log($scope.posts);

    });

});


//Definisco il controllo per l'eliminazione di una pizzeria
myApp.controller("eliminaPizzeriaCtrl", function($scope, $http, $routeParams){
   
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/eliminaPizzeria.php",
        //Specifico che il parametro giorno è uguale al parametro ottenuto dall'URL passato dalla pagina precedente e ottenuto tramite Get
        params: {nomephp: $.cookie("nomePizzeria")},
        method: "get"

    }).then(function(response){
        $scope.posts = response;
        console.log($scope.posts);

    });

});

//Definisco il controllo per l'eliminazione di una pizzeria
myApp.controller("eliminaClienteCtrl", function($scope, $http, $routeParams){
   
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/eliminaCliente.php",
        //Specifico che il parametro giorno è uguale al parametro ottenuto dall'URL passato dalla pagina precedente e ottenuto tramite Get
        params: {nomephp: $.cookie("email_Utente")},
        method: "get"

    }).then(function(response){
        $scope.posts = response;
        console.log($scope.posts);

    });

});




//Definisco il controller CreaGiorno passando solo $scope come parametro della funzione
myApp.controller("creaGiornoCtrl", function($scope){

    //Al click del bottone eseguo la funzione
    $("#submit").click(function(){

        //Ottengo tutti i parametri necessari da passare al php per eseguire una corretta query
        var giorno = $("#date").val();
        var nPostiTotali = $("#nPostiTotali").val();
        var numero_Tavoli = $("#nTavoli").val();
        var oraApertura =  $("#oraApertura").val();
        var oraChiusura = $("#oraChiusura").val();

        console.log(oraApertura);

        //Divido l'orario inserito dall'utente in ora e minuti per un migliore controllo
        var orarioApertura = oraApertura.split(":");
        var ora_apertura_pizzeria = parseInt(orarioApertura[0]);
        var minuti_apertura_pizzeria = parseInt(orarioApertura[1]);

        console.log(ora_apertura_pizzeria);


        var orarioChiusura = oraChiusura.split(":");
        var ora_chiusura_pizzeria = parseInt(orarioChiusura[0]);
        var minuti_chiusura_pizzeria = parseInt(orarioChiusura[1]);

        console.log(ora_chiusura_pizzeria);


        //Controllo che l'utente non abbia lasciato il giorno vuoto
        if(giorno == ""){
            $("#msgError").html("Inserisci un giorno! ");
            console.log('Scegli una data');

        //Controllo che l'utente abbia inserito un numero positivo di posti e che non abbia lasciato vuoto il campo
        } else if(nPostiTotali < 0 || nPostiTotali == ""){
            console.log('Errore posti totali');
            $("#msgError").html("Inserire un corretto numero di posti totali");

        //Controllo che l'utente abbia inserito un numero positivo di tavoli e che non abbia lasciato vuoto il campo
        } else if (numero_Tavoli < 0 || numero_Tavoli == ""){
            console.log('Errore numero tavoli');
            $("#msgError").html("Inserire un corretto numero di tavoli");

        //Controllo che l'orario inserito dall'utente non sia vuoto, successivamente si controlla se i minuti/ore singole siano vuote
        } else if(oraApertura == "" || oraChiusura == "" || ora_apertura_pizzeria == "" || minuti_apertura_pizzeria == ""  || ora_chiusura_pizzeria == ""  || minuti_chiusura_pizzeria == ""){
            console.log('Errore orario');
            $("#msgError").html("Inserisci un corretto orario.");

        //Controllo che l'orario apertura della pizzeria sia maggiore dell'orario di chiusura di un determinato giorno
        } else if(ora_apertura_pizzeria > ora_chiusura_pizzeria){
            console.log('Errore orario ORA');
            $("#msgError").html("Errore, ora apertura maggiore dell'ora chiusura.");

        //Nel caso in cui l'orario di apertura fosse uguale a quello di chiusura allora controllo i minuti
        } else if(ora_apertura_pizzeria == ora_chiusura_pizzeria){
            //Se i minuti dell'apertura sono maggiori o uguali a quelli relativi alla chiusura allora ritorno un errore
            if(minuti_apertura_pizzeria >= minuti_chiusura_pizzeria){
                console.log('Errore minuti');
                $("#msgError").html("Inserisci un corretto orario. Ora di apertura maggiore uguale dell'ora di chiusura.");

            } else {
                //Se anche i minuti sono corretti, allora inserisco il giorno all'interno del Database 
                $.ajax({
                    type: 'POST',
                    url: 'http://127.0.0.1/pizzerie_riunite/webservices/creaGiorno.php',
                    dataType : 'json',

                    //Passo tutti i dati per eseguire una corretta query
                    data : {nomePizzeria : $.cookie("nomePizzeria"), data_scelta : giorno, numPostiTotali  : nPostiTotali, numeroTavoli :numero_Tavoli,
                            ora_apertura : oraApertura, ora_chiusura : oraChiusura },
                    cache: false,

                    //Se la query ha successo, allora ottengo i dati da result.data e li mostrerò nell'html
                    success : function(result){
                        var nPostiTotali = $("#nPostiTotali").val("");
                        var numeroTavoli = $("#nTavoli").val("");

                        $scope.posts = result.data;

                        $("#msgError").html($scope.posts);
                        

                    }, 

                    fail: function(result){
                        $("#msgError").html("Errore nella Query ");

                    }
                });
            }
            
        } else {

            //Effettuo la stessa cosa nel caso in cui i minuti siano corretti
             $.ajax({
                    type: 'POST',
                    url: 'http://127.0.0.1/pizzerie_riunite/webservices/creaGiorno.php',
                    dataType : 'json',
                    data : {nomePizzeria : $.cookie("nomePizzeria"), data_scelta : giorno, numPostiTotali  : nPostiTotali, numeroTavoli :numero_Tavoli,
                            ora_apertura : oraApertura, ora_chiusura : oraChiusura },
                    cache: false,
                    success : function(result){
                        var nPostiTotali = $("#nPostiTotali").val("");
                        var numeroTavoli = $("#nTavoli").val("");

                        $scope.posts = result.data;

                        $("#msgError").html($scope.posts);
                        

                    }, 

                    fail: function(result){
                        $("#msgError").html("Errore nella Query ");

                    }
                });
             return false;
        }

      
    });

});



//Per i commenti riferirsi al caso precedente, è molto simile
myApp.controller("aggiornaGiornoCtrl", function($scope){
    $("#aggiorna").click(function(){

        var giorno = $("#giorno").text();
        var nPostiAggiunti = $("#nPostiAggiunti").val();
        var numero_Tavoli = $("#nTavoliAggiunti").val();
        var oraApertura =  $("#oraApertura").val();
        var oraChiusura = $("#oraChiusura").val();

        console.log(oraApertura);

        var orarioApertura = oraApertura.split(":");
        var ora_apertura_pizzeria = parseInt(orarioApertura[0]);
        var minuti_apertura_pizzeria = parseInt(orarioApertura[1]);

        console.log(ora_apertura_pizzeria);


        var orarioChiusura = oraChiusura.split(":");
        var ora_chiusura_pizzeria = parseInt(orarioChiusura[0]);
        var minuti_chiusura_pizzeria = parseInt(orarioChiusura[1]);

        console.log(ora_chiusura_pizzeria);



        if(nPostiAggiunti == ""){
            console.log('Errore posti aggiunti');
            $("#msgError").html("Inserire un corretto numero di posti aggiunti");
        } else if (numero_Tavoli == ""){
            console.log('Errore numero tavoli');
            $("#msgError").html("Inserire un corretto numero di tavoli");
        } else if(oraApertura == "" || oraChiusura == "" ){
            console.log('Errore orario');
            $("#msgError").html("Inserisci un corretto orario.");

        } else if(oraApertura == "" || oraChiusura == "" || ora_apertura_pizzeria == "" || minuti_apertura_pizzeria == ""  || ora_chiusura_pizzeria == ""  || minuti_chiusura_pizzeria == ""){

            console.log('Errore orario');
            $("#msgError").html("Inserisci un corretto orario.");

        } else if(ora_apertura_pizzeria > ora_chiusura_pizzeria){

            console.log('Errore orario ORA');
            $("#msgError").html("Errore, ora apertura maggiore dell'ora chiusura.");


        } else if(ora_apertura_pizzeria == ora_chiusura_pizzeria){
            
            if(minuti_apertura_pizzeria >= minuti_chiusura_pizzeria){
                console.log('Errore minuti');
                $("#msgError").html("Inserisci un corretto orario. Ora di apertura maggiore uguale dell'ora di chiusura.");

            } else {

                $.ajax({
                    type: 'POST',
                    url: 'http://127.0.0.1/pizzerie_riunite/webservices/aggiornaDati.php',
                    dataType : 'json',
                    data: {nomePizzeriaPhp : $.cookie("nomePizzeria"), data_scelta : giorno, numPostiAggiunti : nPostiAggiunti, nTavoliAggiunti : numero_Tavoli,
                            ora_apertura : oraApertura, ora_chiusura : oraChiusura },
                    cache: false,
                    success : function(result){


                        $scope.posts = result.Risultato;
                        console.log($scope.posts);

                        $("#msgError").html($scope.posts);
                    }, 

                    fail: function(result){
                        console.log("ERRORE");
                        $("#msgError").html("La data scelta è già presente ! ");
                    },
                    error: function(req, err){ console.log('ERRORE: ' + err); }
                });
        }
        
    } else {
        $.ajax({
                    type: 'POST',
                    url: 'http://127.0.0.1/pizzerie_riunite/webservices/aggiornaDati.php',
                    dataType : 'json',
                    data: {nomePizzeriaPhp : $.cookie("nomePizzeria"), data_scelta : giorno, numPostiAggiunti : nPostiAggiunti, nTavoliAggiunti : numero_Tavoli,
                            ora_apertura : oraApertura, ora_chiusura : oraChiusura },
                    cache: false,
                    success : function(result){


                        $scope.posts = result.Risultato;
                        console.log($scope.posts);

                        $("#msgError").html($scope.posts);
                    }, 

                    fail: function(result){
                        console.log("ERRORE");
                        $("#msgError").html("La data scelta è già presente ! ");
                    },
                    error: function(req, err){ console.log('ERRORE: ' + err); }
                });

    }
    return false;
    });

});



myApp.controller("loginCtrl", function($scope){
    //aspetta il click sul bottone
    $("#loginUtente").click(function(){

        //si salva le credenziali inserite dall'utente e fà dei controlli preliminari
       var emailUtenteLogin = $("#emailUtente").val();
       var passwordUtente = $("#passwordUtente").val();

        if(emailUtenteLogin == ""){
            $("#rispostaServizio").html("Inserisci una mail !  ");
        } else if(passwordUtente == ""){
            $("#rispostaServizio").html("Inserisci una password ! ");
        } 
        
        //chiamo il file php che deve interrogare il server per il match delle credenziali
        else {
            $.ajax({
                type: 'POST',
                url: 'http://127.0.0.1/pizzerie_riunite/webservices/login_utente.php',
                dataType : 'json',
                data : {emailUtentePhp : emailUtenteLogin, passwordUtentePhp  : passwordUtente},
                cache: false,
                success : function(result){

                    $scope.posts = result.info;
                    console.log($scope.posts);  

                    $("#rispostaServizio").html($scope.posts);

                    if($scope.posts == "Login avvenuto con successo"){

                        //Il login è avvenuto con successo, salvo il nome della pizzeria nei Cookie per poterlo utilizzare nuovamente
                        $.cookie('email_Utente', emailUtenteLogin);
                        window.location.href = "http://localhost/pizzerie_riunite/#/visualizzaPrenotazioni/" + $.cookie("email_Utente") ;
                    } 

                }, 

                fail: function(result){
                    $("#rispostaServizio").html("Errore JQuery");

                }
            });
        }
        return false;
    });

    $("#loginPizzeria").click(function(){

       //Ottengo le credenziali inserite dall'utente
       var nomePizzeriaLogin = $("#nomePizzeriaLogin").val();
       var passwordPizzeria = $("#passwordLogin").val();

       //Controllo che il nome della pizzeria non sia vuoto (l'utente può inserire anche numeri, tanto la query darà comunque errore)
        if(nomePizzeriaLogin == ""){
            $("#rispostaServizio").html("Inserisci un nome !");

        //Controllo che la password inserita non sia vuota
        } else if(passwordPizzeria == ""){
            $("#rispostaServizio").html("Inserisci una password !");
        } 
        
        //Se tutti i parametri precedenti sono accettati allora effettuo la query
        else {
            $.ajax({
                type: 'POST',
                url: 'http://127.0.0.1/pizzerie_riunite/webservices/loginPizzeria.php',
                dataType : 'json',
                data : {nomePizzeriaPhp : nomePizzeriaLogin, passwordPhp  : passwordPizzeria},
                cache: false,
                success : function(result){

                    $scope.posts = result.info;
                    console.log($scope.posts);  

                    //Inserisco sull'HTML il testo all'interno del valore 'info' del json ritornato dal php
                    $("#rispostaServizio").html($scope.posts);

                    if($scope.posts == "Login avvenuto con successo"){

                        //Il login è avvenuto con successo, salvo il nome della pizzeria nei Cookie per poterlo utilizzare nuovamente
                        $.cookie('nomePizzeria', nomePizzeriaLogin);

                        window.location.href = "http://localhost/pizzerie_riunite/#/visualizzaLista/" + $.cookie("nomePizzeria") ;
                    } 

                }, 

                fail: function(result){
                    $("#rispostaServizio").html("Errore JQuery");

                }
            });
        }
        return false;
    });

});

myApp.controller("visualizzaPrenotazioniCtrl", function($scope, $http, $routeParams){

    //chiama il file php che prende dal database tutte le prenotazioni di quell'utente
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/prenotazioni.php",
        
        params: {email_Utente : $routeParams.email_Utente},
        method: "get"

    }).then(function(response){
        $scope.posts = response.data;
        console.log($scope.posts);

    });

});


myApp.controller("eliminaPrenotazioneCtrl", function($scope, $http, $routeParams){
    //chiama il file php che và a ricercare la prenotazione da eliminare nel db e procede all'eliminazione
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/eliminaPrenotazione.php",
        
        params: {id:$routeParams.id},
        method: "get"

    }).then(function(response){
        $scope.posts = response;
        console.log($scope.posts);

    });

});

myApp.controller("registrazioneCtrl", function($scope){
    //attende il click sul bottone
    $("#registrazioneUtente").click(function(){
        //si salva i dati inseriti all'utente e fà dei controlli preliminari
        var emailCliente = $("#emailUtenteRegistrazione").val();
        var passwordCliente = $("#passwordUtenteRegistrazione").val();
        var nomeCliente = $("#nomeUtenteRegistrazione").val();
        var cognomeCliente = $("#cognomeUtenteRegistrazione").val();
        var indirizzoCliente = $("#indirizzoUtenteRegistrazione").val();


        if(emailCliente == "" || (emailCliente.indexOf("@")==-1)){
            $("#rispostaServizio").html("Inserisci una mail valida!")
        } else if(passwordCliente == ""){
            $("#rispostaServizio").html("Inserisci una password valida");
        } else if (indirizzoCliente == "" || $.isNumeric(indirizzoCliente)){
            $("#rispostaServizio").html("Inserisci un indirizzo valido!");
        } else if (nomeCliente == "" || $.isNumeric(nomeCliente)){
            $("#rispostaServizio").html("Inserisci un nome valido!");
        } else if (cognomeCliente == "" || $.isNumeric(cognomeCliente)){
            $("#rispostaServizio").html("Inserisci un cognome valido!");
        }

        else {
            //passa i dati al php che li andrà ad inserire nel database per registrare l'utente
            $.ajax({
                type: 'POST',
                url: "http://127.0.0.1/pizzerie_riunite/webservices/registrazione_cliente.php",
                dataType : 'json',
                data : {emailClientePhp : emailCliente, passwordClientePhp  : passwordCliente, indirizzoClientePhp : indirizzoCliente, nomeClientePhp : nomeCliente, cognomeClientePhp : cognomeCliente },
                cache: false,

                success : function(result){

                    $scope.posts = result.info;
                    console.log($scope.posts);
                    $("#rispostaServizio").html($scope.posts);
                }, 

                fail: function(result){
                    $("#rispostaServizio").html("Errore su JSOn ! ");

                }
            });
        }
        return false;
    });




    //Se l'utente clicca il bottone della registrazione allora:
    $("#registrazionePizzeria").click(function(){

        //Ottengo le credenziali inserite dall'utente
        var nomePizzeria = $("#nomePizzeriaRegistrazione").val();
        var passwordPizzeria = $("#passwordRegistrazione").val();
        var indirizzo = $("#indirizzoRegistrazione").val();

        //Controllo che l'utente non abbia inserito un valore numerico e non abbia lasciato i campi vuoti
        if(nomePizzeria == "" || $.isNumeric(nomePizzeria)){
            $("#rispostaServizio").html("Inserisci un nome valido! ")

        //La password può comunque essere numerica
        } else if(passwordPizzeria == ""){
            $("#rispostaServizio").html("Inserisci una password valida");

        //Controllo che l'indirizzo non sia vuoto e non composto solo da numeri
        } else if (indirizzo == "" || $.isNumeric(indirizzo)){
            $("#rispostaServizio").html("Inserisci un indirizzo valido!");
        }

        //Nal caso in cui tutti i controlli vadano a buon fine effettuo la query
        else {
            $.ajax({
                type: 'POST',
                url: 'http://127.0.0.1/pizzerie_riunite/webservices/creaPizzeria.php',
                dataType : 'json',
                data : {nomePizzeriaPhp : nomePizzeria, passwordPhp  : passwordPizzeria, indirizzoPhp : indirizzo },
                cache: false,
                success : function(result){

                    $scope.posts = result.info;
                    console.log($scope.posts);
                    $("#rispostaServizio").html($scope.posts);
                }, 

                fail: function(result){
                    $("#rispostaServizio").html("Errore su JSOn ! ");

                }
            });
        }
        return false;
    });

});

myApp.controller("modificaPrenotazioneCtrl", function($scope){
    //attende il click sul bottone
    $("#aggiornaPrenotazione").click(function(){
        //salvo i dati inseriti dall'utente nella form e faccio dei controlli preliminari
        var id = $("#numero_prenotazione").text();
        

        var nuovi_posti = $("#nPostiNuovi").val();
        var orario = $("#orarioNuovo").val();

        if(orario == ""){
            console.log('Errore orario');
            $("#msgError").html("Inserire un orario valido");
        } else if (nuovi_posti == "" || nuovi_posti == '0'){
            console.log('Errore numero posti');
            $("#msgError").html("è necessario prenotare almeno un posto");
        }


        else {
            //invio i dati al php il quale dovrà controllare la correttezza dei dati e poi dovrà procedere all'aggiornamento
            $.ajax({
                type: 'POST',
                url: 'http://127.0.0.1/pizzerie_riunite/webservices/modificaPrenotazione.php',
                dataType : 'json',
                data: {id: id, postiDaSostituire : nuovi_posti, orario : orario},
                cache: false,
                success : function(result){

                    $scope.posts = result.Risultato;
                    console.log($scope.posts);

                    $("#msgError").html($scope.posts);
                }, 

                fail: function(result){
                    console.log("ERRORE");
                    $("#msgError").html("La data scelta è già presente ! ");
                },
                error: function(req, err){ console.log('ERRORE: ' + err); }
            });
        }
        return false;
    });

});

myApp.controller("visualizzaPrenotazioneCtrl", function($scope, $http, $routeParams){
   //visualizza a schermo un'unica prenotazione, viene utilizzato per la schermata di modifica
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/prenotazione.php",
        params: {id:$routeParams.id},
        method: "get"

    }).then(function(response){
        $scope.posts = response;
        console.log($scope.posts);
    });

});

myApp.controller("creaPrenotazioneCtrl", function($scope, $http, $routeParams){
    //interroga il database per elencare le pizzerie
    $http({
        url: "http://127.0.0.1/pizzerie_riunite/webservices/elencoPizzerie.php",
        method: "get"

    }).then(function(response){
        $scope.posts = response.data.data;
        console.log($scope.posts);
    });
    //attende il clik sul bottone 
    $("#prenota").click(function(){
        //salva i dati immessi e fà dei controlli
        var cliente = $.cookie("email_Utente");

        var luogo = $("#luogo").val();
        var data = $("#date").val();
        var postiP = $("#nPosti").val();
        var orario = $("#orarioN").val();

        if(luogo == ""){
            console.log('Errore luogo');
            $("#msgError").html("Inserire una pizzeria valida");
        } else if(data == ""){
            console.log('Errore data');
            $("#msgError").html("Inserire una data valida");
        }else if (postiP == "" || postiP == '0'){
            console.log('Errore numero posti');
            $("#msgError").html("è necessario prenotare almeno un posto");
        }else if(orario == ""){
            console.log('Errore orario');
            $("#msgError").html("Inserire un'orario valido");
        }

        else{
            //passa i dati al database il quale controlla se è possibile effettuare la prenotazione con quei dati, in caso positivo la esegue
            $.ajax({

                type: 'POST',
                url: 'http://127.0.0.1/pizzerie_riunite/webservices/creaPrenotazione.php',
                dataType : 'json',
                data: {cliente : cliente, luogo : luogo, data : data, postiP : postiP, orario : orario},
                cache: false,
                success : function(result){

                    $scope.posts = result.Risultato;
                    console.log($scope.posts);

                    $("#msgError").html($scope.posts);
                }, 

                fail: function(result){
                    console.log("ERRORE");
                    $("#msgError").html("La data scelta è già presente ! ");
                },
                error: function(req, err){ console.log('ERRORE: ' + err); }
            });
        }
        return false;           


        });

    });

