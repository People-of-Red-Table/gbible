<?php
	require 'bible_nav.php';

	// just interesting random =] 1 to 1000 that you will get 'Hallelujah!' text =]
	$random = rand(1, 1000);
	if ($random === 1000)
		echo '<p class="alert alert-success">' . $text['hallelujah'] . '</p>';

	$statement_translation = $links['sofia']['pdo'] -> prepare(
			'select bh.b_code, bh.table_name, bh.title, bh.description, bh.copyright, 
			bh.license, l.link, bh.http_link, country, language, dialect
			from b_shelf bh 
			join licenses l on l.license = bh.license
			where b_code = :b_code'
		);


	$result_translation = $statement_translation -> execute(array('b_code' => $b_code));
	if(!$result_translation)
		log_msg(__FILE__ . ':' . __LINE__ . ' PDO translations query exception. Info = {' . json_encode($statement_translation -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}' );
	$info_row = $statement_translation -> fetch();
	$bible_title = $info_row['title'];
	$bible_description = $info_row['description'];

	if (stripos($b_code, 'http') === FALSE)
	{
?>
<form method="post">
	<input type="hidden" name="menu" value="search" />
	<input type="hidden" name="search_in" value="<?=$b_code;?>" />
	<div class="input-group input-group-sm">
		<input type="text" class="form-control" name="search_query" placeholder="<?=$text['search_in'].$info_row['title'];?>" />
		<span class="input-group-btn"><input type="submit" class="btn btn-default" name="submit" value="<?=$text['text_search'];?>"></span>
	</div>
</form>
<br />
<?php
	}
?>
	<div class="panel panel-primary">
		<div class="panel-header">
		<?php

		if (stripos($b_code, '_http') === FALSE)
		{
			$table_name = $info_row['table_name'];

			$statement_books = $links['sofia']['pdo'] -> prepare('
								select distinct book from ' . $table_name
							);
			$books_result = $statement_books -> execute();
			$books_rows = $statement_books -> fetchAll();
			$books_nav = '<div width="80%" align="center">';
			foreach ($books_rows as $row) 
			{
				$books_nav .=  '<a href="./?b_code=' . $b_code . '&book=' . $row['book'] . '">' . $row['book'] . '</a> ';
			}
				$books_nav .= '</div>';
		?>
			<div><center><h2 id="bibleTitle" title="<?=$bible_description;?>"><?=$bible_title;?></h2></center></div>
			<nav class="gb-books-nav"><?=$books_nav;?></nav>
		<?php
			if (!isset($book))
				$book = $books_rows[0]['book'];

			if (!isset($chapter))
				$chapter = 1;

			$statement_chapters = $links['sofia']['pdo'] -> prepare (
									'select distinct chapter from ' . $table_name 
									.' where book = :book'
								);
				$result_chapters = $statement_chapters -> execute(array('book' => $book));

				if(!$result_chapters)
					log_msg(__FILE__ . ':' . __LINE__ . ' PDO chapters query exception. Info = {' . json_encode($statement_chapters -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}, \$table_name = `' . $table_name . '`.' );

				$chapters_rows = $statement_chapters -> fetchAll();
				$chapters_links = '<div width="80%" align="center">';
				$chapter_count=0;
				foreach ($chapters_rows as $chapter_row) 
				{
					$chapter_count++;
					$chapters_links .= '<a href="./?b_code=' . $b_code . '&book=' . $book . '&chapter=' . $chapter_row['chapter'] . '">[' . $chapter_row['chapter'] . ']</a> ';
				}
				$chapters_links .= '</div>';

				$chapter_nav = '<table width="100%"><tr><td width="50%">';
				if ($chapter > 1)
					$chapter_nav .= '<a href="./?b_code=' . $b_code . '&book=' . $book . '&chapter=' . ($chapter - 1) . '#top-anchor"><button class="btn btn-default">Previous Chapter</button></a>';
				$chapter_nav .= '</td><td width="50%" align="right">';
				if ($chapter < $chapter_count)
					$chapter_nav .= '<a href="./?b_code=' . $b_code . '&book=' . $book . '&chapter=' . ($chapter + 1) . '#top-anchor"><button class="btn btn-default">Next Chapter</button></a>';
				$chapter_nav .= '</td></tr></table>'; 

		?>
			<div id="book-title"><center><h3><?=$book;?> <?=$chapter;?></h3></center></div>
			<nav class="gb-pagination"><?=$chapters_links;?></nav><br />
			<nav class="gb-chapter-nav"><?=$chapter_nav;?></nav>

		</div>
		<?php

			if (stripos($b_code, '_http') === FALSE)
			{
				$verse_paragraph_title = $text['click_to_share'];
				if ($_SESSION['uid'] > -1)
					$verse_paragraph_title .= $text['add_to_fav_addition'];
				$verse_paragraph_title .= $text['copy_link_to_verse'] . '.';
			}

			$statement_verses = $links['sofia']['pdo'] -> prepare (
						'select verseID, startVerse, verseText from ' . $table_name .
						' where book = :book and chapter = :chapter'
				);
			$result_verses = $statement_verses -> execute(array('book' => $book, 'chapter' => $chapter));

			if(!$result_verses)
				log_msg(__FILE__ . ':' . __LINE__ . ' ' . ' PDO verses query exception. Info = {' . json_encode($statement_verses -> errorInfo())  . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}, \$table_name = `' . $table_name . '`.');

			$verses_rows = $statement_verses -> fetchAll();
			$verses = '';
			$b_start = '';
			$b_end = '';
			foreach ($verses_rows as $verse_row) 
			{
				if ($verse_row['startVerse'] == $verse)
				{
					$b_start = '<b>';
					$b_end = '</b>';
				}
				else
				{
					$b_start = '';
					$b_end = '';
				}
				if (strpos($verse_row['verseText'], '¶') !== FALSE)
				{
					$verses .= '<br />';
					$verse_row['verseText'] = str_replace('¶', '', $verse_row['verseText']);
				}
				// fav = glyphicon glyphicon-heart
				$first_words = substr($verse_row['verseText'], 0, 90) ;
				if (strlen($verse_row['verseText']) > 90) $first_words .= '...';

				$verses .= '<div class="dropdown"><p class="dropdown-toggle" title="' . $verse_paragraph_title . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="' . $verse_row['verseID'] . '"><sup>' . $verse_row['startVerse'] . '</sup> ' . $b_start . $verse_row['verseText'] . $b_end . '</p><ul class="dropdown-menu" aria-labelledby="' . $verse_row['verseID'] . '">';

					if ($_SESSION['uid'] > - 1)
						 $verses .= '<li><a href="./?menu=users_addVerseToFavorites&b_code=' . $b_code . '&id=' . $verse_row['verseID'] . '" target="_blank"><span class="glyphicon glyphicon-heart"></span> Add To Favorites</a></li>';

						$array = explode('/', $_SERVER['SCRIPT_NAME']);
						if (count($array) > 1)
							$addition_url = '/' . $array[0];
						$url = 'http://' . $_SERVER['HTTP_HOST'] . $addition_url . '/?b_code=' . $b_code . '&book=' . $book . '&chapter=' . $chapter . '&verse=' . $verse_row['startVerse'] . '#' . $verse_row['verseID'];

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
						$verses .= '<li><a href="./?menu=tweetVerse&id=' . $verse_row['verseID'] . '&b_code=' . $b_code . '&book=' . $book . '&chapter=' . $chapter . '&verseNumber=' . $verse_row['startVerse'] . '&first_words=' . $first_words . '" target="_blank"><span class="glyphicon glyphicon-comment"></span> ' . $text['text_tweet'] . '</a></li>';

	

						$verses .= '<li><a onclick="clipboard.copy(window.location.origin + window.location.pathname + \'?b_code=' 
					. $b_code . '&book=' . $book . '&chapter=' . $chapter . 
					'&verse=' . $verse_row['startVerse'] . '#'. $verse_row['verseID'] . '\')"><span class="glyphicon glyphicon-copy"></span> ' . $text['copy_link_to_the_verse'] . '</a></li>'
						. '</ul></div>' . PHP_EOL;
			}
		?>
		<div class="panel-body"><?=$verses;?></div>

	<nav class="gb-chapter-nav"><?=$chapter_nav;?></nav><br />
	<nav class="gb-pagination"><?=$chapters_links;?></nav><br />
	<nav class="gb-books-nav"><?=$books_nav;?></nav>

	<?php
		}
		else
		{
	?>	
		<script type="text/javascript">$(document).ready(function (){resize();});</script>
		<div class="panel-body"><center><iframe src="<?=$info_row['http_link'];?>" width="80%" id="BibleFrame" name="BibleFrame" onresize="alert('resize');resize();"></iframe></center></div>
	<?php
		}
	?>


		<div class="panel-footer">
			<center><h5><b><?=$info_row['title'];?></b></h5><br /><?=$info_row['copyright'];?><br /><?=$text['published_under'];?> <a href="<?=$info_row['link'];?>" target="_blank"><?=$info_row['license'];?></a></center>
		</div>
	</div>
	<?php
		if (stripos($b_code, '_http') === FALSE)
		{	
			echo '<p style="font-size: 0.75em">' . $verse_paragraph_title . '</p>';
		}
	?>