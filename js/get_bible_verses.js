function get_bible_verses()
{
	$.getJSON(
		"./ajax/get_bible_verses.json.php?b_code=" + document.getElementById('bible-selection').value +
			"&book_index=" + book_index + "&chapter=" + chapter_index,
		function (data)
		{
			$('#bible-verses').remove();
			$('.panel-body').append('<div id="bible-verses"></div>');

			$.each (data, function (num, row)
			{
				if (row['verseText'].indexOf('¶') != -1)
				{ 
					$('#bible-verses').append('<br />');
					row['verseText'] = row['verseText'].replace('¶', '');
				}
				$('#bible-verses').
				append('<p><sup>' + row['startVerse'] + '</sup> ' 
					+ row['verseText'] + '</p>');
			});
			$('#chapterNumber').text(chapter_index);
			get_chapters();
		}
	);	
}