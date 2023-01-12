
    function createXmlHttpRequestObject() 
    {
        var request;
  
        try
        {
            request = new XMLHttpRequest(); // should work on all browsers except IE6 or older
        } 
        catch (e) 
        { 
            try 
            {
                request = new ActiveXObject("Microsoft.XMLHttp"); // browser is IE6 or older
            }
            catch (e) 
            {
                // ignore error
            }
        }
  
        if (!request) 
        {
            alert("Error creating the XMLHttpRequest object.");
        } 
        else 
        {
            return request;
        }
    }
    
    function najitRozdilOdPondeli()
    {
        var datum = new Date();
        var dniPoPondelku = datum.getDay(); // Funkce pro pondělí vrátí 1 
                                            // a pro neděli 0
        console.log("GetDay vrací: " + dniPoPondelku);
        if(dniPoPondelku == 0){
            dniPoPondelku = 6;
        }else{
            dniPoPondelku -= 1;
        }
        return dniPoPondelku;
    }
    
    function zmensitMesic(mesicPondeli)
    {
        mesicPondeli -= 1;
        if(mesicPondeli < 1){
            mesicPondeli = 12;
        }
        return mesicPondeli;
    }
    
    function najitPondeli()
    {
        var rozdilOdPondeli = najitRozdilOdPondeli();
        // Format je "2022-12-27" rok-mesic-datum
        var datum = new Date();
        
        var aktualniDen = datum.getDate();
        console.log("a" + datum.getDate());
        var aktualniMesic = 1 + datum.getMonth();
        var aktualniRok = datum.getFullYear();
        
        var denPondeli = 0;
        var mesicPondeli = 0;
        var rokPondeli = 0;
        
        if((aktualniDen - rozdilOdPondeli) < 1){
            if(mesic == 1|| mesic ==  3|| mesic == 5|| mesic == 7|| mesic == 8|| mesic == 10|| mesic == 12){
                denPondeli = 31 - (aktualniDen - rozdilOdPondeli);
                mesicPondeli = aktualniDen - 1 ;
                if(mesicPondeli < 1){
                    mesicPondeli = 12; 
                    rokPondeli = aktualniRok - 1;
                }
            }
            
            if(mesic ==  4 || mesic ==  6 || mesic ==  9 || mesic ==  11){
                denPondeli = 30 - (aktualniDen - rozdilOdPondeli);
                mesicPondeli = aktualniDen - 1;
                if(mesicPondeli < 1){
                    mesicPondeli = 12; 
                    rokPondeli = aktualniRok - 1;
                }
            }
            
            if(mesic ==  2){
                denPondeli = 28 - (aktualniDen - rozdilOdPondeli); 
                mesicPondeli = aktualniMesic - 1;
                if(mesicPondeli < 1){
                    mesicPondeli = 12; 
                    rokPondeli = aktualniRok - 1;
                }
            }
        }else{
            denPondeli = aktualniDen - rozdilOdPondeli;
            mesicPondeli = aktualniMesic;
            rokPondeli = aktualniRok;
        }
        
        return '' + rokPondeli + '-' + mesicPondeli + '-' + denPondeli;
    }
    
    function uriznoutCas(cas)
    {
        var myCas = '' + cas;
        myArrayCas = myCas.split(":");
        return myArrayCas[0];
    }
    
    function datumPrvnihoDneTydnePlus(datumPrvnihoDneTydne){
        var casRozdelen = datumPrvnihoDneTydne.split("-");
        var rok = parseInt(casRozdelen[0]);
        var mesic =  parseInt(casRozdelen[1]);
        var den = parseInt(casRozdelen[2]);
        if(den == 31){
            mesic += 1;
            if(mesic == 13){
                mesic = 1;
                rok += 1;
            }
            den = 1;
        }else{
            den += 1;
        }
        return rok + "-" + mesic + "-" + den ;
    }

	function prepsatRozvrh(responseText, datumPrvnihoDneTydne)
	{
		const t = JSON.parse(responseText);
		// document.getElementById("Monday8").innerHTML = "";
		
		nazvyId = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
		
		for(let IdDen = 0; IdDen < nazvyId.length; IdDen++){ // Pro kazdy den
		    datumPrvnihoDneTydne = datumPrvnihoDneTydne;
		    for(let IdNum = 8; IdNum < 17; IdNum++){ // Pro kazdy cas v dni
		        var id = nazvyId[IdDen] + IdNum;
		        for(let i = 0; i < t.length; i++){
		            if(IdNum == uriznoutCas(t[i].cas)){
		                if(t[i].datum === datumPrvnihoDneTydne){
		                    document.getElementById(id).innerHTML = '';
		                    document.getElementById(id).innerHTML += t[i].jmeno; 
		                    console.log("id: " + id);
		                    // Prepis v rozvrhu polozku na jmeno
		                }
		            };
		        }
		     }
		     // Prida 1 do data
		     datumPrvnihoDneTydne = datumPrvnihoDneTydnePlus(datumPrvnihoDneTydne); 
		}
	}    

	function downloadData() 
	{
	   // 	document.getElementById("status").innerHTML = "downloadData()";

		var request = createXmlHttpRequestObject();
        var jmeno = "Jan Novák";
        var datumPrvnihoDneTydne = najitPondeli();
	    	request.open('GET', 'http://iturezervacnisystem.wz.cz/backend.php?druhRozhrani=fyzioterapeut&rozvrh=ano&jmeno=' + jmeno + '&datum-prvniho-dne-tydne=' + datumPrvnihoDneTydne, true);
            console.log('http://iturezervacnisystem.wz.cz/backend.php?druhRozhrani=fyzioterapeut&rozvrh=ano&jmeno='+jmeno+'&datum-prvniho-dne-tydne='+datumPrvnihoDneTydne);
	     	request.onreadystatechange = function() 
	        {
	        	if ((request.readyState == 4) && (request.status == 200)) 
	            {
					const myArray = request.responseText.split("<!--WZ-REKLAMA-1.0IK-->")
				    prepsatRozvrh(myArray[1], datumPrvnihoDneTydne);
	            }
	
	        }
		request.send("a");
	}
	datumPrvnihoDneTydnePlus("2022-12-12");
    setInterval(downloadData, 2000);
   