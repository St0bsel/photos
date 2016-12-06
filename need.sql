CREATE DATABASE IF NOT EXISTS photos;
USE photos;
CREATE TABLE IF NOT EXISTS Users (
    UserId INT NOT NULL AUTO_INCREMENT,
    UserName VARCHAR(50) UNIQUE,
    UserPw VARCHAR(255),
    UserMail VARCHAR(100) UNIQUE,
    PRIMARY KEY (UserId)
  );

CREATE TABLE IF NOT EXISTS Albums (
  AlbumId INT NOT NULL AUTO_INCREMENT,
  AlbumName VARCHAR(50) UNIQUE,
  AlbumDescription VARCHAR(255),
  AlbumCreatedBy INT,
  FOREIGN KEY (AlbumCreatedBy) REFERENCES Users(UserId),
  PRIMARY KEY (AlbumId)
);

CREATE TABLE IF NOT EXISTS Locations (
  LocationId INT NOT NULL AUTO_INCREMENT,
  LocationName VARCHAR(50) UNIQUE,
  LocationDescription VARCHAR(255),
  LocationN DECIMAL(8,6),
  LocationE DECIMAL(8,6),
  PRIMARY KEY(LocationId)
);

CREATE TABLE IF NOT EXISTS Pictures (
  PictureId INT NOT NULL AUTO_INCREMENT,
  PictureName VARCHAR(255),
  PicturePath VARCHAR(255),
  PictureUploadedBy INT,
  PictureLocation INT,
  PictureDate DATE,
  FOREIGN KEY (PictureUploadedBy) REFERENCES Users(UserId),
  FOREIGN KEY (PictureLocation) REFERENCES Locations(LocationId),
  PRIMARY KEY (PictureId)
);

CREATE TABLE IF NOT EXISTS PicturesInAlbum (
  PicAlbId INT AUTO_INCREMENT NOT NULL,
  AlbumId INT,
  PictureId INT,
  FOREIGN KEY (AlbumId) REFERENCES Albums(AlbumId),
  FOREIGN KEY (PictureId) REFERENCES Pictures(PictureId),
  PRIMARY KEY (PicAlbId)
);

CREATE TABLE IF NOT EXISTS Tags (
  TagId INT AUTO_INCREMENT NOT NULL,
  TagName VARCHAR(50) UNIQUE,
  PRIMARY KEY (TagId)
);

CREATE TABLE IF NOT EXISTS PictureTag (
  PicTagId INT AUTO_INCREMENT NOT NULL,
  PictureId INT,
  TagId INT,
  FOREIGN KEY (PictureId) REFERENCES Pictures(PictureId),
  FOREIGN KEY (TagId) REFERENCES Tags(TagId),
  PRIMARY KEY (PicTagId)
);

CREATE UNIQUE INDEX UserNameIndex ON Users(Username);
CREATE UNIQUE INDEX UserIdIndex ON Users(UserId);
CREATE UNIQUE INDEX UserMailIndex ON Users(UserMail);

CREATE UNIQUE INDEX AlbumIdIndex ON Albums(AlbumId);
CREATE UNIQUE INDEX AlbumNameIndex ON Albums(AlbumName);

CREATE UNIQUE INDEX LocationIdIndex ON Locations(LocationId);
CREATE UNIQUE INDEX LocationNameIndex ON Locations(LocationName);

CREATE INDEX PhotosIndex ON Pictures(PictureId, PictureName, PicturePath, PictureDate);

CREATE INDEX PicAlbINdex ON PicturesInAlbum(PicAlbId, PictureId, AlbumId);

CREATE INDEX TagIndex ON Tags(TagId, TagName);

CREATE INDEX PicTagIndex ON PictureTag(PicTagId, PictureId, TagId);
