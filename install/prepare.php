<?php

// *_about.htm

// info.html is mirror of http://ebible.org/bible/

/*
	Archives' preparing... 
*/

	if (isset($_SERVER['REMOTE_ADDR']))
	{
		echo 'What are you doing here, genius? Trying to help me to install Bible Site? =]<br />';
		echo 'You shall not run install via web. <br />';
		exit;
	}
	else
	{
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		require '../config.php';
	}

	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
	require '../config.php';
	$log = fopen('log.txt', 'w');

	$argv = $_SERVER['argv'];
	$argc = $_SERVER['argc'];

	$skip = false;
	$from_vpl = 'nhwBl_vpl';

	if ($argc > 1)
	{
		$from_vpl = $argv[1];
		$skip = true;
	}

	if ($skip and stripos($from_vpl, '_vpl') === FALSE)
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

	if(!$skip)
	{
		$result = mysqli_query($link, 'drop table if exists b_shelf;');
		if (!$result) { echo mysqli_error($link); exit;}

		$result = mysqli_query($link, 'create table b_shelf(
						id int primary key auto_increment,
						country varchar(100),
						language varchar(200),
						dialect varchar(200),
						b_code varchar(20),
						table_name varchar(20),
						title varchar(500),
						description varchar(1000),
						translated_by varchar(200),
						copyright varchar(1000),
						license varchar(50)
						);
					');
		if (!$result) { echo mysqli_error($link); exit;}
	}

	while ( ($file_dir = readdir($dh)) !== FALSE)
	{
		if(is_dir($file_dir) && strpos($file_dir, '_vpl') !== FALSE)
		{
			$counter++;
			if (($skip === true) and ($file_dir == $from_vpl)) $skip = false;
			if ($skip === true) continue;

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

		// Progress
		echo 'Progress: ' . (integer)(($counter * 100) / $total_count) . '%' . PHP_EOL;
	}
	closedir($dh);


	echo 'Count: ' . $counter . PHP_EOL;
	echo 'Share Licensed: ' . $licensed . PHP_EOL;

	$queries = file('prepare.sql');
	while ($done === false) 
	{
		if(!$link)
		{
					fwrite($log, 'Reconnect.' . PHP_EOL);
					$link = mysqli_connect($host, $user, $password, $database);
					mysqli_set_charset($link,'utf8');
					if (!$link) { fwrite($log, mysqli_connect_error()); };
		}
		$result = true;
		foreach ($queries as $query) 
		{
			if (strlen($query) > 5)
			{
				try 
				{
					$result = mysqli_query($link, $query);					
				} 
				catch (Exception $e) 
				{
					if (!$result) { fwrite($log, '`' . $query . '` ' . mysqli_error($link));}
					$result = false;
					break;					
				}
			}
		}
		if($result === true)
			$done = true;
	}


	require './add_languages.php';

	mysqli_close($link);

	fclose($log);














?>