function get_books()
{
	$.getJSON(
		"./ajax/get_books.json.php?b_code=" + document.getElementById('bible-selection').value,
		function (data)
		{
			//alert(JSON.stringify( data));
			//$('.gb-pagination').find('ul').remove();
			$('#openBookDialog').find('.modal-body').find('nav').remove();
			$('#openBookDialog').find('.modal-body').append('<nav></nav>');
			counter = 1;
			$.each(data, function(num, row)
				{
					$('#openBookDialog').find('.modal-body').find('nav').append(
						'<a href="#top-anchor" data-dismiss="modal" onclick="chapter_index=1;book_index=' + counter + '; get_book_info(); get_bible_verses();">' + 
						row['book'] + '</a> ');
					counter++;
				});
		}
	);
}

 
