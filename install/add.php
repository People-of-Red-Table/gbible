<?php

// *_about.htm

// info.html is mirror of http://ebible.org/bible/

/*
	Archives' preparing... 
*/
	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

	require '../config.php';

	$log = fopen('log.txt', 'w');

	if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
	{
		$argv = $_SERVER['argv'];
		$argc = $_SERVER['argc'];			
		if ($argc > 1)
		{
			$from_vpl = $argv[1];
		}
		else
			echo "You've got to add name of translation as argument: `php -f add.php translation_vpl`";
	}
	else
	$from_vpl = $_REQUEST['vpl'];

	if (stripos($from_vpl, '_vpl') === FALSE)
  	{
    	$from_vpl .= '_vpl';
  	}

	function replace_name($text)
	{
		return str_ireplace('sofia.', '', $text);
	}

	/*$links = file('urls.txt');
	foreach ($links as $item) 
	{
		exec('wget ' . $item);
		$zip = new ZipArchive;
		$res = $zip -> open(basename($item));
		if ($res === TRUE)
		{
			$zip -> extractTo('./');
			$zip -> close();
		}
		else
		{
			fwrite($log, 'ZIP extracting of ' . $item . '... not happened...');
		}

	}*/

/*
	
*/


	$dir_path = './';
	$dh = opendir($dir_path);
	$total_count = 758;
	$counter = 0;
	$licensed = 0;
	$info = file('info.html');

	$link = $links['sofia']['mysql'];
	mysqli_set_charset($link,'utf8');
	if (!$link) { echo mysqli_connect_error(); exit;};

	$file_dir = $from_vpl;
		if(is_dir($file_dir) && strpos($file_dir, '_vpl') !== FALSE)
		{
			$counter++;

			$done = false;
			while($done === false)
			{
				if(!$link)
				{
					fwrite($log, 'Reconnect.' . PHP_EOL);
					$link = open_connection($db, 'mysql', 'sofia');
					mysqli_set_charset($link,'utf8');
					if (!$link) { fwrite($log, mysqli_connect_error()); };
				}
				echo $file_dir . PHP_EOL;

				$row = array('country' => '', 'language' => '', 'dialect' => '', 
					'b_code' => '', 'title' => '', 'description' => '', 'copyright' => '', 'license' => '', 'table_name' => '');

				$row['b_code'] = str_replace('_vpl', '', $file_dir);

				$subdir_path = $dir_path . $file_dir;
				$subdir_h = opendir($subdir_path);
				$about_file = '';
				
				while ( ($subfile = readdir($subdir_h)) !== FALSE)
				{
					if (strpos($subfile, '_about.htm') !== FALSE)
					{
						$about_file = $subfile;
						break;
					}

				}


				if ($about_file == '') { echo '`_about.htm` file for ' . $file_dir . ' not found' . PHP_EOL; break; }
				else echo $about_file . PHP_EOL;
				
				$about_file = './' . $file_dir . '/' . $about_file;
				$string = file_get_contents($about_file);
				if (strpos(strtolower($string), 'public domain') !== FALSE)
					$row['license'] = 'Public Domain';
				elseif (strpos(strtolower($string), 'creativecommons.org/licenses/by-nd/4.0') !== FALSE)
					$row['license'] = 'Creative Commons ND 4.0';
				elseif (strpos(strtolower($string), 'creativecommons.org/licenses/by-nc-nd/4.0') !== FALSE)
					$row['license'] = 'Creative Commons NC ND 4.0';
				elseif (strpos(strtolower($string), 'creativecommons.org/licenses/by/3.0') !== FALSE)
					$row['license'] = 'Creative Commons 3.0';
				elseif (strpos(strtolower($string), 'creativecommons.org/licenses/by-nd/3.0') !== FALSE)
					$row['license'] = 'Creative Commons ND 3.0';
				elseif (strpos(strtolower($string), 'creativecommons.org/licenses/by-sa/3.0') !== FALSE)
					$row['license'] = 'Creative Commons SA 3.0';
				elseif (strpos(strtolower($string), 'http://creativecommons.org/licenses/by-nc-nd/3.0/') !== FALSE)
					$row['license'] = 'Creative Commons NC ND 3.0';
				elseif (strpos(strtolower($string), 'Creative Commons') !== FALSE)
					$row['license'] = 'Creative Commons';
				else
				{
					// skip translation without Share License 
					$done = true; 
					continue; 
				}
				$licensed++;
				$lines = file($about_file);
				$row['title'] = trim(strip_tags($lines[13]));
				$row['description'] = trim(strip_tags($lines[14]));


				foreach ($info as $item) 
				{
					if (strpos($item, "/" . $row['b_code'] . "'") !== FALSE)
					{
						$row['country'] = substr($item, 4, strpos($item, '</td>') - 4);
						$row['country'] = strip_tags($row['country']);
					}
				}

				foreach ($lines as $line) 
				{
					if (strpos(strtolower($line), 'language') !== FALSE)
					{
						$row['language'] = strip_tags($line);
						$row['language'] = str_ireplace('language', '', $row['language']);
						$row['language'] = trim(str_replace(':', '', $row['language']));
						echo $row['language'] . ';';
					}

					if (strpos(strtolower($line), 'dialect') !== FALSE)
					{
						$row['dialect'] = strip_tags($line);
						$row['dialect'] = str_ireplace('dialect', '', $row['dialect']);
						$row['dialect'] = trim(str_replace(':', '', $row['dialect']));
						echo $row['dialect'] . ';';
					}
						// strpos(haystack, needle)
					
					if (
						(stripos($line, 'copyright') !== FALSE)
						and (stripos($line, 'http') !== FALSE)
						)
					{
						$line = str_ireplace('">', '" target="_blank">', $line);
						$line = str_ireplace('<p>', '', $line);
						$line = str_ireplace('<br />', '', $line);
						$row['copyright'] = $line;
					}
				}

				//$array  = array('country', 'language', 'dialect', 'b_code', 'title', 'description', 'copyright');
				foreach ($row as $key => &$value) 
				{
					if ($key == 'copyright') continue;
					$value = strip_tags($value);
					$value = htmlspecialchars($value);
					$value = str_replace('(', '[', $value);
					$value = str_replace(')', ']', $value);
				}

				echo strip_tags($row['country'] .';' . $row['language'] . ';' . $row['dialect'] . ';'
					. $row['b_code'] . ';' . $row['title'] . ';' . $row['description'] .';' . $row['copyright']) . PHP_EOL;

				fwrite($log, strip_tags($row['country'] .';' . $row['language'] . ';' . $row['dialect'] . ';'
					. $row['b_code'] . ';' . $row['title'] . ';' . $row['description'] .';' . $row['copyright']) . PHP_EOL);

				$queries = file('./' . $file_dir . '/' . $row['b_code'] . '_vpl.sql');
				
				try 
				{
					$result = mysqli_query($link, replace_name($queries[1]));
				} 
				catch (Exception $e) 
				{
				if (!$result) { fwrite($log, '`' . $queries[1] . '` ' . mysqli_error($link));}
					continue;
				}

				$array = explode('.', $queries[1]);
				$row['table_name'] = str_replace(';', '', $array[1]);				
				

				$query = '';
				for ($i = 2; $i < 10; $i++ )
				{
					$query .= $queries[$i];
				}
				try 
				{
					$result = mysqli_query($link, replace_name($query));					
				} 
				catch (Exception $e) 
				{
					if (!$result) { fwrite($log,  '`' . $query . '` ' . mysqli_error($link));}
					continue;
				}

					try 
					{
						$result = mysqli_query($link, $queries[10]);
						
					} catch (Exception $e) 
					{
						if (!$result) { fwrite($log,  '`' . $queries[10] . '` ' . mysqli_error($link));}
						continue;				
					}


				for ($i = 11; $i < count($queries); $i++ )
				{
					//$query = mb_convert_encoding($queries[$i], 'UTF-8');
					$query = iconv(mb_detect_encoding($queries[$i]), 'UTF-8', $queries[$i]);
					try 
					{
						$result = mysqli_query($link, $query);	
					} 
					catch (Exception $e) 
					{
						if (!$result) { fwrite($log,  '`' . $query . '` ' . mysqli_error($link));}
						continue;
					}
				}

				$result = true;
				// b_shelf
				try 
				{
					$dbh = $links['sofia']['pdo'];
					$command = $dbh -> prepare('insert into b_shelf (country,language,dialect,b_code,title,description,copyright,license, table_name) 
								values (:country,:language,:dialect,:b_code,:title,:description,:copyright,:license, :table_name);');
					$dbh -> beginTransaction();
					$result = $command -> execute($row);
					$dbh -> commit();	
				} 
				catch (Exception $e) 
				{
					continue;
				}

				if ($result === true)
					$done = true;
			}
		}

		
	closedir($dh);

	mysqli_close($link);

	fclose($log);


	echo 'script is executed.';


?>