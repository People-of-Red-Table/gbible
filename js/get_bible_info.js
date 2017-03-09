function get_bible_info()
{
	b_code = document.getElementById('bible-selection').value;
	$.getJSON(
		"./ajax/get_bible_info.json.php?b_code=" + b_code,
		function (data)
		{
			//alert(JSON.stringify( data[0] ));
			$('#bible-title').find('center').remove();
			$('#bible-title').append('<center><h3 id="bible-title" title="' + data[0]['description'] + '">' + data[0]['title'] + '</h3></center>');

			$('.panel-footer').find('center').remove();
			$('.panel-footer').append('<center>' + data[0]['copyright'] 
						+ '<br />Published under <a href="' + data[0]['license_link'] + '" target="_blank">' + data[0]['license'] + '</a></center>');
		}
	);
	$('#bibleSelectionDialog').modal('hide');
	get_books();
	get_book_info();
}

 
