/*
Ce fichier permet de crée la base de donnée que nottre application uttilisera le modèle relationel suivant :

Region(PK idRegion,departmentName)
Departement(PK idDepartement,#idRegion,regionName)
Outstanding(PK idOutstanding,outstandingName,type,epoque)
Work(PK idWork,#idCreator,nameWork)
House(PK idHouse ,#idOutstanding,#idDepartement,#idRegion,houseName,presentation,city,adresse,postalcode,latitude,longitude,websiteLink,instaLink,facebooklink,twitterLink,foundingDate,labelDate)
Img(PK(idImg, #idHouse),url,name)
User(PK idUser,#manage,password,blame,email,login,isModerate)
Friend(PK(#idUser1,#idUser2))
Favorite(PK(#idUser,#idHouse))
Comment(PK idCom,#idOwner,#idHouse,content,rate,reporting,like,dislike)
ProhibitedEmail(PK mail)
ProhibitedLogin(PK login)
*/

-- Region(PK idRegion,departmentName)
CREATE TABLE Region (
    idRegion   INT PRIMARY KEY,
    regionName VARCHAR(255)
);
-- Departement(PK idDepartement,#idRegion,regionName)
CREATE TABLE Department (
    idDepartment   INT PRIMARY KEY,
    idRegion       INT REFERENCES region (idRegion),
    departmentName VARCHAR(255)
);
-- Outstanding(PK idOutstanding,outstandingName,type,epoque)
CREATE TABLE Outstanding (
    idOutstanding   INT PRIMARY KEY,
    outstandingName VARCHAR(255),
    type            VARCHAR(255),
    epoque          VARCHAR(255)
);
--House(PK idHouse,#idOutstanding,#idDepartement,#idRegion,houseName,presentation,city,adresse,postalcode,latitude,longitude,websiteLink,instaLink,facebooklink,twitterLink,foundingDate,labelDate)
--Img(PK(idImg, #idHouse),url,name)
CREATE TABLE House (
    idHouse       INT PRIMARY KEY,
    idOutstanding INT REFERENCES outstanding (idOutstanding),
    idDepartment  INT REFERENCES Department (idDepartment),
    idRegion      INT REFERENCES Department (idRegion),
    houseName     VARCHAR(255),
    presentation  VARCHAR(255),
    city          VARCHAR(255),
    adress        VARCHAR(255),
    postalcode    INT,
    latitude      VARCHAR(255),
    longitude     VARCHAR(255),
    websiteLink   VARCHAR(255),
    instaLink     VARCHAR(255),
    facebookLink  VARCHAR(255),
    twitterLink   VARCHAR(255),
    foundingDate  INT,
    labelDate     INT
);
-- Img(PK(idImg, #idHouse),url,name)
CREATE TABLE Img (
    idImg   INT PRIMARY KEY,
    idHouse INT REFERENCES House (idHouse) PRIMARY KEY,
    url     VARCHAR(255),
    imgName VARCHAR(255)
);
-- User(PK idUser,#manage,password,blame,email,login,isModerate
CREATE TABLE "User" (
    idUser     INT PRIMARY KEY,
    manage     INT REFERENCES House (idHouse),
    password   VARCHAR(255),
    blame      INT,
    email      VARCHAR(255) UNIQUE,
    login      VARCHAR(255) UNIQUE,
    isModerate BOOLEAN
);
-- Friend(PK(#idUser1,#idUser2))
CREATE TABLE Friend (
    idUser1 INT PRIMARY KEY REFERENCES "User" (idUser),
    idUser2 INT PRIMARY KEY REFERENCES "User" (idUser)
);
-- Favorite(PK(#idUser,#idHouse))
CREATE TABLE Favorite (
    idOwner INT REFERENCES "User" (idUser) PRIMARY KEY,
    idHouse INT REFERENCES House (idHouse) PRIMARY KEY
);
-- Notification(idNotif,#idOwner,#requestfriend,message)
CREATE TABLE Notification (
    idNotif       INT PRIMARY KEY,
    idOwner       INT REFERENCES "User" (idUser),
    requestfriend INT REFERENCES "User" (idUser),
    message       TEXT
);
-- Comment(PK idCom,#idOwner,#idHouse,content,rate,reporting,like,dislike)
CREATE TABLE Comment (
    idCom     INT PRIMARY KEY,
    idOwner   INT REFERENCES "User" (idUser) UNIQUE,
    idHouse   INT REFERENCES House (idHouse),
    content   VARCHAR(255),
    rate      INT,
    reporting INT,
    "like"    INT,
    dislike   INT
);
-- ProhibitedEmail(PK mail)
CREATE TABLE ProhibitedEmail (
    mail VARCHAR(255) PRIMARY KEY
);
-- ProhibitedLogin(PK login)
CREATE TABLE ProhibitedLogin (
    login VARCHAR(255) PRIMARY KEY
)


