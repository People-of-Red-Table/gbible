<?php

	$query = 'insert into iso_ms_languages (language_code, language_name, country_name, country_code, iso_language_code) 
		select :language_code, :language_name, :country_name, :country_code, :iso_language_code from dual where not exists 
		(select * from iso_ms_languages where language_code = :language_code);';

		if (!isset($_SERVER['REMOTE_ADD']))
			$_SERVER['REMOTE_ADD'] = '127.0.0.1';

	require '../charity.php';
	require '../config.php';

	$statement = $pdo -> prepare($query);
	foreach ($charity as $item) 
	{
		if (strpos($item['Code'], '-') !== FALSE)
		{
			$array = explode('-', $item['Code'])[1];
			$row = ['language_code' => $item['Code'], 'language_name' => $item['Language'], 'country_name' => $item['Country'], 
			'country_code' => $array[1], 'iso_language_code' => $array[0]];
			$result = $statement -> execute($row);
			if (!$result)
			{
				echo '<br />' . json_encode($statement -> errorInfo()) . '<br />';
			}
			else echo '.';
		}
	}
	$query = 'update iso_ms_languages iml set native_language_name = (select native_name from iso_639_1_languages i6l where i6l.language_name = iml.language_name) where native_language_name is null';
	$statement = $pdo -> prepare($query);
	$result= $statement -> execute();
	if ($result) echo 'Native languages are updated';
	else echo json_encode($statement -> errorInfo());
	$query = 'update iso_ms_languages iml set native_language_name = language_name where native_language_name is null';
	$statement = $pdo -> prepare($query);
	$result= $statement -> execute();
	if ($result) echo 'Native languages are updated';
	else echo json_encode($statement -> errorInfo());
	echo 'The End of Script';
?>