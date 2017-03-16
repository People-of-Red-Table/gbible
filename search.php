<h1><a href="./?menu=search"><?=$text['text_search'];?></a></h1>
<?php
	if (isset($_REQUEST['search_query']))
		$search_query = $_REQUEST['search_query'];
	else $search_query = '';

	$statement_translation = $links['sofia']['pdo'] -> prepare(
			'select bh.b_code, bh.table_name, bh.title, bh.description, bh.copyright, 
			bh.license, l.link, bh.http_link, country, language, dialect
			from b_shelf bh 
			join licenses l on l.license = bh.license
			where b_code = :b_code'
		);

	if (isset($_REQUEST['search_in']))
		$search_in = $_REQUEST['search_in'];
	else $search_in = $b_code;
	
	$result_translation = $statement_translation -> execute(array('b_code' => $search_in));
	if(!$result_translation)
		log_msg(__FILE__ . ':' . __LINE__ . ' PDO translations query exception. Info = {' . json_encode($statement_translation -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}' );
	$info_row = $statement_translation -> fetch();
	$bible_title = $info_row['title'];
	$bible_description = $info_row['description'];

	if (stripos($search_in, 'http') === FALSE)
	{
?>
<form method="post">
	<input type="hidden" name="menu" value="search" />
	<input type="hidden" name="search_in" value="<?=$search_in;?>" />
	<div class="input-group input-group-sm">
		<input type="text" class="form-control" name="search_query" placeholder="<?=$text['search_in'].$info_row['title'];?>" value="<?=$search_query;?>" />
		<span class="input-group-btn"><input type="submit" class="btn btn-default" name="submit" value="<?=$text['text_search'];?>"></span>
	</div>
</form>
<br />
<?php
		if(strlen($search_query) > 2)
		{
			$special_characters = [' ', '\'', '"', '.', ',','?', '!', '[',']','{','}','(',')','@','#','$','^','&','*','¿','¡', '-', '=', '+', '_', '/', '\\'];

			for($i = 0; $i < strlen($search_query); $i++)
			{
				if (in_array($search_query[$i], $special_characters))
					$search_query[$i] = '%';
			}
			$search_query = htmlspecialchars($search_query);
			$search_query = mysqli_real_escape_string($mysql, $search_query);

			$query = 'select book, chapter, startVerse, verseText from ' . $info_row['table_name'] . ' where verseText like \'%' . $search_query . '%\'
						
				';
			$search_result = mysqli_query($mysql, $query);

			if ($search_result)
			{
				if (mysqli_num_rows($search_result) > 0)
				{
					echo '<table class="table table-striped">';
					while($row = mysqli_fetch_assoc($search_result))
					{
						echo '<tr><td><a href="./?b_code=' . $info_row['b_code'] . '&book=' . $row['book'] . '&chapter=' . $row['chapter'] . '&verse=' . $row['startVerse'] . '"><b>' . $row['book'] . ' ' . $row['chapter'] . ':' . $row['startVerse'] . '</b></a><br /><br />' . $row['verseText'] . '</td></tr>';
					}
					echo '</table>';
				}
				else
				{
					$msg_type = 'info';
					$message = $text['repharase_search'];
				}
			}
			else
			{
				$msg_type = 'danger';
				$message = $text['search_exception'];
			}
		}
	}

	if (isset($msg_type) and isset($message))
	{
		echo '<p class="alert alert-' . $msg_type . '">' . $message . '</p>';
	}

?>