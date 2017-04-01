<?php
	session_start();

	header('Content-type: image/png');

	$str = '2357wertyuiopasfghjklzxcvnm';
	$_SESSION['captcha_code'] = substr(str_shuffle($str), 0, 5);
	$text = $_SESSION['captcha_code'];

	$font_size = 21;
	$image_width = 150;
	$image_height = 50;

	$image = imagecreate($image_width, $image_height);
	imagecolorallocate($image, 255, 255, 255);
	$text_color = imagecolorallocate($image, 0, 0, 0);

	/*
		// people do not like "bad crosses"
	for ($i=1; $i<=10; $i++)
	imageline($image, 
			rand(0, $image_width), 
			rand(0, $image_height), 
			rand(0, $image_width), 
			rand(0, $image_height), 
		$text_color);
	*/
	$fonts = ['arial', 'ariali', 'arialbi', 'arialbd'];
	$pos = floor($font_size/2);
	for ($i=0; $i < 5; $i++)
	{
		$pos += $font_size;
		$text_color = imagecolorallocate($image, rand(0, 127), rand(0, 127), rand(0, 127));
		imagettftext($image, $font_size, 0, $pos, 35, $text_color, '../fonts/' . $fonts[rand(0, count($fonts) - 1)] . '.ttf', $text[$i]);
	}
	imagepng($image);
?>