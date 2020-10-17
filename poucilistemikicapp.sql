# linija koja počne s znakon ljestve (hash) je zanemarena 
# komentar
# otvori cmd
# c:\xampp\mysql\bin\mysql -uedunova -pedunova --default_character_set=utf8mb4 < d:\pp21\poucilistemikicapp.sql

# backup cmd naredba
# c:\xampp\mysql\bin\mysqldump -uedunova -pedunova poucilistemikicapp > d:\pp21\backup_baza.sql

# kreirati bat datoteku s gornjom naredbom te 
# konfigurirati Task Scheduler na windowsimaž
# na linuxu to je cron

# logiranje mysql
# u my.ini (XAMPP config gumb) u dijelu datoteke gdje je [mysqld]
# dodati
# general_log = on
# general_log_file=d:\mysql.log


# ove tri naredbe se ne izvode na produkciji
drop database if exists poucilistemikicapp;
create database poucilistemikicapp;
use poucilistemikicapp;

# ovo samo izvesti u produkciji
#alter database cesar_pp21 default character set utf8mb4;

create table operater(
    sifra   int not null primary key auto_increment,
    email   varchar(50) not null,
    lozinka char(60) not null,
    ime     varchar(50) not null,
    prezime varchar(50) not null,
    uloga   varchar(10) not null
);

# admin@edunova.hr a 
# oper@edunova.hr o
insert into operater(email,lozinka,ime,prezime,uloga) values 
('oper@edunova.hr','$2y$10$m/4kVMvKhI2Wp3YLsW.8e.VMHQuC7Fdy8KYMUMykrjM2T2XMjZBCq',
'Operater', 'Edunova', 'oper'),
('admin@edunova.hr','$2y$10$ZpzbFNHRpWR6LjgrlucAmeGoMXI1IrHXvS.Eud71pyelM444HLrw.',
'Admin', 'Edunova', 'admin');


create table program(
sifra       int not null primary key auto_increment,
naziv       varchar(50) not null,
opis        text,
cijena      decimal(18,2),
verificiran boolean
) engine=innodb;

create table klasa(
sifra int not null primary key auto_increment,
naziv varchar(50) not null,
program int not null,
profesor int,
datumpocetka datetime
) engine=innodb;

create table profesor(
    sifra int not null primary key auto_increment,
    osoba int not null,
    iban varchar(50)
) engine=innodb;

create table ucenik(
    sifra int not null primary key auto_increment,
    osoba int not null,
    brojugovora varchar(50)
) engine=innodb;

create table osoba(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    oib char(11),
    email varchar(100)
) engine=innodb;

create table sudionik(
    klasa int not null,
    ucenik int not null
) engine=innodb;


#vanjski (strani) ključevi
alter table klasa add foreign key (program) references program(sifra);
alter table klasa add foreign key (profesor) references profesor(sifra);

alter table profesor add foreign key (osoba) references osoba(sifra);

alter table ucenik add foreign key (osoba) references osoba(sifra);

alter table sudionik add foreign key (klasa) references klasa(sifra);
alter table sudionik add foreign key (ucenik) references ucenik(sifra);


# select * from program;
# describe program;

# najlošiji način
# 1 -> ovo mi je šifra
insert into program values
(null,'Strani jezik',null,null,null);

# malo bolji
# 2
insert into program (naziv,verificiran,cijena) values 
('Računovodstvo za mala poduzeća',true,null);

# primjer dobre prakse
# 3-5
insert into program (naziv,cijena) values
('Operater na PC računalu',4700.99),
('Administrator baze podataka',5900),
('Modeliranje i izrada 3D modela',6100),
('CAD dizajner',7200),
('Knjigovodstvo u OPG-ima',2500);


# 1 - 13
insert into osoba (prezime,ime,email) values
('Marijan', 'Mikić','marijan.mikic@gmail.com'),
('Carmelita','Jetter','carmelita@gmail.com'),
('Alison','Felix','felix@gmail.com'),
('Matea','Raković','matea@gmail.com'),
('Slaven','Marić','kiki@sdfgroup.com'),
('Mislav','Krišto','mislav@gmail.com'),
('Aida','Zmaić','viki2@gmail.com'),
('Peter','Hogeband','hogy@hotmail.com'),
('Nao','Kodaira','kodaira@gmail.com'),
('Christine','Nessbit','chtista@gmail.com'),
('Foluke','Akirandevo','foluke@icloud.com'),
('Christa','Harmotto','herika@gmail.com'),
('Hekata','Windsor','hekata@gmail.com');


# select * from osoba;
# zadatak: Shaquille O'Neal (shaki@edunova.hr)
#14
insert into osoba (ime, prezime, email) values 
('Renata',"Davilla",'rssuzi@yahoo.com');

#insert into osoba (ime, prezime, email)
#values ('Shaquille','O\'Neal','shaki@edunova.hr');

#insert into osoba (ime, prezime, email)
#values ('Shaquille','O''Neal','shaki@edunova.hr');


insert into osoba (ime, prezime, email) values 
('Hazilyn',"Coccamas",'haze@kkl.ph');

# 1 - 2
insert into profesor (osoba) values
(1),(14);

# select * from profesor;

# 1 - 12
insert into ucenik (osoba) values
(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),
(13);

# select * from ucenik;

#describe klasa;
# 1 - 2
insert into klasa (naziv,program,profesor, datumpocetka) values 
('KL0001',1,2,'2020-04-14 17:00:00'),
('KL0003',1,2,'2020-06-21 13:30:00'),
('KL0002',2,1,'2020-05-19 19:00:00');

# select * from klasa;

#describe sudionik;

insert into sudionik (klasa,ucenik) values
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),
(1,7),(1,8),(1,9),(1,10),(1,11),(1,12);

insert into sudionik (klasa,ucenik) values
(2,1),(2,2),(2,3),(2,4),(2,5),(2,6);


