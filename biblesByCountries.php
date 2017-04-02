<h1><?php
		echo $text['bibles_by_countries'];
		if (isset($_REQUEST['bible_country']))
		{
			$query = 'select native from countries where name = :country';
			$statement_native_country_name = $pdo -> prepare($query);
			$statement_native_country_name -> execute(['country' => $_REQUEST['bible_country']]);
			$row = $statement_native_country_name -> fetch();
			echo '. ' . $row['native']. '.';
		}
	?></h1>
<br/>

<?php
	if(!isset($_REQUEST['bible_country']))
	{
		$query = 'select distinct country `country_name`, 
					case when cnt.native is null then t.country else cnt.native end `country`
			from
			(select country from b_shelf 
			union all
			select country_name from iso_ms_languages
					where language_name in (select language_name from b_shelf)
			) as t
			left join countries cnt on t.country = cnt.name
			order by country';
		$result_countries = mysqli_query($links['sofia']['mysql'], $query);
		if (!$result_countries)
		{
			log_msg(__FILE__ . ':' . __LINE__ . ' 
				Countries SQL exception. Info = {' . mysqli_error($links['sofia']['mysql']) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
			//echo "Whoops. We've got issue with MySQL connection... Sorry. Please contact support. " . mysqli_error($links['sofia']['mysql']);;
			//print_r($links['sofia']['mysql']);
			$msg_type = 'danger';
			$message = '';
		}
		else
		{
			while($row = mysqli_fetch_assoc($result_countries))
			{	
				echo '<a href="./?menu=biblesByCountries&bible_country=' . $row['country_name'] . '">' . $row['country'] . '</a><br/>';
			}
		}
	}
	else
	{
		$query = '
			select b_shelf.b_code, b_shelf.title, b_shelf.description, b_shelf.country, b_shelf.language from b_shelf 
			where b_shelf.language in
			(
			select language_name from iso_ms_languages where country_name = :country  and language_name in (select distinct language from b_shelf)
			union
			select language `language_name` from b_shelf where country = :country)
			';
		$statement_bibles = $pdo -> prepare($query);
		$bibles_result = $statement_bibles -> execute(['country' => $_REQUEST['bible_country']]);

		if ($bibles_result)
		{
			$bibles_rows = $statement_bibles -> fetchAll();
			echo '<table clas=="table table-striped">';
			foreach ($bibles_rows as $row) 
			{
				echo '<tr><td><a href="./?menu=bible&country=' . $_REQUEST['bible_country'] . '&language=' . $row['language'] . '&b_code=' . $row['b_code'] . '">' . $row['title'] . '</a>'
						. '<p>' . $row['description'] . '</p>'
						.'</td></tr>';
			}
			echo '</table>';
		}
		else
		{
			$msg_type = 'danger';
			$message = '';
		}
	}
?>