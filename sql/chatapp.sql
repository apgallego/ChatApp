DROP DATABASE chatapp;
CREATE DATABASE IF NOT EXISTS chatapp CHARSET utf8mb4 COLLATE utf8mb4_general_ci;

USE chatapp;

CREATE TABLE IF NOT EXISTS users (
  id int(10) NOT NULL AUTO_INCREMENT,
  usName varchar(30) NOT NULL,
  usSurname varchar(30) NOT NULL,
  username varchar(30) NOT NULL UNIQUE,
  email varchar(30) NOT NULL UNIQUE,
  age int(2) NOT NULL,
  telephone varchar(16) NOT NULL,
  passwd varchar(100) NOT NULL,
  pfp varchar(100) DEFAULT './assets/files/img/default/pfp_default.jpg',
  isActive BOOLEAN NOT NULL DEFAULT 0,
  usRole enum('admin', 'client') NOT NULL DEFAULT 'client',
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS chats (
	id int(10) NOT NULL AUTO_INCREMENT,
  alias varchar(30) NOT NULL,
  pfp varchar(100) DEFAULT './assets/files/img/default/pfp_group_default.jpg',
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS participate_users_chats(
    userID int(10) NOT NULL, 
    chatID int(10) NOT NULL,
    PRIMARY KEY (userID, chatID),
    FOREIGN KEY (userID) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (chatID) REFERENCES chats(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
  id int(10) NOT NULL AUTO_INCREMENT,
  senderID int(10) NOT NULL,
  chatID int(10) NOT NULL,
  content varchar(10000) NOT NULL,
  msgTime datetime NOT NULL,
  msgFile varchar(1000),
  isRead boolean NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (senderID) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (chatID) REFERENCES chats(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- INSERT INTO `users` (`usName`, `usSurname`, `username`, `email`, `age`, `telephone`, `passwd`, `isActive`, `usRole`) VALUES ('root', 'root', 'root', 'root', 99, '999999999', 'root', '1', 'admin');