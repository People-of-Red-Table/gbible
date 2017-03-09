function get_book_info()
{
	$.getJSON(
		"./ajax/get_book_info.json.php?book_index=" + book_index +
			'&b_code=' + document.getElementById('bible-selection').value,
		function (data)
		{
			$('#book-title').find('center').remove();
			book_short_title = data['book'];
			$('#book-title').append('<center><h4><a href="#" data-toggle="modal" ' + 
				'data-target="#openBookDialog">' + data['book'] 
				+ '</a> <span id="chapterNumber">' + chapter_index + '</span></h4></center>');
			$('#openBookDialog').modal('hide');
		}
	);	
}