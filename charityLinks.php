<h1><?=$text['charity_of_world'];?></h1>
<p><?=$text['choose_a_country'];?>.</p>
<?php

	$query = 'select cnt.name `country`, lower(cnt.code) `country_code`, cont.name `continent`, cont.sort from countries cnt
			join continents cont on cnt.continent = cont.code
			where cnt.code in (select distinct country_code from charity_organizations)
			order by cont.sort, cnt.name asc
	';

	$statement_countries = $links['sofia']['pdo'] -> prepare($query);

	$result_countries = $statement_countries -> execute();
	$counter = 0;
	if ($result_countries)
	{
	$countries_rows = $statement_countries -> fetchAll();

		$continent = '';
		foreach ($countries_rows as $country_row) 
		{
			if ($continent !== $country_row['continent'])
			{
				$continent = $country_row['continent'];
				echo '<h3>' . $continent . '</h3><br />';
			}
			echo '<a href="./?menu=charityOrganizationsOf&charity_country=' . $country_row['country_code'] . '">' . $country_row['country'] . '</a><br />';
		}
		$counter++;
	}
	else
	{
		echo '<div class="alert alert-danget">' . $text['charity_of_world_exception'] . '</div>';
		log_msg(__FILE__ . ':' . __LINE__ . ' Countries PDO query exception. Info = {' . json_encode($statement_countries -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
	}
	
?>