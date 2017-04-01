<?php
	function html_verse($verse_row)
	{
		global $pdo;
		global $mysql;
		global $base_url;
		global $text;
		global $menu;

		global $b_code;
		global $book;
		global $chapter;
		global $verse;


		$html_verse = '';

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
			$html_verse .= '<br />';
			$verse_row['verseText'] = str_replace('¶', '', $verse_row['verseText']);
		}
		// fav = glyphicon glyphicon-heart
		$first_words = substr($verse_row['verseText'], 0, 90) ;
		if (strlen($verse_row['verseText']) > 90) $first_words .= '...';

		$verse_paragraph_title = $text['click_to_share'];
			if ($_SESSION['uid'] > -1)
				$verse_paragraph_title .= $text['add_to_fav_addition'];
			$verse_paragraph_title .= $text['copy_link_to_verse'] . '.';		

		$html_verse .= '<div class="dropdown">
						<p class="dropdown-toggle" title="' . $verse_paragraph_title . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="' . $verse_row['verseID'] . '" align="justify">'; 
		if (strcasecmp($menu, 'search') !== 0)
			$html_verse .= '<sup>' . $verse_row['startVerse'] . '</sup> ';

		$html_verse .= $b_start . $verse_row['verseText'] . $b_end . '</p><ul class="dropdown-menu" aria-labelledby="' . $verse_row['verseID'] . '">';

		if ($_SESSION['uid'] > - 1)
			 $html_verse .= '<li><a href="./?menu=users_addVerseToFavorites&b_code=' . $b_code . '&id=' . $verse_row['verseID'] . '" target="_blank"><span class="glyphicon glyphicon-heart"></span> Add To Favorites</a></li>';

		$url = $base_url . '?b_code=' . $b_code . '&book=' . $verse_row['book'] . '&chapter=' . $chapter . '&verse=' . $verse_row['startVerse'] . '#' . $verse_row['verseID'];

		// Facebook Share
		$html_verse .= '<li><div class="fb-share-button" data-href="%SHARE_URL%" data-layout="button" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '&src=sdkpreparse">Share</a></div></li>';

		// Facebook Like

		$html_verse .= '<li><div class="fb-like" data-href="' . $url . '" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div></li>';

		// VK Share Link

		$html_verse .= '<li><a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank"><span class="glyphicon glyphicon-share"></span> ' . $text['share_in_vk'] . '</a></li>';

		// VK Share https://vk.com/editapp?act=create
		/*$html_verse .= '<a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank">Share in VK</a><br />';
		*/
		// VK Like https://vk.com/editapp?act=create
		/*$html_verse .= '<div id="vk_like_' . $verse_row['verseID'] . '"></div>
					<script type="text/javascript">
					VK.init({apiId: 111, onlyWidgets: true});
					 VK.Widgets.Like(\'vk_like_' . $verse_row['verseID'] . '\', {pageUrl: \'' . $url .'\', pageTitle: \'' . $bible_title . ' ' . $verse_row['book'] . ' ' . $chapter . ':' . $verse_row['startVerse'] . '\'}, \'' . $b_code . $verse_row['verseID'] . '\');</script>';
		*/

		// Twitter 
		$html_verse .= '<li><a href="./?menu=tweetVerse&id=' . $verse_row['verseID'] . '&b_code=' . $b_code . '&book=' . $verse_row['book'] . '&chapter=' . $chapter . '&verseNumber=' . $verse_row['startVerse'] . '&first_words=' . $first_words . '" target="_blank"><span class="glyphicon glyphicon-comment"></span> ' . $text['text_tweet'] . '</a></li>';



		$html_verse .= '<li><a onclick="clipboard.copy(window.location.origin + window.location.pathname + \'?b_code=' 
			. $b_code . '&book=' . $verse_row['book'] . '&chapter=' . $chapter . 
			'&verse=' . $verse_row['startVerse'] . '#'. $verse_row['verseID'] . '\')"><span class="glyphicon glyphicon-copy"></span> ' . $text['copy_link_to_the_verse'] . '</a></li>'
			. '</ul></div>' . PHP_EOL;

		return $html_verse;
	}
?>