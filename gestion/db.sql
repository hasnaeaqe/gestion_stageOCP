create database if not exists `ocp_gestion`;
USE ocp_gestion;
CREATE TABLE Technicien (
    id_tech INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    fonction VARCHAR(100),
    userName VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE Equipement (
    id_equip INT AUTO_INCREMENT PRIMARY KEY,
    num_serie VARCHAR(100) UNIQUE,
    type VARCHAR(100)
);

CREATE TABLE Composant (
    id_composant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    description VARCHAR(255),
    prix FLOAT
);

CREATE TABLE Ordre (
    id_ordre INT AUTO_INCREMENT PRIMARY KEY,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    priorite VARCHAR(50),
    type_intervention VARCHAR(100),
    id_tech INT,
    id_equip INT,
    FOREIGN KEY (id_tech) REFERENCES Technicien(id_tech),
    FOREIGN KEY (id_equip) REFERENCES Equipement(id_equip)
);

CREATE TABLE Ordre_Composant (
    id_ordre INT,
    id_composant INT,
    PRIMARY KEY (id_ordre, id_composant),
    FOREIGN KEY (id_ordre) REFERENCES Ordre(id_ordre),
    FOREIGN KEY (id_composant) REFERENCES Composant(id_composant)
);

CREATE TABLE TableauDeBord (
    id_tableau INT AUTO_INCREMENT PRIMARY KEY,
    dateAffichage DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    id_ordre INT,
    FOREIGN KEY (id_ordre) REFERENCES Ordre(id_ordre)
);




-- Insertion Techniciens
INSERT INTO Technicien (nom, prenom, fonction, userName, password) VALUES
('Dupont', 'Jean', 'Technicien Maintenance', 'jdupont', 'pass123'),
('Martin', 'Claire', 'Technicien Support', 'cmartin', 'pass123'),
('Nguyen', 'Thi', 'Technicien Senior', 'tnguyen', 'pass123');

-- Insertion Equipements
INSERT INTO Equipement (num_serie, type) VALUES
('SN1001', 'Pompe'),
('SN1002', 'Compresseur'),
('SN1003', 'Filtre');

-- Insertion Composants
INSERT INTO Composant (nom, description, prix) VALUES
('Filtre à air', 'Filtre pour compresseur', 25.5),
('Joint', 'Joint pour pompe', 5.75),
('Courroie', 'Courroie de transmission', 15.0);

-- Insertion Ordres
INSERT INTO Ordre (date_creation, priorite, type_intervention, id_tech, id_equip) VALUES
('2025-08-27 09:00:00', 'Haute', 'Maintenance préventive', 1, 1),
('2025-08-27 10:00:00', 'Moyenne', 'Réparation', 2, 2),
('2025-08-27 11:00:00', 'Basse', 'Inspection', 3, 3);

-- Insertion Ordre_Composant
INSERT INTO Ordre_Composant (id_ordre, id_composant) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3),
(3, 1),
(3, 3);

-- Insertion TableauDeBord
INSERT INTO TableauDeBord (dateAffichage, status, id_ordre) VALUES
('2025-08-27 09:30:00', 'En cours', 1),
('2025-08-27 10:30:00', 'Terminé', 2),
('2025-08-27 11:30:00', 'Planifié', 3);



CREATE TABLE Equipement_Composant (
    id_equip INT,
    id_composant INT,
    PRIMARY KEY (id_equip, id_composant),
    FOREIGN KEY (id_equip) REFERENCES Equipement(id_equip),
    FOREIGN KEY (id_composant) REFERENCES Composant(id_composant)
);

-- Exemple d’insertion
INSERT INTO Equipement_Composant (id_equip, id_composant) VALUES
(1, 1), (1, 2),       -- Equipement 1 peut utiliser Composant 1 et 2
(2, 2), (2, 3),       -- Equipement 2 peut utiliser Composant 2 et 3
(3, 1), (3, 3);       -- Equipement 3 peut utiliser Composant 1 et 3
