
        <h1>Проектирование базы данных</h1>
		<p>Что делает скрипт:</p>
		<ol>
			<li>Удаляет старую базу данных</li>
			<li>Создает новую</li>
			<li>Создает таблицы из файла data/structure/create.sql </li>
			<li>Создает представления из папки data/views </li>
			<li>Генерирует данные </li>
			<li>Тестирует представления </li>
		</ol>
        <p class="lead">Для продолжения выберите план генерации</p>
			<form action="run.php" method="post" style="width:200px;">
				<p>
					<select size="3" name="plan"  class="form-control">
						<? foreach ($plans as $plan) { ?>
							<option value="<?=$plan?>"><?=basename($plan, ".php")?></option>
						<? } ?>	
					</select>
			   </p>
				<p>
					<button type="submit" class="btn btn-primary">Выполнить</button>
				</p>	
			</form>
