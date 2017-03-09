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
				if (row['startVerse'] == verse_index)
				{
					$('#bible-verses').
				append('<p><sup>' + row['startVerse'] + '</sup> <b>' 
					+ row['verseText'] + '</b></p>');
					verse_index = 0;
				}
				else
				$('#bible-verses').
				append('<p onclick="clipboard.copy(window.location.origin + window.location.pathname + \'?b_code=' 
					+ b_code + '&book=' + book_short_title + '&chapter=' + chapter_index + 
					'&verse=' + row['startVerse'] + '\')"><sup>' + row['startVerse'] + '</sup> ' 
					+ row['verseText'] + '</p>');
			});
			$('#chapterNumber').text(chapter_index);
			get_chapters();
		}
	);	
}