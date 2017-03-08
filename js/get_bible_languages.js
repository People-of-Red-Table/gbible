function get_bible_languages()
{
	$.getJSON(
		"./ajax/get_bible_languages.json.php?country=" + document.getElementById('country-selection').value,
		function (data)
		{
			$('#language-selection').find('option').remove();
			$.each (data, function (num, row)
			{
				var selected = '';
				if (language_name.toUpperCase() == row['language_name'].toUpperCase() )
					selected = ' selected';
				$('#language-selection').
				append('<option value="' + row['language_name'] + '"' + selected + '>' 
					+ row['native_language_name'] + '</option>');
			});
			$('#language-selection').val(data[0]['language_name']);
			get_bible_titles();
		});
}