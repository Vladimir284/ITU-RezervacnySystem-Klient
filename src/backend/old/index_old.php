<?php
    // Načtení externiho souboru pro nacteni databaze
    include_once 'dbh.inc.php'; // Napojeni na databazi
    
    function pridatRezervaci() {
        $den = $_GET["den"]; 
        $mesic = $_GET["mesic"];
        $rok = $_GET["rok"];
        $od = $_GET["od"];
        $do = $_GET["do"];
        $prijmeni = $_GET["prijmeni"];
        $email = $_GET["email"];
        $telefon = $_GET["telefon"];
        $komentar = $_GET["komentar"];
        $kategorie = $_GET["kategorie"];
        $specifikace = $_GET["specifikace"];
        $zamestnanec = $_GET["zamestnanec"];
        
        $insert_sql = "INSERT INTO 
                                iturezervacn9298.rezervace (den, mesic, rok, od, do, jmeno, prijmeni, email, telefon, komentar, kategorie, specifikace, zamestnanec)
                                VALUES ('".$den."', '".$mesic."', '".$rok."', '".$od."', '".$do."', '".$jmeno."', '".$prijmeni."', 
                                '".$email."', '".$telefon."', '".$komentar."', '".$kategorie."', '".$specifikace."', '".$zamestnanec."');";
        mysqli_query($conn, $insert_sql); 
    }
    
    function vypsatNejblizsiRezervace($conn) {
        // Zavolá a vypíše rezervace sezarene rokem
        $sql_query_all = "SELECT jmeno, prijmeni, den, mesic, rok, od, do FROM iturezervacn9298.rezervace ORDER BY rok DESC LIMIT 3;";
        $checker_all = mysqli_query($conn, $sql_query_all);
        $resultChecker_all = mysqli_num_rows($checker_all);
        if($resultChecker_all > 0){
            print("[");
            while($row_all = mysqli_fetch_assoc($checker_all)){
                echo '{"jmeno": "'.$row_all['jmeno'].'", "prijmeni": "'.$row_all['prijmeni'].'", "den": "'.$row_all['den'].'", "mesic": "'.$row_all['mesic'].'", "rok": "'.$row_all['rok'].'", "od": "'.$row_all['od'].'" "do": "'.$row_all['do'].'"}';
                if($resultChecker_all - 1 > 0){
                    echo ', ';
                    $resultChecker_all = $resultChecker_all - 1;
                }
                
            }
            print("]");
         }
    }
    
    function vypsatRezervaceDanehoDne($den, $mesic, $rok, $conn) {
        // Zavolá a vypíše rezervace sezarene rokem
        $sql_query_all = "SELECT jmeno, prijmeni, den, mesic, rok, od, do FROM iturezervacn9298.rezervace where den=".$den." AND mesic=".$mesic." AND rok=".$rok." ;";
        $checker_all = mysqli_query($conn, $sql_query_all);
        $resultChecker_all = mysqli_num_rows($checker_all);
        if($resultChecker_all > 0){
            print("[");
            while($row_all = mysqli_fetch_assoc($checker_all)){
                echo '{"jmeno": "'.$row_all['jmeno'].'", "prijmeni": "'.$row_all['prijmeni'].'", "den": "'.$row_all['den'].'", "mesic": "'.$row_all['mesic'].'", "rok": "'.$row_all['rok'].'", "od": "'.$row_all['od'].'" "do": "'.$row_all['do'].'"}';
                if($resultChecker_all - 1 > 0){
                    echo ', ';
                    $resultChecker_all = $resultChecker_all - 1;
                }
                
            }
            print("]");
         }
    }
    
    $nejblizsi = $_GET["nejblizsi"]; 
    $rezervaceDanehoDne = $_GET["rezervaceDanehoDne"];
    $jmeno = $_GET["jmeno"];
    // Nabrání všech atributů pro uložení do databáze pro rezervaci
    
    if (!empty($nejblizsi)){
        vypsatNejblizsiRezervace($conn);
    }else if (!empty($rezervaceDanehoDne)){
        $den = $_GET["den"]; 
        $mesic = $_GET["mesic"];
        $rok = $_GET["rok"];
        vypsatRezervaceDanehoDne($den, $mesic, $rok, $conn);
    }else if (!empty($jmeno)){
        // ?jmeno=Vit&den=19&mesic=5&rok=1999&od=13:00&do=14:00&prijmeni=Hrbacek&email=verejnopravni@email.cz&telefon=773088210&komentar=OndrejJeSuper&specifikace=Face&kategorie=Masaže
        $seznamParametru = "jmeno";
        $seznamHodnot = $jmeno;
        
        $den = $_GET["den"]; 
        if(!empty($den)){
            $seznamParametru = $seznamParametru.", den";
            $seznamHodnot = $seznamHodnot."', '".$den;
        }
        $mesic = $_GET["mesic"];
        if(!empty($mesic)){
            $seznamParametru = $seznamParametru.", mesic";
            $seznamHodnot = $seznamHodnot."', '".$mesic;
        }
        $rok = $_GET["rok"];
        if(!empty($rok)){
            $seznamParametru = $seznamParametru.", rok";
            $seznamHodnot = $seznamHodnot."', '".$rok;
        }
        $od = $_GET["od"];
        if(!empty($od)){
            $seznamParametru = $seznamParametru.", od";
            $seznamHodnot = $seznamHodnot."', '".$od;
        }
        $do = $_GET["do"];
        if(!empty($do)){
            $seznamParametru = $seznamParametru.", do";
            $seznamHodnot = $seznamHodnot."', '".$do;
        }
        $prijmeni = $_GET["prijmeni"];
        if(!empty($prijmeni)){
            $seznamParametru = $seznamParametru.", prijmeni";
            $seznamHodnot = $seznamHodnot."', '".$prijmeni;
        }
        $email = $_GET["email"];
        if(!empty($email)){
            $seznamParametru = $seznamParametru.", email";
            $seznamHodnot = $seznamHodnot."', '".$email;
        }
        $telefon = $_GET["telefon"];
        if(!empty($telefon)){
            $seznamParametru = $seznamParametru.", telefon";
            $seznamHodnot = $seznamHodnot."', '".$telefon;
        }
        $komentar = $_GET["komentar"];
        if(!empty($komentar)){
            $seznamParametru = $seznamParametru.", komentar";
            $seznamHodnot = $seznamHodnot."', '".$komentar;
        }
        $kategorie = $_GET["kategorie"];
        if(!empty($kategorie)){
            $seznamParametru = $seznamParametru.", kategorie";
            $seznamHodnot = $seznamHodnot."', '".$kategorie;
        }
        $specifikace = $_GET["specifikace"];
        if(!empty($specifikace)){
            $seznamParametru = $seznamParametru.", specifikace";
            $seznamHodnot = $seznamHodnot."', '".$specifikace;
        }
        $zamestnanec = $_GET["zamestnanec"];
        if(!empty($zamestnanec)){
            $seznamParametru = $seznamParametru.", zamestnanec";
            $seznamHodnot = $seznamHodnot."', '".$zamestnanec;
        }
        
        
        /*$insert_sql = "INSERT INTO 
                                iturezervacn9298.rezervace (den, mesic, rok, od, do, jmeno, prijmeni, email, telefon, komentar, kategorie, specifikace, zamestnanec)
                                VALUES ('".$den."', '".$mesic."', '".$rok."', '".$od."', '".$do."', '".$jmeno."', '".$prijmeni."', 
                                '".$email."', '".$telefon."', '".$komentar."', '".$kategorie."', '".$specifikace."', '".$zamestnanec."');";
        */
        $insert_sql = "INSERT INTO 
                                iturezervacn9298.rezervace (".$seznamParametru.")
                                VALUES ('".$seznamHodnot."');";
        

        mysqli_query($conn, $insert_sql); 
        print("SUCCESS<br><b>For debugging purpuses: (SQL insert this commands) </b><br>".$insert_sql);
    }else{
        print("Nothing to do. Try <a href='http://iturezervacnisystem.wz.cz/index.php?nejblizsi=true'>?nejblizsi=true</a> or <a href='http://iturezervacnisystem.wz.cz/index.php?rezervaceDanehoDne=true&den=19&mesic=5&rok=1999'>?rezervaceDanehoDne</a> or just add reservation <a href='http://iturezervacnisystem.wz.cz/index.php?jmeno=Pavek&den=21&mesic=4&rok=1987&od=13:00&do=14:00&prijmeni=Franek&email=daberi@email.cz&telefon=987654321&komentar=OndrejJepPoradSuper&specifikace=Face&kategorie=Masa%C5%BEe'>?jmeno=JmenoRezervujicho</a>.");
    }

?>