CREATE TABLE pacient (
	id int(12) not null PRIMARY KEY AUTO_INCREMENT,
    name varchar(24),
	email varchar(24),
	phone int(11),
	date varchar(10),
	time varchar(5),
	service varchar(24)
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

INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`) VALUES ("Petr Pacientový","petr@pacient.cz", +421765654,"2023-01-10","10:00","BioLampa");
INSERT INTO pacient(`name`, `email`, `phone`,`date`,`time`) VALUES ("Foo Bar","foo@bar.baz", 123894057);