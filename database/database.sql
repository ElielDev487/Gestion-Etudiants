create database gestion_etudiants;
use gestion_etudiants;

create table filieres(
    id int primary key auto_increment,
    nom varchar(150)
);

create table etudiants(
    id int primary key auto_increment,
    nom varchar(100);
    prenom varchar(150);
    filiere_id int,
    foreign key (filiere_id) references filieres(id);
);