<h1>Charity Organizations of the World</h1>
<p>Choose a country to look at charity organizations.</p>
<?php

	$query = 'select cnt.name `country`, lower(cnt.code) `country_code`, cont.name `continent` from countries cnt
			join continents cont on cnt.continent = cont.code
			where cnt.code in (select distinct country_code from charity_organizations)
			order by cnt.continent desc, cnt.name asc
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
				echo '<h2>' . $continent . '</h2><br />';
			}
			echo '<a href="./?menu=charityOrganizationsOf&charity_country=' . $country_row['country_code'] . '">' . $country_row['country'] . '</a><br />';
		}
		$counter++;
	}
	else
	{
		echo '<div class="alert alert-danget">We\'ve got issue with charity links for country of the world. Please contact support.</div>';
		log_msg(__FILE__ . ':' . __LINE__ . ' Countries PDO query exception. Info = {' . json_encode($statement_countries -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
	}
	
?>