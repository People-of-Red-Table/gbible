<?php
function get_easter_datetime($year) {
    $base = new DateTime("$year-03-21");
    $days = easter_days($year);

    return $base->add(new DateInterval("P{$days}D"));
}

foreach (range(2015, 2017) as $year) 
{
    printf("Easter in %d is on %s\n<br />",
           $year,
           get_easter_datetime($year)->format('F j'));
}
?>