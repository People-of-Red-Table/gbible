function get_bible_titles()
{
	$.getJSON(
		"./ajax/get_bible_titles.json.php?language=" + document.getElementById('language-selection').value,
		function (data)
		{
			$('#bible-selection').find('option').remove();
			var items = [];
			$.each (data, function (num, row)
			{
				$('#bible-selection').
				append('<option value="' + row['b_code'] + '">' 
					+ row['title'] + '</option>');
			});
		}
	);
}