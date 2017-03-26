<?php
require_once 'pdo.php';

$message = [];
// Регистрация
if(isset($_POST['regist']))
{

	$login = trim(addslashes($_POST['login']));
	$password = trim(addslashes($_POST['password']));

	if($login==='')
	{
		$message['error'][] = "<p style='color: red'; >Введите новый логин</p>";
	}
	if($password==='') 
	{
		$message['error'][] = "<p style='color: red'; >Придумайте ваш пароль</p>";
	}

	$stmt = $pdo->prepare("SELECT * FROM user WHERE login = '{$login}'");
	$stmt->execute();
	if($stmt->rowCount() > 0)
	{
	$message['error'][] = "<p style='color: red'; >Пользователь с данным логином уже существует</p>";
	}
	if(empty($message['error']))
	{
	
	$sql = "INSERT INTO user (login, password) VALUES (:login, :password)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['login' =>$login, 'password' =>md5($password)]);
	if($stmt->rowCount() === 1)
		{
		$message['result'][] = "<p style='color: green;'>Вы успешно зарегистрированы, вы можете авторизоваться</p>";
		}
	}
	}
//Авторизация
		if(isset($_POST['auth']))
{
	$array=[];
	$log = trim(addslashes($_POST['login']));
	$pass = trim(addslashes($_POST['password']));

	if($log==='')
	{
		$message['error'][] = "<p style='color: red'; >Введите ваш логин</p>";
	}
	if($pass==='') 
	{
		$message['error'][] = "<p style='color: red'; >Введите ваш пароль</p>";
	}

	$stmt = $pdo->prepare("SELECT * FROM user WHERE login = :login AND password = :password");
	$stmt->execute(['login'=>$log, 'password'=>md5($pass)]);
	if($stmt->rowCount() !== 1)
	{
	$message['error'][] = "<p style='color: red'; >Неверные входные данные</p>";
	}
	else{
		$array = $stmt->fetch(PDO::FETCH_ASSOC); 
		$_SESSION['users']=$array;
		header('Location: tasks.php');
		
	}
}
?>

<html>
  <head>
    <meta charset="utf-8">
	
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
    <title>Вход на сайт</title>
   </head>
  <body> 
    <section>
    <div class="container">
        <div class="col-sm-4 col-sm-offset-4 padding-right">

              <h2>Регистрация</h2>
                    <form  method="post">
                        <input type="text" name="login" placeholder="логин" value="<?= @$login; ?>"/>
                        <input type="password" name="password" placeholder="Пароль" value=""/>
						<input type="submit" name="regist"  value="Вход" />
					</form>
						<h3>Или авторизируйтесь</h3>
					<form  method="post">
						<input type="text" name="login" placeholder="введите ваш логин" value="<?= @$log; ?>"/>
						 <input type="password" name="password" placeholder="Пароль" value=""/>
						<input type="submit" name="auth" value="Вход" />
					
					</form>
               
				<?php 
				foreach($message as $type)
				{
					echo array_shift($type);
				}
			
				 ?>
				
	    </div>
       </div>
</section>
 </body>
</html>
