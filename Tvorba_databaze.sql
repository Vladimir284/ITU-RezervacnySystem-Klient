CREATE TABLE pacient (
	id int(12) not null PRIMARY KEY AUTO_INCREMENT,
    name varchar(24),
	email varchar(24),
	phone int(11),
	date varchar(10),
	time varchar(5),
	service varchar(24),
	employee varchar(24)
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

INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Andrej Szelte","andrej@pacientr.hu", 2123234345,"2023-01-10","8:00","Thajská Masáž","Jan Novák");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Petr Novotny","petr@pacient.cz", 123456789,"2023-01-10","10:00","BioLampa","Tomáš Fojt");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Bartolomej Blahac","bartolomeh@pacient.sk",234345456,"2023-01-10","13:00","Vstupné vyšetření","Dara Rolins");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Andrej Szelte","andrej@pacientr.hu", 2123234345,"2023-01-10","14:00","Magnetoterapie","Denis Ritchie");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Bartolomej Blahac","bartolomeh@pacient.sk",234345456,"2023-01-10","15:00","Biolampa","Tomáš Fojt");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Anastazia Jurgova","anastazia@pacient.ru",987765543,"2023-01-10","16:00","Biolampa","Jan Novák");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Dalibor Marko","dalibor@pacient.sk",918354728,"2023-01-11","8:00","Magnetoterapie","Pavel Novotný");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Bartolomej Blahac","bartolomeh@pacient.sk",234345456,"2023-01-11","9:00","Thajská masáž","Flóra Hrbáčková");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Bartolomej Blahac","bartolomeh@pacient.sk",234345456,"2023-01-11","12:00","Meridianová masáž","Denis Ritchie");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Bartolomej Blahac","bartolomeh@pacient.sk",234345456,"2023-01-12","15:00","Vstupné vyšetření", "Denis Ritchie");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`,`service`,`employee`) VALUES ("Bartolomej Blahac","bartolomeh@pacient.sk",234345456,"2023-01-14","10:00","MagnetpTerapie", "Dara Rolins");
