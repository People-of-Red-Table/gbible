<?php

	function get_new_id()
	{
		global $pdo;
		global $mysql;
		global $text;

		$str = '2357wrtpsfghjklzxcvnm';
		$str = str_repeat($str, 3);
		$ids_amount_limit = 100;

		$ids_statement = $pdo -> prepare('select count(id) from new_identities');
		$ids_statement -> execute();
		$row_count = $ids_statement -> fetch();
		$ids_count = $row_count[0];

		if ($ids_count < $ids_amount_limit)
			$insert_id_statement = $pdo -> prepare('insert into new_identities (id) values (:renew_id);');

		while ($ids_count <= $ids_amount_limit)
		{
			$result = false;
			while(!$result)
			{
				$renew_id = substr(str_shuffle($str), 0, 32);
				$result = $insert_id_statement -> execute(['renew_id' => $renew_id]);
			}
			$ids_count++;
		}

		$result = mysqli_query($mysql, 'select id from new_identities limit 0, 1');
		$new_id_row = mysqli_fetch_assoc($result);
		$new_id = $new_id_row['id'];

		mysqli_query($mysql, 'delete from new_identities where id = "' . $new_id . '"');
		return $new_id;
	}

?>