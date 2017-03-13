<?php

	$crlf = PHP_EOL;
	if (isset($_SERVER['REMOTE_ADDR']))
	{
		$crlf = '<br />';
	}

	$dir_path = '../bibles/';
	$dh = opendir($dir_path);

	while ( ($file = readdir($dh)) !== FALSE)
	{
		$array = explode('.', $file);
		if (stripos($file, '.zip') !== FALSE and !file_exists($dir_path . $array[0]))
		{
			echo $file . $crlf;
			$zip = new ZipArchive;
			$res = $zip -> open($dir_path . $file);
			if ($res === TRUE)
			{
				mkdir($dir_path . $array[0]);
				$zip -> extractTo($dir_path . $array[0]);
				$zip -> close();
			}
			else
			{
				echo 'ZIP extracting of ' . $dir_path . $file . '... not happened...' . $crlf;
				echo json_encode($zip);
			}
		}
	}

?>