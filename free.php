<?php

	fclose($log_file);
	fclose($slog_file);
	mysqli_close($links['sofia']['mysql']);

?>