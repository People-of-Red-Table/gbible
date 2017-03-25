<?php

	echo '<br/>
	<form method="post">
		<input type="hidden" name="menu" value="timetable" />
		<input type="hidden" name="action" value="schedule_parallel_bibles" />
		<div class="form-group">
			<label for="timetableTable">' . $text['text_title'] . '</label>
			<input type="text" name="timetable_title" class="form-control" value="' . $tt_userBibleA -> title . ' ' . $text['text_and'] . ' ' . $tt_userBibleB -> title . '" id="timetableTitle" placeholder="' . $text['text_title'] . '">
		</div>
		<div class="form-group">
			<label for="fromDateTimetable">' . $text['from_date'] . '</label>
			<input type="date" name="from_date" class="form-control" value="' . $date -> format('Y-m-d') . '" id="fromDateTimetable">
		</div>
		';
		foreach ($week as $day_of_week) 
		{
			$chapters_amount = 1;
			if ((strcasecmp('saturday', $day_of_week) === 0)
				or (strcasecmp('sunday', $day_of_week) === 0))
				$chapters_amount = 5;
			echo '<div class="form-group">
				<label for="' . $day_of_week . '_group">' . $text['chapters_in'] . $text['text_' . $day_of_week] . '</label>
				<div class="input-group" id="' . $day_of_week . '_group">
					<span class="input-group-addon">
					<input type="checkbox" name="day_of_week[' . $day_of_week . ']" checked>
					</span>
					<input type="text" class="form-control" name="chapters_in[' . $day_of_week . ']" value="' . $chapters_amount . '" />
				</div>
			</div>';	
		}
		
		echo '<input type="submit" class="btn btn-default form-control" name="submit" value="' . $text['create_own_timetable'] . '" />
	</form>';
?>