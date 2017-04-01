<h1><?=$text['parallel_bibles'];?></h1>
<br />
<?php
	require 'parallelBiblesNav.php';

	// just interesting random =] 1 to 1000 that you will get 'Hallelujah!' text =]
	$random = rand(1, 1000);
	if ($random === 1000)
		echo '<p class="alert alert-success">' . $text['hallelujah'] . '</p>';

	$statement_translation = $links['sofia']['pdo'] -> prepare(
			'select bh.b_code, bh.table_name, bh.title, bh.description, bh.copyright, 
			bh.license, l.link, bh.http_link, country, language, dialect
			from b_shelf bh 
			join licenses l on l.license = bh.license
			where b_code in (:b_code1, :b_code2) '
		);


	$result_translation = $statement_translation -> execute(['b_code1' => $b_code1, 'b_code2' => $b_code2]);
	if(!$result_translation)
		log_msg(__FILE__ . ':' . __LINE__ . ' PDO translations query exception. Info = {' . json_encode($statement_translation -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}' );

	$info_row1 = $statement_translation -> fetch();
	if ($statement_translation -> rowCount() === 1)
		$info_row2 = $info_row1;
	else
		$info_row2 = $statement_translation -> fetch();

	if ($b_code1 !== $info_row1['b_code'])
	{
		$t_row = $info_row1;
		$info_row1 = $info_row2;
		$info_row2 = $t_row;
	}

	$bible_title1 = $info_row1['title'];
	$bible_description1 = $info_row1['description'];
	$bible_title2 = $info_row2['title'];
	$bible_description2 = $info_row2['description'];

	$table_name1 = $info_row1['table_name'];
	$table_name2 = $info_row2['table_name'];
?>
	<br/>
	<div class="panel panel-primary">
		<div>
		<?php

		if (stripos($b_code1, 'http') === FALSE and stripos($b_code2, 'http') === FALSE)
		{
			$books = [];
			$statement_books = $links['sofia']['pdo'] -> prepare('
								select distinct t.book, case when bt.shorttitle is not null then bt.shorttitle else t.book end `shorttitle` from ' . $table_name1 . ' t 
								left join book_titles bt on t.book = bt.book and language_code = "' . $interface_language . '"
								where t.book in (select distinct book from ' . $table_name2 . ')'
							);

			$books_result = $statement_books -> execute();
			$books_rows = $statement_books -> fetchAll();
			$books_nav = '<div width="80%" align="center">';
			$books_form = '<form method="post" name="bookSelectionFormFromChosenBibles">
							<select name="book" class="form-control" onchange="bookSelectionFormFromChosenBibles.submit()">
							'
							;

			$book_title = $book;
			foreach ($books_rows as $row) 
			{
				$books[] = $row['book'];
				$books_nav .=  '<a href="./?menu=parallelBibles&book=' . $row['book'] . '">' . $row['shorttitle'] . '</a> ';
				$selected = '';
				if ($book === $row['book'])
				{
					$selected = ' selected="selected"';
					$book_title = $row['shorttitle'];
				}
				$books_form .= '<option value="' . $row['book'] . '"' . $selected . '>' . $row['shorttitle'] . '</option>';
			}
				$books_form .= '</select></form>';
				$books_nav .= '</div>';

		?>
			<br/>
			<div>
				<div class="row">
					<div class="col-md-6">
						<center><?=$text['bible_a'];?>: <h2 id="bibleTitle1" title="<?=$bible_description2;?>"><?=$bible_title1;?></h2></center>
					</div>
					<div class="col-md-6">
					<center><?=$text['bible_b'];?>: <h2 id="bibleTitle2" title="<?=$bible_description2;?>"><?=$bible_title2;?></h2></center>
					</div>
				</div>
			</div>
			<nav class="gb-books-nav hidden-print"><?=$books_nav.'<br/>'.$books_form;?></nav>
		<?php
			if (!isset($book) or !in_array($book, $books))
				$book = $books_rows[0]['book'];

			if (!isset($chapter))
				$chapter = 1;

			$statement_chapters = $links['sofia']['pdo'] -> prepare (
									'select distinct chapter from ' . $table_name1 
									.' where book = :book
									and chapter in (select distinct chapter from ' . $table_name2
									.' where book = :book)'
								);
				$result_chapters = $statement_chapters -> execute(array('book' => $book));

				if(!$result_chapters)
					log_msg(__FILE__ . ':' . __LINE__ . ' PDO chapters query exception. Info = {' . json_encode($statement_chapters -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}, \$table_name1 = `' . $table_name1 . '`, \$table_name2 = `' . $table_name2 . '`.' );

				$chapters_rows = $statement_chapters -> fetchAll();
				$chapters_links = '<div width="80%" align="center">';
				$chapter_count=0;

				$chapters_form = '<form method="post" name="chapterSelectionFormFromChosenBibles">
							<select name="chapter" class="form-control" onchange="chapterSelectionFormFromChosenBibles.submit()">
							'
							;

				foreach ($chapters_rows as $chapter_row) 
				{
					$chapter_count++;
					$chapters_links .= '<a href="./?menu=parallelBibles&book=' . $book . '&chapter=' . $chapter_row['chapter'] . '">[' . $chapter_row['chapter'] . ']</a> ';
					$selected = '';
					if ($chapter == $chapter_row['chapter'])
						$selected = ' selected="selected"';
					$chapters_form .= '<option value="' . $chapter_row['chapter'] . '"' . $selected . '>' . $chapter_row['chapter'] . '</option>';
				}
				$chapters_links .= '</div>';
				$chapters_form .= '</select></form>';

				$chapter_nav = '<table width="100%"><tr><td width="50%">';
				if ($chapter > 1)
					$chapter_nav .= '<a href="./?menu=parallelBibles&book=' . $book . '&chapter=' . ($chapter - 1) . '#top-anchor"><button class="btn btn-default">' . $text['previous_chapter'] . '</button></a>';
				$chapter_nav .= '</td><td width="50%" align="right">';
				if ($chapter < $chapter_count)
					$chapter_nav .= '<a href="./?menu=parallelBibles&book=' . $book . '&chapter=' . ($chapter + 1) . '#top-anchor"><button class="btn btn-default">' . $text['next_chapter'] . '</button></a>';
				$chapter_nav .= '</td></tr></table>'; 

		?>
			<div id="book-title"><center><h3><?=$book_title;?> <?=$chapter;?></h3></center></div>
			<nav class="gb-pagination hidden-print"><?=$chapters_links.'<br/>'.$chapters_form;?></nav><br />
			<nav class="gb-chapter-nav hidden-print"><?=$chapter_nav;?></nav>

		</div>
		<?php
				$verse_paragraph_title = $text['click_to_share'];
				if ($_SESSION['uid'] > -1)
					$verse_paragraph_title .= $text['add_to_fav_addition'];
				$verse_paragraph_title .= $text['copy_link_to_verse'] . '.';

				$query1 = 'select verseID, startVerse, verseText from ' . $table_name1 
						.' where  book = :book and chapter = :chapter '
						.' and startVerse in (select startVerse from ' . $table_name2 . ' where book = :book and chapter = :chapter)';

				$query2 = 'select verseID, startVerse, verseText from ' . $table_name2 
						.' where  book = :book and chapter = :chapter '
						.' and startVerse in (select startVerse from ' . $table_name1 . ' where book = :book and chapter = :chapter)';						

			$statement_verses1 = $links['sofia']['pdo'] -> prepare ($query1);
			$statement_verses2 = $links['sofia']['pdo'] -> prepare ($query2);

			$result_verses1 = $statement_verses1 -> execute(array('book' => $book, 'chapter' => $chapter));
			$result_verses2 = $statement_verses2 -> execute(array('book' => $book, 'chapter' => $chapter));

			if(!$result_verses1)
				log_msg(__FILE__ . ':' . __LINE__ . ' ' . ' PDO verses query exception. Info = {' . json_encode($statement_verses1 -> errorInfo())  . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}, \$table_name1 = `' . $table_name1 . '`.');
			if(!$result_verses2)
				log_msg(__FILE__ . ':' . __LINE__ . ' ' . ' PDO verses query exception. Info = {' . json_encode($statement_verses2 -> errorInfo())  . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}, \$table_name2 = `' . $table_name2 . '`.');

			$verses = '';
			while ($verse_row1 = $statement_verses1 -> fetch() 
			and $verse_row2 = $statement_verses2 -> fetch()) 
			{
				$bgcolor = '';
				if ($verse_row1['startVerse'] % 2 == 0)
					$bgcolor = ' style="color: navy"';

				$verses .= '<div class="row"' . $bgcolor . '>';
				if (strpos($verse_row1['verseText'], '¶') !== FALSE)
				{
					$verse_row1['verseText'] = str_replace('¶', '', $verse_row1['verseText']);
				}
				if (strpos($verse_row2['verseText'], '¶') !== FALSE)
				{
					$verse_row2['verseText'] = str_replace('¶', '', $verse_row2['verseText']);
				}

				$first_words1 = substr($verse_row1['verseText'], 0, 90) ;
				if (strlen($verse_row1['verseText']) > 90) $first_words1 .= '...';
				$first_words2 = substr($verse_row2['verseText'], 0, 90) ;
				if (strlen($verse_row2['verseText']) > 90) $first_words2 .= '...';

				// Verse from Bible A

				$verses .= '<div class="col-md-6"><div class="dropdown"><p class="dropdown-toggle" title="' . $verse_paragraph_title . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="' . $verse_row1['verseID'] . '"><sup>' . $verse_row1['startVerse'] . '</sup> ' . $verse_row1['verseText'] . '</p><ul class="dropdown-menu" aria-labelledby="' . $verse_row1['verseID'] . '">';

					if ($_SESSION['uid'] > - 1)
						 $verses .= '<li><a href="./?menu=users_addVerseToFavorites&b_code=' . $b_code1 . '&id=' . $verse_row1['verseID'] . '" target="_blank"><span class="glyphicon glyphicon-heart"></span> Add To Favorites</a></li>';


						$url = $base_url . '?b_code=' . $b_code1 . '&book=' . $book . '&chapter=' . $chapter . '&verse=' . $verse_row1['startVerse'] . '#' . $verse_row1['verseID'];

						// Facebook Share
						$verses .= '<li><div class="fb-share-button" data-href="%SHARE_URL%" data-layout="button" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '&src=sdkpreparse">Share</a></div></li>';

						// Facebook Like

						$verses .= '<li><div class="fb-like" data-href="' . $url . '" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div></li>';

						// VK Share Link

						$verses .= '<li><a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank"><span class="glyphicon glyphicon-share"></span> ' . $text['share_in_vk'] . '</a></li>';

						// VK Share https://vk.com/editapp?act=create
						/*$verses .= '<a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank">Share in VK</a><br />';
						*/
						// VK Like https://vk.com/editapp?act=create
						/*$verses .= '<div id="vk_like_' . $verse_row['verseID'] . '"></div>
									<script type="text/javascript">
									VK.init({apiId: 111, onlyWidgets: true});
									 VK.Widgets.Like(\'vk_like_' . $verse_row['verseID'] . '\', {pageUrl: \'' . $url .'\', pageTitle: \'' . $bible_title . ' ' . $book . ' ' . $chapter . ':' . $verse_row['startVerse'] . '\'}, \'' . $b_code . $verse_row['verseID'] . '\');</script>';
						*/

						// Twitter 
						$verses .= '<li><a href="./?menu=tweetVerse&id=' . $verse_row1['verseID'] . '&b_code=' . $b_code . '&book=' . $book . '&chapter=' . $chapter . '&verseNumber=' . $verse_row1['startVerse'] . '&first_words=' . $first_words1 . '" target="_blank"><span class="glyphicon glyphicon-comment"></span> ' . $text['text_tweet'] . '</a></li>';

	

						$verses .= '<li><a onclick="clipboard.copy(window.location.origin + window.location.pathname + \'?b_code=' 
					. $b_code1 . '&book=' . $book . '&chapter=' . $chapter . 
					'&verse=' . $verse_row1['startVerse'] . '#'. $verse_row1['verseID'] . '\')"><span class="glyphicon glyphicon-copy"></span> ' . $text['copy_link_to_the_verse'] . '</a></li>'
						. '</ul></div>' . PHP_EOL;

						$verses .= '</div>';

				// verse of Bible B

				$verses .= '<div class="col-md-6"><div class="dropdown"><p class="dropdown-toggle" title="' . $verse_paragraph_title . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="' . $verse_row2['verseID'] . '"><sup>' . $verse_row2['startVerse'] . '</sup> ' . $verse_row2['verseText'] . '</p><ul class="dropdown-menu" aria-labelledby="' . $verse_row2['verseID'] . '">';

					if ($_SESSION['uid'] > - 1)
						 $verses .= '<li><a href="./?menu=users_addVerseToFavorites&b_code=' . $b_code2 . '&id=' . $verse_row2['verseID'] . '" target="_blank"><span class="glyphicon glyphicon-heart"></span> Add To Favorites</a></li>';

						$url = $base_url . '?b_code=' . $b_code2 . '&book=' . $book . '&chapter=' . $chapter . '&verse=' . $verse_row2['startVerse'] . '#' . $verse_row2['verseID'];

						// Facebook Share
						$verses .= '<li><div class="fb-share-button" data-href="%SHARE_URL%" data-layout="button" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '&src=sdkpreparse">Share</a></div></li>';

						// Facebook Like

						$verses .= '<li><div class="fb-like" data-href="' . $url . '" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div></li>';

						// VK Share Link

						$verses .= '<li><a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank"><span class="glyphicon glyphicon-share"></span> ' . $text['share_in_vk'] . '</a></li>';

						// VK Share https://vk.com/editapp?act=create
						/*$verses .= '<a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank">Share in VK</a><br />';
						*/
						// VK Like https://vk.com/editapp?act=create
						/*$verses .= '<div id="vk_like_' . $verse_row['verseID'] . '"></div>
									<script type="text/javascript">
									VK.init({apiId: 111, onlyWidgets: true});
									 VK.Widgets.Like(\'vk_like_' . $verse_row['verseID'] . '\', {pageUrl: \'' . $url .'\', pageTitle: \'' . $bible_title . ' ' . $book . ' ' . $chapter . ':' . $verse_row['startVerse'] . '\'}, \'' . $b_code . $verse_row['verseID'] . '\');</script>';
						*/

						// Twitter 
						$verses .= '<li><a href="./?menu=tweetVerse&id=' . $verse_row2['verseID'] . '&b_code=' . $b_code2 . '&book=' . $book . '&chapter=' . $chapter . '&verseNumber=' . $verse_row2['startVerse'] . '&first_words=' . $first_words2 . '" target="_blank"><span class="glyphicon glyphicon-comment"></span> ' . $text['text_tweet'] . '</a></li>';

	

						$verses .= '<li><a onclick="clipboard.copy(window.location.origin + window.location.pathname + \'?b_code=' 
					. $b_code2 . '&book=' . $book . '&chapter=' . $chapter . 
					'&verse=' . $verse_row2['startVerse'] . '#'. $verse_row2['verseID'] . '\')"><span class="glyphicon glyphicon-copy"></span> ' . $text['copy_link_to_the_verse'] . '</a></li>'
						. '</ul></div>' . PHP_EOL;
					$verses .= '</div></div>';
			}
		?>
		<div class="panel-body"><?=$verses;?></div>

	<nav class="gb-chapter-nav hidden-print"><?=$chapter_nav;?></nav><br />
	<nav class="gb-pagination hidden-print"><?=$chapters_links;?></nav><br />
	<nav class="gb-books-nav hidden-print"><?=$books_nav;?></nav>

	<?php
		}
		else
		{
			echo '<p class="alert alert-info">' . $text['choose_parallel_bibles'] . '</p>';
		}
	?>


		<div class="panel-footer">
			<div class="row">
				<div class="col-md-6">
					<?=$text['bible_a'];?>: <h5><b><?=$info_row1['title'];?></b></h5><br /><?=$info_row1['copyright'];?><br /><?=$text['published_under'];?> <a href="<?=$info_row1['link'];?>" target="_blank"><?=$info_row1['license'];?></a>
				</div>
				<div class="col-md-6">
					<?=$text['bible_b'];?>: <h5><b><?=$info_row2['title'];?></b></h5><br /><?=$info_row2['copyright'];?><br /><?=$text['published_under'];?> <a href="<?=$info_row2['link'];?>" target="_blank"><?=$info_row2['license'];?></a>
				</div>
			</div>
		</div>
	</div>
	<?php
		if ((stripos($b_code1, '_http') === FALSE) and (stripos($b_code2, '_http') === FALSE))
		{	
			echo '<p style="font-size: 0.75em">' . $verse_paragraph_title . '</p>';
		}



		if ($_SESSION['uid'] > -1)
		{
			$pb_scheduled = FALSE;


			$statement_timetables = $pdo -> prepare('select id, user_id, b_code, b_code2, scheduled from timetables 
													where user_id = :user_id and scheduled is not null 
													and b_code = :b_code1 and b_code2 = :b_code2');

			$statement_timetables -> execute(['user_id' => $_SESSION['uid'], 'b_code1' => $b_code1, 'b_code2' => $b_code2]);
			$tt_rows = $statement_timetables -> fetchAll();

			foreach ($tt_rows as $tt_row) 
			{
				if ( (strcasecmp($tt_row['b_code'], $b_code1) === 0)
					and (strcasecmp($tt_row['b_code2'], $b_code2) === 0) )
				{
					$pb_scheduled = true;


					$update_query = 'update schedules set `read` = now() where timetable_id = :timetable_id and book = :book and chapter = :chapter';
					$update_statement = $pdo -> prepare($update_query);
					$result = $update_statement -> execute(['timetable_id' => $tt_row['id'], 'book' => $book, 'chapter' => $chapter]);
					$message = check_result($result, $update_statement, $text['tt_reading_update_exception'], __FILE__ . ':' . __LINE__ . ' Update reading exception.');
					
					if (!empty($message))
						echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';
				}
			}

			if (!$pb_scheduled)
				echo '<form method="post" action="./" class="hidden-print">
							<input type="hidden" name="menu" value="timetable">
							<input type="hidden" name="action" value="create_schedule_for_parallel_bibles">
							<input type="hidden" name="tt_b_code1" value="' . $b_code1 . '">
							<input type="hidden" name="tt_b_code2" value="' . $b_code2 . '">
							<input type="submit" class="btn btn-default form-control" name="submit" value="' . $text['to_schedule'] . '"></form>';
			else
				echo '<a class="hidden-print" href="./?menu=timetable"><button class="btn btn-default form-control">' . $text['text_timetable'] . '</button></a>';
		} // </if-not-guest>

		
	?>