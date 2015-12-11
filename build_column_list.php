<?php

function review_table($table_name)
{
	$result = mysql_query("DESCRIBE " . mysql_real_escape_string($table_name));
	$clean_table_name = htmlentities($table_name);
	echo "<option label>{$clean_table_name}</option>\n";
	
	while ($row = mysql_fetch_array($result))
	{
		echo "<option>" . $clean_table_name . "." .  $row[0] . "</option>\n";
	}	
}

mysql_connect('localhost', 'root');
mysql_select_db('pika');
review_table('cases');
review_table('contacts');
exit();