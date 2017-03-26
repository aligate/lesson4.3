<html>
  <head>
    <meta charset="utf-8">
    <title>Редактирование</title>
    
  </head>
  <body>

    <h2>Редактирование заданий</h2>

   <form action = "tasks.php" method = 'POST'>
		<b>Текст:</b> <input type ='text' name='description' size = '50' value ='<?= htmlspecialchars($array['description']); ?>' > <b>Статус:</b>
		<select name="is_done">
		<?php if(!$array['is_done']): ?>
            <option value="<?= $array['is_done']; ?>"><span style='color: orange;'>В процессе</span></option>
            <option value="1">Выполнено</option>
			<?php else: ?>
			<option value="<?= $array['is_done']; ?>"><span style='color: green;'>Выполнено</span></option>
            <option value="0">В процессе</option>
		<?php endif; ?>
           </select>
		<input type ='hidden' name='id' value ='<?= $array['id']; ?>' >
		<input type="submit" name="change" value="Изменить" />
	</form>
	
	<p style ="color: blue; font-size:20px;"><a href="?action=cancel">Или вернуться к списку заданий</p>
  </body>
</html>
