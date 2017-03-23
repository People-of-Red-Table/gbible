
<!-- <content> -->
<div class="container" id="content">

<?php

	$menu_items = ['bible', 

		// User "Sign Up", "Sign In"
		'users_signUp', 'users_registration', 'users_signIn', 'users_signingIn',  'users_settings', 

		'users_saveSettings', 

		// Password reset
		'users_resetPassword', 'users_passwordResetting', 'users_secretAnswerChecking', 
		'users_resetPasswordByEMail',

		// Favorites
		'users_addVerseToFavorites',
		'users_myFavoriteVerses',
		'topVerses',

		// tweet
		'tweetVerse',

		// Charity
		'charityLinks', 'charityOrganizationsOf',

		'feedback', 'changeLanguage', 'history', 'thankYouNotes',

		'search', 'biblesByCountries', 'parallelBibles',

		'timetable'
 
		];

	if(in_array($menu, $menu_items))
	{
		$menu = str_replace('_', '/', $menu);
		require "./$menu.php";
	}
	else
		require 'bible.php';

?>
</div>
<br />
<!-- </content> -->