<?php
	require "../config.php";
	$country_name = $_REQUEST['country'];

	$statement_country = $links['sofia']['pdo'] -> prepare('select country_code from iso_ms_languages where country_name = :country_name');
	$statement_country_result = $statement_country -> execute(array('country_name' => $country_name));
	if (!$statement_country_result)
		log_msg(__FILE__ . ':' . __LINE__ . ' Country codes PDO exception. ' . json_encode($statement_country -> errorInfo()) . ', $_REQUEST = {' . json_encode($_REQUEST) . '}');
	$country_row = $statement_country -> fetch();

	setcookie('country', $country_row['country_code'], time() + 30 * 24 * 3600);

	$statement = $links['sofia']['pdo'] -> prepare(
			'
				select distinct t.language_name, case when iso_ms.native_language_name is null then t.language_name else iso_ms.native_language_name end `native_language_name`, iso_ms.iso_language_code `language_code` from
				(
					select distinct language `language_name` from b_shelf
					where country = :country_name
					union all
					select distinct language `language_name` from b_shelf
					where language in 
						(
							select language_name from iso_ms_languages
							where country_name = :country_name and language_name in (select distinct language from b_shelf)
						)
				) as t

			left join iso_ms_languages iso_ms on t.language_name = iso_ms.language_name
			order by native_language_name
		');
	$result_languages = $statement -> execute(array('country_name' => $country_name));

	if (!$result_languages)
	{
		log_msg(__FILE__ . ':' . __LINE__ . ' Languages PDO exception. ' . json_encode($statement -> errorInfo()) . ', $_REQUEST = {' . json_encode($_REQUEST) . '}');
		//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. "
		;
		//print_r($links['sofia']['pdo']);

	}
	else
	{
		echo json_encode($statement -> fetchAll());		
	}
	
?>