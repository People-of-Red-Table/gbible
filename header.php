<!doctype html>
<html lang="">
	<head>
		<title>Golden Bible</title>
		<link rel="shortcut icon" href="./favicon.ico" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="charset" content="utf-8">
		<?php
			if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
			{
		?>
		<!-- <prog-server> -->
		<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="./bootstrap/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="./jquery/jquery-ui.theme.min.css">
		<script src="./jquery/jquery.min.js"></script>
		<script src="./jquery/jquery-ui.min.js"></script>
		<script src="./bootstrap/js/bootstrap.min.js"></script>
		<!-- </prog-server> -->		
		<?php
			}
			else
			{
		?>
		<!-- <for-web-server> -->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
		<!-- Latest compiled and minified JavaScript -->
		<link rel="stylesheet" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
		<!-- </for-web-server>	-->	
		<?php
			}
		?>
		<script src="./external/clipboard.min.js"></script>
		<script type="text/javascript" src="./js/lib.js"></script>
		<link rel="stylesheet" href="./style.css">
	</head>
	<body>
	<!-- Facebook Share and Like-->
	<div id="fb-root"></div>
<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js =  d.createElement(s); js.id = id; js.src = "//connect.facebook.net/<?=$fb_language_country;?>/sdk.js#xfbml=1&version=v2.8"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>

		<nav class="nav navbar-inverse">
			<div class="container-fluid">

				<div class="navbar-header">
					<a href="#" class="navbar-brand">Golden Bible</a>
				</div>

				<div class="navbar" id="nav">
					<ul class="nav navbar-nav">
						<li><a href="./?menu=bible">Open</a></li>
						<li><a href="./?menu=topVerses">Top Verses</a></li>

					<?php
						if ($_SESSION['uid'] > -1)
						{
					?>
						<li><a href="./?menu=users_myFavoriteVerses">Favorite Verses</a></li>
					<?php
						}
					?>

						<li><a href="./?menu=charityLinks">Charity Organizations of the World</a></li>

						<!--<li><a href="#" data-toggle="modal" data-target="#bibleSelectionDialog">Open</a></li>-->
						<!--<li>
							<a href="#">Top</a>
						</li>
						<li><a href="#">Bookshelf</a></li>
						<li><a href="http://charity-port.16mb.com/" target="_blank">Community</a></li>-->
					</ul>

					<!--<ul class="nav navbar-nav navbar-right">
						<li><a href="#">Sign In</a></li>
					</ul>-->

					<ul class="nav navbar-nav navbar-right">
						<!--<li><a href="#">Contact Us</a></li>-->

					<?php
						if ($_SESSION['uid'] > -1)
						{
					?>
						<li><a href="./?menu=users_settings">Settings</a></li>
						<li><a href="./?menu=sign_out">Sign Out</a></li>
						<li><a href="#"><b><?=$_SESSION['nickname'];?></b></a></li>
					<?php
						}
						else 
						{
					?>
						<li><a href="./?menu=users_signUp">Sign Up</a></li>
						<li><a href="./?menu=users_signIn">Sign In</a></li>
					<?php
						}
					?>

					</ul>
				</div>

			</div>
		</nav>
		<!--
		<li><a href="https://twitter.com/goldenbible_org" target="_blank">Twitter @GoldenBible_org</a></li>
						<li><a href="https://twitter.com/ihaveabiblesite" target="_blank">Twitter @ihaveabiblesite</a></li>
						<li><a href="https://plus.google.com/115380489639432555966" target="_blank">Google+</a></li>
						<li><a href="https://www.youtube.com/channel/UCCWrFOJPlLyW85xf40afNJg" target="_blank">YouTube</a></li>
		-->

		<br />
		<div class="container" id='top-anchor'>
		<?php
			$charity_links = '<div class="row">';

			$charity_links .= '<div class="col-md-2"><a href="http://redcross.org/" target="_blank">American Red Cross</a></div>';
			
			foreach ($charity as $item) 
			{
				if(strcasecmp($item['Code'], $hal_language_country) === 0)
				{
					if ($item['CharityType'] == 'Charity')
						$text = 'Charity for ';
					else $text = $item['CharityType'] . ' of ';
					$charity_links .= '<div class="col-md-2"><a href="' . $item['CharityLink'] . '" target="_blank">' . $text . $item['Country'] . '</a></div>';
				}
			}

			$charity_links .=  '<div class="col-md-2"><a href="http://www.evansnyc.com/charity/" target="_blank">Charity for Russia</a></div>';

			$charity_links .= '<div class="col-md-2"><a href="http://cvbsp.org.br/redcross/index.php?p=pages/home" target="_blank">Brazilian Red Cross</a></div>';

			$charity_links .= '<div class="col-md-2"><a href="http://redcross.org.uk/" target="_blank">British Red Cross</a></div>';
	
			$charity_links .= '<div class="col-md-2"><a href="http://icrc.org/" target="_blank">Intern. Committee of Red Cross</a></div>';

			$charity_links .= '</div>';
			
			echo $charity_links . '<br />';
		?>

		<div class="row">
			<div class="col-md-12">
				<center><a href="http://www.israelgives.org/" target="_blank">Israel Gives</a></center>
			</div>
		</div><br />
	</div>

