<?php

	$today = new DateTime();
    $base = new DateTime($today -> format('Y') . '-03-21');
    $days = easter_days($today -> format('Y'));
	$base->add(new DateInterval("P{$days}D"));

    printf("Easter is on %s\n<br />",
           $base->format('F j'));
?>