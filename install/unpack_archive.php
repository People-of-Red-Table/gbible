<?php

	$crlf = '<br />';

	echo 'ZIP archive extractor.' . $crlf;

	$dir_path = '../bibles/';

	if (isset($_REQUEST['file']))
		$file = $_REQUEST['file'];
	else
	{
		echo 'You have to set GET variable `file` with archive to unpack.' ;
		exit;
	}

	if (strpos($file, '.zip') === FALSE)
		$file .= '.zip';
	eco $file . $crlf;
	$array = explode('.', $file);
	if (stripos($file, '.zip') !== FALSE and !file_exists($dir_path . $array[0]))
	{
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
	echo 'The end of script.' . $crlf;
?>