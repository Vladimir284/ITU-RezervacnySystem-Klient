<?php
    // Načtení externiho souboru pro nacteni databaze
    include_once 'dbh.inc.php'; // Napojeni na databazi
    
    function pseudonahodneCislo($konstanta, $datum)
    {
        // Vezmi z data cislo
        $pole  = explode("-", $datum); // Rozdělím $cas na pole {"2022", "12", "14"}
        $den   = (int) $pole[2];
        $noveCislo = ($konstanta * $den * 7) % 100;
        
        return $noveCislo;
    }
    
    function SQLformatCasuDoJSONformatu($cas){
        $pole  = explode("-", $cas); // Rozdělím $cas na pole {"2022", "12", "01"}
        $rok   = (int) $pole[0]; // Překonvertuji z řetězce na čisla
        $mesic = (int) $pole[1];
        $den   = (int) $pole[2];
        
        return $rok."-".$mesic."-".$den;
    }
    
    function vypisPodsluzeb($conn, $nazev){
            // Vypiš mi všechny podslužby 
            // Vypíše všechny kategorie služeb v databazii
            $prikaz_k_vypisu = "SELECT jmeno FROM iturezervacn9298.sluzba WHERE kategorie = '".$nazev."';";
            $sluzby_result = mysqli_query($conn, $prikaz_k_vypisu);
            $resultCheck = mysqli_num_rows($sluzby_result); // Údaj pro kontrolu práce s databází
        
            if($resultCheck !== 0){ // Pokud se dotaz provedl správně
                print("[");         
                while($row_all = mysqli_fetch_assoc($sluzby_result)){
                    // Vypis kategorie
                    echo '"'.$row_all['jmeno'].'"';
                    // Pokud není to poslední, tak dam carku
                    if($resultCheck - 1 > 0){
                        echo ', ';
                        $resultCheck = $resultCheck - 1;
                    }
                }
                print("]"); 
            }
    }
    

    // Vypise datum pro konec týdne
    function casPlus7($cas){
        $pole  = explode("-", $cas); // Rozdělím $cas na pole {"2022", "12", "01"}
        $rok   = (int) $pole[0]; // Překonvertuji z řetězce na čisla
        $mesic = (int) $pole[1];
        $den   = (int) $pole[2];
        
        if(($den + 7) > 31){      // Jestli datum přečuhuje, 
            $den = $den + 7 - 31; // tak ho nastavím na dalsí mesic
            $mesic = $mesic + 1;
            if($mesic > 12){
                $mesic = 1;
                $rok = $rok + 1;
            }
        }else{
            $den = $den + 7; // Jinak proste pridam 7
        }
        
        return $rok."-".$mesic."-".$den;
    }
    
    function vypsatMapuAdminivo($jmeno, $cas, $conn) {
        // Dotaz pro vypsani terminu fyzioterapeuta
        $sql_query_all = "SELECT datum, cas, pacient.jmeno, mistnost 
        FROM iturezervacn9298.rezervace, iturezervacn9298.sluzba, 
        iturezervacn9298.zamestnanec, iturezervacn9298.pacient 
        WHERE iturezervacn9298.rezervace.idsluzba=iturezervacn9298.sluzba.id 
        AND iturezervacn9298.rezervace.idzamestnanec=iturezervacn9298.zamestnanec.id 
        AND iturezervacn9298.rezervace.idpacient=iturezervacn9298.pacient.id 
        AND iturezervacn9298.zamestnanec.jmeno ='".$jmeno."' AND 
        iturezervacn9298.rezervace.datum > '".$cas."' AND 
        iturezervacn9298.rezervace.datum < '".casPlus7($cas)."';";
        $select_result = mysqli_query($conn, $sql_query_all);
         
        $resultCheck = mysqli_num_rows($select_result);
        
        // Jestli tam neni, tak ho pridam
        if($resultCheck !== 0){ // Pokud se dotaz provedl správně
            print("[");             // Vypiš všechno
            while($row_all = mysqli_fetch_assoc($select_result)){
                echo '{"jmeno": "'.$row_all['jmeno'].'", "datum": "'.$row_all['datum'].'", "cas": "'.$row_all['cas'].'", "mistnost": "'.$row_all['mistnost'].'"}';
                // Pokud není to poslední, tak dam carku
                if($resultCheck - 1 > 0){
                    echo ', ';
                    $resultCheck = $resultCheck - 1;
                }
                
            }
            print("]");
         }else{
             print("[]");
         }
    } // Konec funkce
    
    function vypsatMapuFyzioteraperuta($jmeno, $cas, $conn) {
        // Dotaz pro vypsani terminu fyzioterapeuta
        $sql_query_all = "SELECT datum, cas, pacient.jmeno, mistnost, rezervace.id, sluzba.jmeno AS sluzba
        FROM iturezervacn9298.rezervace, iturezervacn9298.sluzba, 
        iturezervacn9298.zamestnanec, iturezervacn9298.pacient 
        WHERE iturezervacn9298.rezervace.idsluzba=iturezervacn9298.sluzba.id 
        AND iturezervacn9298.rezervace.idzamestnanec=iturezervacn9298.zamestnanec.id 
        AND iturezervacn9298.rezervace.idpacient=iturezervacn9298.pacient.id 
        AND iturezervacn9298.zamestnanec.jmeno ='".$jmeno."' AND 
        iturezervacn9298.rezervace.datum > '".$cas."' AND 
        iturezervacn9298.rezervace.datum < '".casPlus7($cas)."';";
        $select_result = mysqli_query($conn, $sql_query_all);
        $resultCheck = mysqli_num_rows($select_result);
        
        // Jestli tam neni, tak ho pridam
        if($resultCheck !== 0){ // Pokud se dotaz provedl správně
            print("[");             // Vypiš všechno
            while($row_all = mysqli_fetch_assoc($select_result)){
                echo '{"jmeno": "'.$row_all['jmeno'].'", "sluzba": "'.$row_all['sluzba'].'", "datum": "'.$row_all['datum'].'", "cas": "'.$row_all['cas'].'", "mistnost": "'.$row_all['mistnost'].'", "id": "'.$row_all['id'].'"}';
                // Pokud není to poslední, tak dam carku
                if($resultCheck - 1 > 0){
                    echo ', ';
                    $resultCheck = $resultCheck - 1;
                }
                
            }
            print("]");
         }else{
             print("[]");
         }
    } // Konec funkce

    // Začátek kódu mimo funkce
    $druhRozhrani = $_GET["druhRozhrani"]; 

    // Jestli je druhRozhrani fyzioterapeutův, tak hledej prikazy pro jeho rozhrani
    if (strcmp($druhRozhrani, "fyzioterapeut") == 0){ 
        $zprava = $_GET["zprava"];
        if(!empty($zprava)){
            // Načteme data
            $lekar = $_GET["lekar"];
            $kodlekare = $_GET["kodlekare"];
            $kodambulance = $_GET["kodambulance"];
            $idrezervace = $_GET["idrezervace"];
             
            // Vytvoříme si příkaz pro SQL
            
            // Pro atributy: id	 txt	lekar	kodlekare	kodambulance	idrezervace
            $prikaz_vlozeni_zpravy = "INSERT INTO iturezervacn9298.zpravy (`txt`, `lekar`, 
            `kodlekare`, `kodambulance`, `idrezervace`) 
            VALUES ('".$zprava."','".$lekar."', '".$kodlekare."', 
            ".$kodambulance.",  ".$idrezervace.");";
            
            // Spustíme příkaz v SQL
            mysqli_query($conn, $prikaz_vlozeni_zpravy);
            echo "Ok";
        }
                                             // Konec vlozeni zprávy //
 
        $rozvrh = $_GET["rozvrh"]; // Zjisteni zda chce uživatel mapu/rozvrh fyzioterapeuta
        
        // Pokud chce, tak chci vědět čí mám dát rozvrh a v kterém čase
        if (!empty($rozvrh)){
            $jmeno = $_GET["jmeno"]; 
            $cas = $_GET["datum-prvniho-dne-tydne"];
            
            // Příkaz pro SQL a vypsání dat bez místnosti 
            vypsatMapuFyzioteraperuta($jmeno, $cas, $conn);
        }
        
        $id = $_GET["idProDetailyRezervace"];
        if(!empty($id)){
            $sqlProDetailyRezervace = "SELECT poznamka, iturezervacn9298.sluzba.jmeno AS sluzba, iturezervacn9298.pacient.jmeno, cas, datum 
            FROM iturezervacn9298.rezervace, iturezervacn9298.pacient, iturezervacn9298.sluzba
            WHERE iturezervacn9298.rezervace.id = ".$id."
            AND idpacient = iturezervacn9298.pacient.id AND idsluzba = iturezervacn9298.sluzba.id;";
            
            $select_result  = mysqli_query($conn,   $sqlProDetailyRezervace);
            $resultCheck    = mysqli_num_rows($select_result);
        
            // Jestli tam neni, tak ho pridam
            echo '[';
            if($resultCheck !== 0){ // Pokud se dotaz provedl správně
                while($row_all = mysqli_fetch_assoc($select_result)){// Vypiš všechno
                    echo '{"jmeno": "'.$row_all['jmeno'].'", "datum": "'.$row_all['datum'].'", "cas": "'.$row_all['cas'].'", "poznamka": "'.$row_all['poznamka'].'", "sluzba": "'.$row_all['sluzba'].'"}';
                   if($resultCheck - 1 > 0){
                     echo ', ';
                     $resultCheck = $resultCheck - 1;
                   }
                
                }
            }else{
                 print('{"jmeno": "Petr Ticho", "datum": "2023-01-02", "cas": "11:00:00", "poznamka": "Mám od malička špatně srostlé kosti.", "sluzba": "Thajská masáž"}');
            }
            echo ']';
        }
        
        if($_GET["nasleduje"]){
            $jmeno = $_GET["jmeno"]; 
            $sql = "SELECT iturezervacn9298.sluzba.jmeno AS sluzba, iturezervacn9298.pacient.jmeno, cas, datum, iturezervacn9298.rezervace.id AS identi 
            FROM iturezervacn9298.rezervace, iturezervacn9298.pacient, iturezervacn9298.sluzba
            WHERE ((datum >= '".date("Y-m-d")."' AND cas >= '".date("H:i")."') OR (datum >= '".date("Y-m")."-".(date("d") + 1).".')) 
            AND idpacient = iturezervacn9298.pacient.id AND idsluzba = iturezervacn9298.sluzba.id 
            ORDER BY `rezervace`.`idpacient` ASC LIMIT 1;";
            
            $select_result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($select_result);
        
            // Jestli tam neni, tak ho pridam
            if($resultCheck !== 0){ // Pokud se dotaz provedl správně
                print("[");             // Vypiš všechno
                while($row_all = mysqli_fetch_assoc($select_result)){
                    echo '{"jmeno": "'.$row_all['jmeno'].'", "datum": "'.$row_all['datum'].'", "cas": "'.$row_all['cas'].'", "sluzba": "'.$row_all['sluzba'].'", "id": "'.$row_all['identi'].'"}';
                   if($resultCheck - 1 > 0){
                     echo ', ';
                     $resultCheck = $resultCheck - 1;
                   }
                
                }
                print("]");
            }else{
                 print("[]");
            }
        }
    }
    
    // Jestli je druhRozhrani zakaznikovo, tak hledej prikazy pro jeho rozhrani
    if (strcmp($druhRozhrani, "zakaznik") == 0){ 
        // Seznam služeb a podslužeb
        $vypissluzeb = $_GET["vypissluzeb"];
        if(!empty($vypissluzeb)){
            // Vypíše všechny kategorie služeb v databazii
            $prikaz_k_vypisu_kategorii = "SELECT kategorie FROM iturezervacn9298.sluzba GROUP BY kategorie";
            $kategorie_result = mysqli_query($conn, $prikaz_k_vypisu_kategorii);
            $resultCheck = mysqli_num_rows($kategorie_result); // Údaj pro kontrolu práce s databází
        
            if($resultCheck !== 0){ // Pokud se dotaz provedl správně
                print("[");         
                while($row_all = mysqli_fetch_assoc($kategorie_result)){
                    // Vypis kategorie
                    echo '{"kategorie": "'.$row_all['kategorie'].'", "podsluzby": ';
                    vypisPodsluzeb($conn, $row_all['kategorie']);
                    echo ' }';
                    
                    // Pokud není to poslední, tak dam carku
                    if($resultCheck - 1 > 0){
                        echo ', ';
                        $resultCheck = $resultCheck - 1;
                    }
                }
                print("]"); 
            }
        }
         
        // Odeslat rezervaci - Jméno, Email, Telefón, Poznámku, Službu, Zaměstnance - možná
        $pridatrezervaci = $_GET["pridatrezervaci"];
        
        if(!empty($pridatrezervaci)){
            //$mistnost = $_GET["mistnost"];
            $mistnost = "A114"; // Static, edit Vlada
            $datum = $_GET["datum"];
            $cas = $_GET["cas"];
            $delka = $_GET["delka"];
            $poznamka = $_GET["poznamka"];
            $idsluzba = $_GET["sluzba"];
            $idpacient = $_GET["pacient"];/*
            $idzamestnanec = $_GET["zamestnanec"];
            
            // Známe jméno pacienta
            // Do databáze ukládáme jeho číslo. Třeba ho zjistit.
            // Nejdříve zjistime, zda už máme pacienta v databázi
            // Pokud ano, použijeme jeho číslo, jinak ho přidáme a pak použijeme
            // jeho číslo
            
            $prikaz_k_vypisu_pacienta = "SELECT jmeno FROM iturezervacn9298.pacient 
            WHERE jmeno = '".$idpacient."';";
            $pocetZaznamuPacienta = mysqli_num_rows(mysqli_query($conn, $prikaz_k_vypisu_pacienta)); 
            // Údaj pro kontrolu práce s databází
            
            // Pokud v databázi mam pacienta, tak ho nepřidám. Pokud ne, tak ho přidam
            if($pocetZaznamuPacienta == 0){
                // Získání dat o pacientovi
                $telefon = $_GET["telefon"];
                $email = $_GET["email"];
                
                $insert_pacient = "INSERT INTO iturezervacn92986.pacient (`jmeno`, `email`, `telefon`) 
                VALUES ('".$pacient."','".$email."','".$telefon."');";
                
                mysqli_query($conn, $insert_pacient); // Přidáno do databáze
            }
            
            // Zjistím ID pacienta
            $prikaz_k_vypisu_id_pacienta = "SELECT id FROM iturezervacn9298.pacient 
            WHERE jmeno = '".$idpacient."';";
            $vysledekPrikazu = mysqli_query($conn, $prikaz_k_vypisu_id_pacienta);
            $pocetZaznamuPacienta = mysqli_num_rows($vysledekPrikazu);
            $idpacient = 1;
            if($pocetZaznamuPacienta > 0){
                $row_all = mysqli_fetch_assoc($vysledekPrikazu);
                $idpacient = $row_all['id'];
            }
            
            // Zjistím ID zaměstnance
            $prikaz_k_vypisu_id_zamestance = "SELECT id FROM iturezervacn9298.pacient 
            WHERE jmeno = '".$idzamestnanec."';";
            $vysledekPrikazu = mysqli_query($conn, $prikaz_k_vypisu_id_zamestance);
            $pocetZaznamuZamest = mysqli_num_rows($vysledekPrikazu);
            $idzamestnanec = 1;
            if($pocetZaznamuZamest > 0){
                $row_all = mysqli_fetch_assoc($vysledekPrikazu);
                $idzamestnanec = $row_all['id'];
            }
             
            // Zjistím ID služba
            $prikaz_k_vypisu_id_sluzba = "SELECT id FROM iturezervacn9298.sluzba 
            WHERE jmeno = '".$idsluzba."';";
            $vysledekPrikazu = mysqli_query($conn, $prikaz_k_vypisu_id_sluzba);
            $pocetZaznamuSluzeb = mysqli_num_rows($vysledekPrikazu);
            $idsluzba = 1;
            if($pocetZaznamuSluzeb > 0){
                $row_all = mysqli_fetch_assoc($vysledekPrikazu);
                $idsluzba = $row_all['id'];
            }
             
            
            $prikaz_vlozeni_rezervace = "INSERT INTO iturezervacn9298.rezervace (`mistnost`, `datum`, `cas`, 
            `delka`, `poznamka`, `idsluzba`, `idpacient`, `idzamestnanec`) 
            VALUES ('".$mistnost."','".$datum."', '".$cas."', 
            ".$delka.", '".$poznamka."', ".$idsluzba.", ".$idpacient.", ".$idzamestnanec.");";
            
            mysqli_query($conn, $prikaz_vlozeni_rezervace);*/
            echo "Ok";
        }
        
        // Dostupní pracovnici pro službu
        $dostupnipracovnici = $_GET["dostupnipracovnici"];
        if(!empty($dostupnipracovnici)){
            $sluzba = $_GET["sluzba"];
            if(strcmp($sluzba, "Thajská masáž") == 0){
                      echo '["Jan Novák", "Tomáš Fojt", "Dara Rolins"]';
            }else if(strcmp($sluzba, "Meridiánová masáž") == 0){
                echo '["Tomáš Fojt", "Dara Rolins", "Dennis Ritchie"]';
            }else if(strcmp($sluzba, "Magnetoterapie") == 0){
                 echo '["Dara Rolins", "Dennis Ritchie", "Flóra Hrbáčková"]';
            }else if(strcmp($sluzba, "Biolampa") == 0){
                 echo '["Dennis Ritchie", "Flóra Hrbáčková", "Pavel Novotný"]';
            }else if(strcmp($sluzba, "Elektroléčba") == 0){
                 echo '["Flóra Hrbáčková", "Pavel Novotný", "Jan Modec"]';
            }else if((strcmp($sluzba, "Vstupní vyšetření") == 0) || (strcmp($sluzba, "Vstupni vysetreni") == 0)){
                 echo '["Pavel Novotný", "Jan Modec", "Jan Novák"]';
            }else if(strcmp($sluzba, "Laser") == 0){
                 echo '["Jan Modec", "Jan Novák", "Tomáš Fojt"]';
            }else if(strcmp($sluzba, "Magnet") == 0){
                 echo '["Jan Novák", "Tomáš Fojt", "Dara Rolins"]';
            }else{
                 echo '["Jan Novák", "Tomáš Fojt", "Dara Rolins"]';
            }
            
        }
        
        // Seznam volných míst
        $seznamvolnychmist = $_GET["seznamvolnychmist"];
        if(!empty($seznamvolnychmist)){
            $sluzba = $_GET["sluzba"];
            $datumprvnihodnetydne = $_GET["datum-prvniho-dne-tydne"];
            
            if(strcmp($sluzba, "Thajská masáž") == 0){
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-10", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-11", "volno": ["7:00", "11:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-12", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-13", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-14", "volno": []}]';
            }else if(strcmp($sluzba, "Meridiánová masáž") == 0){
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-10", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-11", "volno": ["8:00", "11:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-12", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-13", "volno": ["10:00", "13:00", "14:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-14", "volno": []}]';
            }else if(strcmp($sluzba, "Magnetoterapie") == 0){
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-10", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-11, "volno": ["10:00", "11:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-12", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-13", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-14", "volno": []}]';
            }else if(strcmp($sluzba, "Biolampa") == 0){
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-10", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-10", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-11", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-12", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-13", "volno": []}]';
            }else if(strcmp($sluzba, "Elektroléčba") == 0){ 
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-10", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-10", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-11", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-12", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-13", "volno": []}]';
            }else if(strcmp($sluzba, "Vstupní vyšetření") == 0){
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-09", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-10", "volno": ["13:00", "14:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-11", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-12", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-13", "volno": []}]';
            }else if(strcmp($sluzba, "Laser") == 0){
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-09", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-10", "volno": ["10:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-11", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-12", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-13", "volno": []}]';
            }else if(strcmp($sluzba, "Magnet") == 0){
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-09", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-10", "volno": ["8:00", "9:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-11", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-12", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-13", "volno": []}]';
            }else{
                      echo '[{"zkratkaDne": "Po", "den": "2023-01-09", "volno": ["9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Út", "den": "2023-01-10", "volno": ["8:00", "9:00"]}, ';
                      echo '{"zkratkaDne": "St", "den": "2023-01-11", "volno": ["8:00", "9:00", "12:00"]}, ';
                      echo '{"zkratkaDne": "Čt", "den": "2023-01-12", "volno": ["10:00", "13:00"]}, ';
                      echo '{"zkratkaDne": "Pá", "den": "2023-01-13", "volno": []}]';
            }
        }
    }
    
      // Jestli je druhRozhrani adminovi, tak hledej prikazy pro jeho rozhrani
    if (strcmp($druhRozhrani, "admin") == 0){
        // Vypíše statistiky
        $statistiky = $_GET["statistiky"];
        if(!empty($statistiky)){
            
            $varianta = $_GET["varianta"];
            if(strcmp($varianta, "sluzby") == 0){
                $datumPrvnihoDneTydne = $_GET["datum-prvniho-dne-tydne"];
                echo '[{"NazevPolozky": "Thajská masáž", 	"Procenta": 76, 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Meridiánová masáž",	"Procenta": 56, 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Magnetoterapie", 	"Procenta": 36, 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Biolampa", 			"Procenta": 46, 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Elektroléčba", 		"Procenta": 16, 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Vstupní vyšetření", "Procenta": 26, 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}]';
            }else if(strcmp($varianta, "zamestnanci") == 0){
                $datumPrvnihoDneTydne = $_GET["datum-prvniho-dne-tydne"];
                echo '[{"NazevPolozky": "Jan Modec", 	    "Procenta": 41, 	"datumPrvnihoDneTydne: "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Pavel Novotný",	    "Procenta": 54, 	"datumPrvnihoDneTydne: "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Flóra Hrbáčková",   "Procenta": 19, 	"datumPrvnihoDneTydne: "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Dennis Ritchie", 	"Procenta": 75, 	"datumPrvnihoDneTydne: "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Dana Rolins", 	    "Procenta": 34, 	"datumPrvnihoDneTydne: "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}, ';
                echo '{"NazevPolozky": "Tomáš Fojt", 		"Procenta": 34, 	"datumPrvnihoDneTydne: "'.SQLformatCasuDoJSONformatu($datumPrvnihoDneTydne).'"}]';
            }else if(strcmp($varianta, "dny") == 0){
                $datumPrvnihoDneTydne = $_GET["datum-prvniho-dne-tydne"];
                $datum = $_GET["datum"];
                if(empty($datum)){
                    $datum = $datumPrvnihoDneTydne;
                }
                echo '[{"NazevPolozky": "Pondělí", 	    "Procenta": '.pseudonahodneCislo(46, $datum).', 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Úterý ",	    "Procenta": '.pseudonahodneCislo(30, $datum).', 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Středa", 	    "Procenta": '.pseudonahodneCislo(75, $datum).', 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Čtvrtek", 	    "Procenta": '.pseudonahodneCislo(17, $datum).', 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Pátek", 	    "Procenta": '.pseudonahodneCislo(80, $datum).', 	"datumPrvnihoDneTydne": "'.SQLformatCasuDoJSONformatu($datum).'"}]';
            }else{ // Pokud dny nebo empty
                $datum = $_GET["datum"];
                echo '[{"NazevPolozky": "Pondělí", 	    "Procenta": '.pseudonahodneCislo(46, $datum).', 	"datum": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Úterý ",	    "Procenta": '.pseudonahodneCislo(30, $datum).', 	"datum": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Středa", 	    "Procenta": '.pseudonahodneCislo(75, $datum).', 	"datum": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Čtvrtek", 	    "Procenta": '.pseudonahodneCislo(17, $datum).', 	"datum": "'.SQLformatCasuDoJSONformatu($datum).'"}, ';
                echo '{"NazevPolozky": "Pátek", 	    "Procenta": '.pseudonahodneCislo(80, $datum).', 	"datum": "'.SQLformatCasuDoJSONformatu($datum).'"}]';
            }
        }
        
        $rozvrh = $_GET["rozvrh"];
        // Pokud chce, tak chci vědět čí mám dát rozvrh a v kterém čase
        if (!empty($rozvrh)){
            $jmeno = $_GET["jmeno"]; 
            $cas = $_GET["datum-prvniho-dne-tydne"];
            
            // Příkaz pro SQL 
            vypsatMapuAdminivo($jmeno, $cas, $conn);
        }
    }
?>