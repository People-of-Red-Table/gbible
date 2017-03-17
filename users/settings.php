<?php
	$statement = $links['sofia']['pdo'] -> prepare('select * from users where id = :id');
	$statement -> execute(array('id' => $_SESSION['uid']));
	$user_row = $statement -> fetch();
?>
<h1><?=$text['text_settings'];?></h1>
<br />
<form method="post">
	<div class="form-group">
			<label for="settingsNickname"><?=$text['text_nickname'];?></label>
			<input type="text" class="form-control" name="nickname" value="<?=$user_row['nickname'];?>" id="settingsNickname">
	</div>

	<div class="form-group">
			<label for="settingsFullName"><?=$text['full_name'];?></label>
			<input type="text" class="form-control" name="full_name" value="<?=$user_row['full_name'];?>" id="settingsFullName">
	</div>

	<div class="form-group">
			<label for="settingsTimezone"><?=$text['text_timezone'];?></label>
			<select class="form-control" name="timezone" id="settingsTimezone">
				<?php
					$timezones = DateTimezone::listIdentifiers();
					foreach ($timezones as $item) 
					{
						if ($item == $user_row['timezone'])
							echo '<option value="' . $item . '" selected>' . $item . '</option>';
						else
							echo '<option value="' . $item . '">' . $item . '</option>';
					}
				?>
			</select>
	</div>	
	
	<div class="form-group">
			<label for="settingsEmail"><?=$text['text_email'];?></label>
			<input type="email" class="form-control" name="email" value="<?=$user_row['email'];?>" id="settingsEmail">
	</div>

	<div class="form-group">
			<label for="settingsPassword"><?=$text['text_password'];?></label>
			<input type="password" class="form-control" name="password" maxlength="16"  value="" id="settingsPassword">
	</div>

	<div class="form-group">
			<label for="settingsRepeatPassword"><?=$text['repeat_password'];?></label>
			<input type="password" class="form-control" name="password_repeat" maxlength="16" value="" id="settingsRepeatPassword">
	</div>

	<div class="form-group">
			<label for="settingsCurrentPassword"><?=$text['current_password'];?> <sup>1</sup></label>
			<p style="font-size: 0.75em"><sup>1</sup> - <?=$text['fill_current_password'];?>.</label>
			<input type="password" class="form-control" name="current_password" maxlength="16"  value="" id="settingsCurrentPassword" />
	</div>

	<p class="alert alert-warning"><?=$text['reset_by_email_warning'];?></p>

	<div class="form-group">
			<label for="secretQuestionField"><?=$text['secret_question'];?></label>
			<input type="text" class="form-control" name="secret_question" value="<?=$user_row['secret_question'];?>" id="secretQuestionField">
	</div>

	<div class="form-group">
			<label for="secretAnswerField"><?=$text['secret_answer'];?></label>
			<input type="text" class="form-control" name="secret_answer" value="<?=$user_row['secret_answer'];?>" id="secretAnswerField">
	</div>

	<?php
		$cmp_country = $user_row['country'];
		$cmp_language = $user_row['language'];
		require 'countryAndLanguageFields.php'; 
	?>
	<br />
	<input type="hidden" name="menu" value="users_saveSettings">
	<!-- TO DO: check email by pattern-->
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['text_save'];?>" />


</form>