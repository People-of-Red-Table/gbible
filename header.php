<!doctype html>
<html lang="">
	<head>
		<title><?=$text['golden_bible'];?></title>
		<link rel="shortcut icon" href="./favicon.ico" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="charset" content="utf-8">
		<meta name="yandex-verification" content="f03375f944df60ab" />
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

		<nav class="nav navbar-inverse" role="navigation">
			<div class="container-fluid">

				<div class="navbar-header">
					<a href="./" class="navbar-brand navbar-link" title="<?=$text['golden_bible'];?>"><?=$text['golden_bible'];?></a>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-bar-gbible">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="collapse navbar-collapse" id="nav-bar-gbible">
					<ul class="nav navbar-nav">
						<li><a href="./?menu=bible"><?=$text['text_open'];?></a></li>
						<li><a href="./?menu=topVerses"><?=$text['top_verses'];?></a></li>

					<?php
						if ($_SESSION['uid'] > -1)
						{
					?>
						<li><a href="./?menu=users_myFavoriteVerses"><?=$text['favorite_verses'];?></a></li>
					<?php
						}
					?>

						<li><a href="./?menu=charityLinks"><?=$text['charity_of_world'];?></a></li>
						<li><a href="http://charity-port.16mb.com/" title="<?=$text['port_title'];?>" target="_blank"><?=$text['text_community'];?></a></li>

						<!--<li><a href="#" data-toggle="modal" data-target="#bibleSelectionDialog">Open</a></li>-->
						<!--<li>
							<a href="#">Top</a>
						</li>
						<li><a href="#">Bookshelf</a></li>-->
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
						<li><a href="./?menu=users_settings"><?=$text['text_settings'];?></a></li>
						<li><a href="./?menu=sign_out"><?=$text['sign_out'];?></a></li>
						<li><a href="#"><b><?=$_SESSION['nickname'];?></b></a></li>
					<?php
						}
						else 
						{
					?>
						<li><a href="./?menu=changeLanguage"><?=$text['text_languages'];?></a></li>
						<li><a href="./?menu=users_signUp"><?=$text['sign_up'];?></a></li>
						<li><a href="./?menu=users_signIn"><?=$text['sign_in'];?></a></li>
					<?php
						}
					?>

					</ul>
				</div>

			</div>
		</nav>

		<br />
		<div class="container" id='top-anchor'>
		<?php
			$charity_links = '<div class="row">';

			$charity_links .= '<div class="col-md-2"><a href="http://redcross.org/" target="_blank">' . $text['american_red_cross'] . '</a></div>';
			
			$charity_link_found = FALSE;
			foreach ($charity as $item) 
			{
				if(strcasecmp($item['Code'], $hal_language_country) === 0)
				{
					$charity_link_found = true;
					if ($item['CharityType'] == 'Charity')
						$charity_title = 'Charity for ';
					else $charity_title = $item['CharityType'] . ' of ';
					$charity_links .= '<div class="col-md-2"><a href="' . $item['CharityLink'] . '" target="_blank">' . $charity_title . $item['Country'] . '</a></div>';
				}
			}

			if ($charity_link_found === FALSE)
				log_msg(__FILE__ . ':' . __LINE__ . ' Charity link not found for visitor`s country. \$hal_language_country = `' . $hal_language_country . '`.');

			$charity_links .=  '<div class="col-md-2"><a href="http://www.evansnyc.com/charity/" target="_blank">' . $text['charity_for_russia'] . '</a></div>';

			$charity_links .= '<div class="col-md-2"><a href="http://cvbsp.org.br/redcross/index.php?p=pages/home" target="_blank">' . $text['brazilian_red_cross'] . '</a></div>';

			$charity_links .= '<div class="col-md-2"><a href="http://redcross.org.uk/" target="_blank">' . $text['british_red_cross'] . '</a></div>';
	
			$charity_links .= '<div class="col-md-2"><a href="http://icrc.org/" target="_blank">' . $text['text_icrc'] . '</a></div>';

			$charity_links .= '</div>';
			
			echo $charity_links . '<br />';
		
			$statement_country_name = $links['sofia']['pdo'] -> prepare('select native from countries where code = :code');
			$result_country_name = $statement_country_name -> execute(['code' => $charity_country]);
			$country_row = $statement_country_name -> fetch();
		?>
		<div class="row">
			<div class="col-md-12">
				<center><a href="./?menu=charityOrganizationsOf" target="_blank"><?=$text['charity_of_your_country'];?> <?=$country_row['native'];?></a></center>
			</div>
		</div><br />

		<div class="row">
			<div class="col-md-12">
				<center><a href="http://www.israelgives.org/" target="_blank"><?=$text['israel_gives'];?></a></center>
			</div>
		</div><br />
	</div>

