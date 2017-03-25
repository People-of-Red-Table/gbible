<?php
	$query = 'update bible_for_a_year_schedules set scheduled = null where user_id = :user_id and b_code = :b_code';
	$statement_schedule = $pdo -> prepare($query);
	$result = $statement_schedule -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $_REQUEST['bfy_b_code']]);
	if (!$result)
	{
		$messages[] = ['type' => 'danger', 'message' => $text['scheduling_exception']];
		log_msg(__FILE__ . ':' . __LINE__ . ' Schedule selection exception. Info = ' . json_encode($statement_schedule -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
	}
?>