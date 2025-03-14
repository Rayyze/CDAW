<?php

User::addSqlQuery('USER_LIST',
	'SELECT * FROM  users ORDER BY id');

User::addSqlQuery('USER_GET_WITH_ID',
	"SELECT * FROM users WHERE id = :id");

User::addSqlQuery('USER_CREATE',
	'INSERT INTO users (name, email, pwd) VALUES (:name, :email, :pwd)');

User::addSqlQuery('USER_UPDATE',
	"UPDATE users SET name = :name, email = :email, pwd = :pwd WHERE id = :id");

User::addSqlQuery('USER_CONNECT',
	'SELECT * FROM users WHERE id=:id and pwd=:pwd');