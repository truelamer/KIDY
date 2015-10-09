
        <h1>Генератор данных</h1>
        <p class="lead">Выберите план генерации</p>
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
