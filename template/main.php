
        <h1>�������������� ���� ������</h1>
		<p>��� ������ ������:</p>
		<ol>
			<li>������� ������ ���� ������</li>
			<li>������� �����</li>
			<li>������� ������� �� ����� data/structure/create.sql </li>
			<li>������� ������������� �� ����� data/views </li>
			<li>���������� ������ </li>
			<li>��������� ������������� </li>
		</ol>
        <p class="lead">��� ����������� �������� ���� ���������</p>
			<form action="run.php" method="post" style="width:200px;">
				<p>
					<select size="3" name="plan"  class="form-control">
						<? foreach ($plans as $plan) { ?>
							<option value="<?=$plan?>"><?=basename($plan, ".php")?></option>
						<? } ?>	
					</select>
			   </p>
				<p>
					<button type="submit" class="btn btn-primary">���������</button>
				</p>	
			</form>
