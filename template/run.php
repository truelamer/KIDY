<h1>Проектирование базы данных</h1>
<p>План генерации выполнен.</p>

<p>Результаты тестирования:</p>
  
<table class="table">
	<tr>
		<th>#</th>
		<th>Представление</th>
		<th>Количество строк</th>
	</tr>
	<? for ($i = 0; $i<count($test_counts); $i++) { ?>
		<tr>
			<td>
				<?=$i+1?>
			</td>		
			<td>
				<?=$test_counts[$i]["view_name"]?>
			</td>
			<td>
				<?=$test_counts[$i]["count"]?>
			</td>			
		</tr>	

	<? } ?>
</table>
 
<a class="btn btn-default" href="/">Назад</a>