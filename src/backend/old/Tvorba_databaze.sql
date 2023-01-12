-- DROP TABLE pacient;
-- DROP TABLE rezervace;
-- DROP TABLE zamestnanec;
-- DROP TABLE sluzba;

create table pacient (
	id int(12) not null PRIMARY KEY AUTO_INCREMENT,
    	jmeno varchar(24),
	email varchar(24),
	telefon int(11)
);

create table sluzba (
                        id int(12) not null PRIMARY KEY AUTO_INCREMENT,
                        jmeno varchar(24),
                        kategorie varchar(24)
);

create table zamestnanec (
                             id int(12) not null PRIMARY KEY AUTO_INCREMENT,
                             jmeno varchar(24)
);

create table rezervace (
                           id int(12) not null PRIMARY KEY AUTO_INCREMENT,
                           mistnost varchar(20),
                           datum DATE,
                           cas TIME,
                           delka int(5),
                           poznamka varchar(224),
                           idsluzba int,
                           FOREIGN KEY (idsluzba) REFERENCES sluzba(id),
                           idpacient int,
                           FOREIGN KEY (idpacient) REFERENCES pacient(id),
                           idzamestnanec int,
                           FOREIGN KEY (idzamestnanec) REFERENCES zamestnanec(id)
);


create table sluzba_zamestnanec (
                                    id int(12) not null PRIMARY KEY AUTO_INCREMENT,
                                    id_t_zamestnanec int,
                                    FOREIGN KEY (id_t_zamestnanec) REFERENCES pacient(id),
                                    id_t_sluzba int,
                                    FOREIGN KEY (id_t_sluzba) REFERENCES zamestnanec(id)
);

// Vložení pacienta a služeb
INSERT INTO `pacient`(`jmeno`, `email`, `telefon`) VALUES ("Petr Pacientový","petr@pacient.cz", 123456789);
INSERT INTO `rezervace`( `mistnost`, `datum`, `cas`, `delka`, `poznamka`, `idsluzba`, `idpacient`, `idzamestnanec`)
VALUES ("A113", '2022-1-28', '13:30', 60, "Mám od malička špatně srostlé kosti.", 1, 1, 1);
INSERT INTO `rezervace`(`mistnost`, `datum`, `cas`, `delka`, `poznamka`, `idsluzba`, `idpacient`, `idzamestnanec`)
VALUES ('A113','2022-12-29', '14:30', 60, 'Po úrazu pateře mám pravidelně bolesti od vrcholu zad k levé patě', 2, 1, 1);

// Vložení služeb
INSERT INTO `sluzba` (`jmeno`, `kategorie`) VALUES ('Magnetorerapia', 'Magnetorerapia');
INSERT INTO `sluzba` (`jmeno`, `kategorie`) VALUES ('Biolampa', 'Biolampa');
INSERT INTO `sluzba` (`jmeno`, `kategorie`) VALUES ('Elektroléčba', 'Elektroléčba');
INSERT INTO `sluzba` (`jmeno`, `kategorie`) VALUES ('Vstupní vyšetření', 'Vstupní vyšetření');



SELECT * FROM iturezervacn9298.rezervace, iturezervacn9298.sluzba WHERE iturezervacn9298.rezervace.idsluzba=iturezervacn9298.sluzba.id;

-- Vypise terminy pacientů od fyzioteraputa Jana Nováka pro jeho týdenní přehled/mapu.
SELECT datum, cas, pacient.jmeno, mistnost FROM iturezervacn9298.rezervace, iturezervacn9298.sluzba, iturezervacn9298.zamestnanec, iturezervacn9298.pacient
WHERE 	iturezervacn9298.rezervace.idsluzba=iturezervacn9298.sluzba.id
  AND 	iturezervacn9298.rezervace.idzamestnanec=iturezervacn9298.zamestnanec.id
  AND 	iturezervacn9298.rezervace.idpacient=iturezervacn9298.pacient.id
  AND 	iturezervacn9298.zamestnanec.jmeno = "Jan Novák";


