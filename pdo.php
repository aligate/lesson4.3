<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

try{
	$pdo = new PDO('mysql:host=localhost;dbname=global', 'root', '');
}
catch (PDOException $e){
	
	echo "Невозможно подключиться к Базе данных";
}



?>