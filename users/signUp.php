<h1><?=$text['sign_up'];?></h1>
<br />
<form method="post">

	<div class="form-group">
		<label for="signUpNickname"><?=$text['text_nickname'];?></label>
		<input type="text" class="form-control" name="nickname" id="signUpNickname">
	</div>
	<div class="form-group">
			<label for="signUpFullName"><?=$text['full_name'];?></label>
			<input type="text" class="form-control" name="full_name" id="signUpFullName">
	</div>
	<div class="form-group">
			<label for="signUpTimezone"><?=$text['text_timezone'];?></label>
			<select name="timezone" id="signUpTimezone" class="form-control">
				<?php
					$timezones = DateTimezone::listIdentifiers();
					foreach ($timezones as $item) 
					{
						if ($item == 'UTC')
						echo '<option value="' . $item . '" selected>' . $item . '</option>';
					else
						echo '<option value="' . $item . '">' . $item . '</option>';
					}
				?>
			</select>
	</div>	
	<div class="form-group">
			<label for="signUpEMail"><?=$text['text_email'];?></label>
			<input type="email" class="form-control" name="email" id="signUpEMail">
	</div>
	<div class="form-group">
			<label for="signUpPassword"><?=$text['text_password'];?></label>
			<input type="password" class="form-control" name="password" maxlength="16" id="signUpPassword">
	</div>
	<div class="form-group">
			<label for="signUpRepeatPassword"><?=$text['repeat_password'];?></label>
			<input type="password" class="form-control" name="password_repeat" maxlength="16" id="signUpRepeatPassword">
	</div>

	<div class="form-group">
			<!-- for next lang-pack add title="" -->
			<label for="signUpSecretQuestion"><?=$text['secret_question'];?></label>
			<input type="text" class="form-control" name="secret_question" id="signUpSecretQuestion">
	</div>

	<div class="form-group">
			<label for="signUpSecretAnswer"><?=$text['secret_answer'];?></label>
			<input type="text" class="form-control" name="secret_answer" id="signUpSecretAnswer">
	</div>

	<?php
		$cmp_country = $hal_country;
		$cmp_language = $hal_language;
		require 'countryAndLanguageFields.php';
	?>

	<!--<div class="form-group">
			<label for="signUpCaptcha"><?=$text['type_captcha'];?></label>
			<iframe src="./lib/get_captcha.php" border="none" width="155" height="55" name="signUpCaptchaFrame"></iframe>
			<a href="./lib/get_captcha.php" target="signUpCaptchaFrame"><span class="glyphicon glyphicon-refresh"></span></a>
			<input type="text" class="form-control" name="user_captcha" id="signUpCaptcha">
	</div>	-->

	<input type="hidden" name="menu" value="users_registration">
	<!-- TO DO: check email by pattern-->
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['sign_up'];?>" />


</form>