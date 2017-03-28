<?php
require_once 'pdo.php';
if(!$_SESSION['users']) header('Location: login.php');
$id_in_session = $_SESSION['users']['id'];
$login_in_session = $_SESSION['users']['login'];

$array =[];
$array_assigned =[];
$array_user = []; 

if(empty( $_GET )) {
	
if( isset( $_POST['save'] ))
	
	{
		// Добавление нового задания
		$desc = trim(addslashes($_POST['description']));
		$sql = "INSERT INTO task (description, user_id, assigned_user_id) VALUES (?, ?, ?)";
		$stmt = $pdo ->prepare($sql);
		$stmt->bindParam(1, $desc, PDO::PARAM_STR);
		$stmt->bindParam(2, $id_in_session, PDO::PARAM_STR);
		$stmt->bindParam(3, $id_in_session, PDO::PARAM_STR);
		$stmt->execute();
		header('Location:'.$_SERVER['PHP_SELF']);
	
		
	} 
	elseif(isset($_POST['assign']))
	{
		// Закрепление задания за другим пользоватлем
		$user_id = $_POST['assigned_user_id'];
		$task_id = $_POST['task_id'];
		$sql = "UPDATE task SET assigned_user_id = {$user_id} WHERE id = {$task_id} AND user_id = {$id_in_session}"; 
		$pdo->query($sql);
		header('Location:'.$_SERVER['PHP_SELF']);
		
		
	}
	elseif(isset($_POST['change']))
	{
		//Редактирование задания
		$desc = trim(addslashes($_POST['description']));
		$id = $_POST['id'];
		$is_done = $_POST['is_done'];
		$sql = "UPDATE task SET description = ?, is_done = ? WHERE id = ? AND user_id = ?";
		$stmt = $pdo ->prepare($sql);
		$stmt->bindParam(1, $desc, PDO::PARAM_STR);
		$stmt->bindParam(2, $is_done, PDO::PARAM_STR);
		$stmt->bindParam(3, $id, PDO::PARAM_STR);
		$stmt->bindParam(4, $id_in_session, PDO::PARAM_STR);
		$stmt->execute();
		header('Location:'.$_SERVER['PHP_SELF']);
	}
	elseif(isset($_POST['sort']))
	{
		// Сортировка заданий по заданным полям
		$data = $_POST['sort_by'];
		$query = "SELECT * FROM task ORDER BY {$data}";
		$stmt = $pdo->query($query);
		$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// Подключаем файл с HTML шаблоном view1.php
		include_once 'view1.php';
		
		
	}
	else
	{
		// Вывод заданий по умолчанию
		// закрепленные задания
				$query1 = "SELECT ts.id AS task_id, 
							ts.description, 
							ts.date_added,
							ts.is_done,
							us.login
		FROM task AS ts JOIN user AS us ON us.id = ts.user_id WHERE ts.assigned_user_id = {$id_in_session}";
		$stmt = $pdo->query($query1);
		$array_assigned = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// Созданные задания
				$query2 = "SELECT ts.id AS task_id, 
							ts.description, 
							ts.user_id,
							ts.assigned_user_id,
							ts.date_added,
							ts.is_done,
							us.id,
							us.login
		FROM task AS ts JOIN user AS us ON us.id = ts.assigned_user_id WHERE ts.user_id = {$id_in_session}";
		$stmt = $pdo->query($query2);
		$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($array)
		{
			$sql = "SELECT * FROM user ORDER BY login";
			$stmt = $pdo->query($sql);
			$array_user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		// Подключаем файл с HTML шаблоном view1.php
		include_once 'view1.php';
		
	}
	
}	
	else {	
	$action = trim(addslashes($_GET['action']));
	$id = trim(addslashes($_GET['id']));
	
	switch($action){
		
		case "delete": 
						$sql = "DELETE FROM task WHERE id = :id AND user_id = :id_in_session";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(['id' => $id, 'id_in_session'=>$id_in_session]);
						header('Location:'.$_SERVER['PHP_SELF']);
						break;
		case "done":
						$sql = "UPDATE task SET is_done = 1 WHERE id = :id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(['id' => $id ]);
						header('Location:'.$_SERVER['PHP_SELF']);
						break;
		case "edit":	
						$sql = "SELECT id, description, is_done FROM task WHERE id = :id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(['id' => $id ]);
						$array = $stmt->fetch(PDO::FETCH_ASSOC);
						// Подключаем файл с HTML шаблоном view2.php
						include_once 'view2.php';
						break;
		case "cancel":
						header('Location:'.$_SERVER['PHP_SELF']);
						break;
		
	}
}
?>


