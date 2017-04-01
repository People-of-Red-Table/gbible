<h1><a href="./?menu=search"><?=$text['text_search'];?></a></h1>
<?php

	if (!isset($_REQUEST['page']))
		$page = 1;
	else
		$page =$_REQUEST['page'];

	$page_nav = '<ul class="pagination">';
	$prev_next_nav = $page_nav;


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

			$query = 'select * from ' . $info_row['table_name'] . ' where verseText like \'%' . $search_query . '%\''.
						' limit ' . ($page - 1) * $_SESSION['tf_verses_per_page'] . ', ' . $_SESSION['tf_verses_per_page'];
			$search_result = mysqli_query($mysql, $query);

			$all_results_query = 'select * from ' . $info_row['table_name'] . ' where verseText like \'%' . $search_query . '%\'';
			$all_results = mysqli_query($mysql, $all_results_query);

			/////////////////////////////

			$verse_count = mysqli_num_rows($all_results);
			$page_count = floor($verse_count / $_SESSION['tf_verses_per_page']);

			if ($verse_count % $_SESSION['tf_verses_per_page'] !== 0)
				$page_count++;

			if ($page_count > 1)
			{
				if ($page != 1)
					$page_nav .= '<li><a href="./?menu=search&search_query=' . $_REQUEST['search_query'] . '&page=1">' . $text['first_page'] . '</a></li>';
				else
					$page_nav .= '<li><a><b>' . $text['first_page'] . '</b></a></li>';

				for ($i=1; $i <= $page_count; $i++)
				{
					if ($page != $i)
						$page_nav .= '<li><a href="./?menu=search&search_query=' . $_REQUEST['search_query'] . '&page=' . $i . '">' . $i . '</a></li>';
					else
						$page_nav .= '<li><a><b>' . $i . '</b></a></li>';
				}
				if ($page != $page_count)
					$page_nav .= '<li><a href="./?menu=search&search_query=' . $_REQUEST['search_query'] . '&page=' . $page_count . '">' . $text['last_page'] . '</a></li>';
				else
					$page_nav .= '<li><a><b>' . $text['last_page'] . '</b></a></li>';

				$page_nav .= '</ul>';

				if ($page !== 1)
					$prev_next_nav .= '<li><a href="./?menu=search&search_query=' . $_REQUEST['search_query'] . '&page=' . ($page - 1) . '">' . $text['text_previous'] . '</a></li>';

				if ($page < $page_count)
					$prev_next_nav .= '<li><a href="./?menu=search&search_query=' . $_REQUEST['search_query'] . '&page=' . ($page + 1) . '">' . $text['text_next'] . '</a></li>';


				$prev_next_nav .= '</ul><br />';

			}
			else
			{
				$page_nav = '';
				$prev_next_nav = '';
			}

			echo $page_nav . $prev_next_nav;

			/////////////////////////////
			if ($search_result)
			{
				if (mysqli_num_rows($search_result) > 0)
				{
					echo '<table class="table table-striped">';
					while($row = mysqli_fetch_assoc($search_result))
					{
						if (strpos($row['verseText'], '¶') !== FALSE)
						{
							$row['verseText'] = str_replace('¶', '', $row['verseText']);
						}
						echo '<tr><td><a href="./?b_code=' . $info_row['b_code'] . '&book=' . $row['book'] . '&chapter=' . $row['chapter'] . '&verse=' . $row['startVerse'] . '"><b>' . $row['book'] . ' ' . $row['chapter'] . ':' . $row['startVerse'] . '</b></a><br /><br />' . html_verse($row) . '</td></tr>';
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

	echo $prev_next_nav . $page_nav;

?>