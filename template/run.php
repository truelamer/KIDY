<h1>�������������� ���� ������</h1>
<p>���� ��������� ��������.</p>

<p>���������� ������������:</p>
  
<table class="table">
	<tr>
		<th>#</th>
		<th>�������������</th>
		<th>���������� �����</th>
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
 
<a class="btn btn-default" href="/">�����</a>