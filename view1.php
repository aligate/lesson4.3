<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Список дел</title>
<style>
    table { 
        border-spacing: 0;
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
    
    table th {
        background: #eee;
    }
</style>
</head>
<body>
<h2>Приветствую Вас, <?= $login_in_session;?>!</h2>
<p><a href="logout.php">Выход</a></p>
<h1>Список дел на сегодня</h1>
<div style="float: left">
    <form action= "tasks.php" method="POST">
        <input type="text" name="description" placeholder="Описание задачи" value="" />
        <input type="submit" name="save" value="Добавить" />
    </form>
</div>
<div style="float: left; margin-left: 20px;">
    <form method="POST">
        <label for="sort">Сортировать по:</label>
        <select name="sort_by">
            <option value="date_added">Дате добавления</option>
            <option value="is_done">Статусу</option>
            <option value="description">Описанию</option>
        </select>
        <input type="submit" name="sort" value="Отсортировать" />
    </form>
</div>
<div style="clear: both"></div>

<table>
    <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th>Операции</th>
		 <th>Автор</th>
		<th>Ответственный</th>
       
        <th>Закрепить задачу за пользователем</th>
   
	<?php foreach( $array as $arr =>$item):?>
<tr>
  <td><b><?= htmlspecialchars($item['description']); ?></b></td>
  <td><?= $item['date_added']; ?></td>
	<?php if($item['is_done'] == 1): ?>
  <td><span style='color: green;'>Выполнено</span></td>
	<?php else : ?>
	<td><span style='color: orange;'>В процессе</span></td>
	<?php endif; ?>
  <td>
        <a href='?id=<?= $item['task_id']; ?>&action=edit'>Изменить</a>
        <a href='?id=<?= $item['task_id']; ?>&action=done'>Выполнить</a>
        <a href='?id=<?= $item['task_id']; ?>&action=delete'>Удалить</a>
    </td>
	 <td><?= $login_in_session; ?></td>
  <td><?= $item['login'];?></td>
  <td><form method='POST'>
  
	<input type="hidden" name="task_id" value="<?=$item['task_id'];?>">
	
  <select name='assigned_user_id'>
  <?php foreach($array_user as $key=>$value): ?>
	 <?php if($value['login'] == $login_in_session): ?>
		<option  selected disabled>выбрать</option>
	<?php else: ?>
		<option value="<?= $value['id']; ?>"><?= $value['login'];?></option>
   <?php endif; ?>
  <?php endforeach; ?>
  </select>  
  <input type='submit' name='assign' value='Переложить ответственность' />
  </form></td>
</tr>
	<?php endforeach; ?>
</table>
<p><strong>Список заданий, выполнение которых от Вас ожидают другие люди:</strong></p>


<table>
        <tr>
            <th>Описание задачи</th>
            <th>Дата добавления</th>
            
            <th>Статус</th>
            <th></th>
            <th>Ответственный</th>
            <th>Автор</th>
            </tr>
			<?php foreach( $array_assigned as $key =>$value):?>
<tr>
  <td><b><?= htmlspecialchars($value['description']); ?></b></td>
  <td><?= $value['date_added']; ?></td>
	<?php if($value['is_done'] == 1): ?>
  <td><span style='color: green;'>Выполнено</span></td>
	<?php else : ?>
	<td><span style='color: orange;'>В процессе</span></td>
	<?php endif; ?>
  <td>
       <a href='?id=<?= $value['task_id']; ?>&action=done'>Выполнить</a>
   </td>
	 <td><?= $login_in_session; ?></td>
  <td><?= $value['login'];?></td>
  <?php endforeach; ?>
</table>
</body>
</html>