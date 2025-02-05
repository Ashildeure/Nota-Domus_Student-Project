/*
Ce fichier permet d'insérer les oeuvres les plus reconnus de certains 
illustres 
*/

CREATE TEMPORARY TABLE Oeuvres (
    idWork          INT PRIMARY KEY,
    outstandingName VARCHAR(255),
    workName        VARCHAR(255)
);

CREATE TABLE Work AS
    (SELECT idWork, idOutstanding, workName
     FROM Work NATURAL RIGHT JOIN Outstanding);