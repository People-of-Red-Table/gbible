function get_chapters()
{
	$.getJSON(
		"./ajax/get_chapters.json.php?b_code=" + document.getElementById('bible-selection').value
		+ "&book_index=" + book_index,
		function (data)
		{
			//alert(JSON.stringify( data));
			//$('.gb-pagination').find('ul').remove();
			$('#openChapterDialog').find('.modal-body').find('nav').remove();
			$('#openChapterDialog').find('.modal-body').append('<nav></nav>');
			counter = 1;
			$.each(data, function(num, row)
				{
					if (chapter_index == counter)
						$('#openChapterDialog').find('.modal-body').find('nav').append(
						'<b>' + 
						row['chapter'] + '</b> ');
					else $('#openChapterDialog').find('.modal-body').find('nav').append(
						'<a href="#top-anchor" onclick="chapter_index=' + counter + '; get_bible_verses();">' + 
						row['chapter'] + '</a> ');
					counter++;
				});
			$('.gb-pagination').html($('#openChapterDialog').find('.modal-body').find('nav').html());

			var chapter_nav = '<table width="100%"><tr><td width="50%">';
			if(chapter_index > 1)
				chapter_nav += '<a href="#top-anchor" onclick="chapter_index--; get_bible_verses();">Previous Chapter</a>';
			chapter_nav += '</td><td width="50%" align="right">';
			if (chapter_index < (counter-1))
				chapter_nav += '<a href="#top-anchor" onclick="chapter_index++; get_bible_verses();">Next Chapter</a>';
			chapter_nav += '</td></tr></table>';
			$('.gb-chapter-nav').html('');
			$('.gb-chapter-nav').html(chapter_nav);
		}
	);
}

 
