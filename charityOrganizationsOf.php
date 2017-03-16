<?php
	$statement_country_name = $links['sofia']['pdo'] -> prepare('select native from countries where code = :code');
	$result_country_name = $statement_country_name -> execute(['code' => $charity_country]);
	$country_row = $statement_country_name -> fetch();
?>

<h1><?=$text['charity_organizations_of'];?> <?=$country_row['native'];?>.</h1>
<div class="container">
<?php

	$statement_charities = $links['sofia']['pdo'] -> prepare('
								select 
										case when (length(co.native_name) < 2) or (co.native_name is null) then co.english_name
										else co.native_name 
										end `name`, 
										co.description, co.http_link, cot.name `type` 
								from charity_organizations co
								join charity_organization_types cot on co.charity_organization_type_id = cot.id
								where country_code = :code 
								order by co.charity_organization_type_id asc
					');
	$result_charities = $statement_charities -> execute(['code' => $charity_country]);
	if (!$result_charities)
	{
		$msg_type = 'danger';
		$message = $text['charity_organizations_exception'];
		log_msg(__FILE__ . ':' . __LINE__ . ' Charities PDO query exception. Info = {' . json_encode($statement_charities -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
	}
	else
	{
		if ($statement_charities -> rowCount() > 0)
		{
			foreach ($statement_charities -> fetchAll() as $charity_row) 
			{
				if (strlen($charity_row['name']) < 5)
					$charity_row['name'] = $charity_row['http_link'];
				echo '<a href="' . $charity_row['http_link'] . '" target="_blank">' . $charity_row['name'] . '</a>'
					.'<p>' . $charity_row['description'] . '</p>';
			}
		}
		else
		{
			$msg_type = 'info';
			$message = $text['charity_not_found'];
		}
	}

	if (isset($msg_type) and isset($message))
	{
		echo '<div class="alert alert-' . $msg_type . '">' . $message . '</div>';
	}
?>
</div>