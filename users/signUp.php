<h1><?=$text['sign_up'];?></h1>
<br />
<form method="post">
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_nickname'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="nickname">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['full_name'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="full_name">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_timezone'];?></p>
		</div>
		<div class="col-md-2">
			<select name="timezone">
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
	</div>	
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_email'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="email">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_password'];?></p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password" maxlength="16">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['repeat_password'];?></p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password_repeat" maxlength="16">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['secret_question'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="secret_question">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p><?=$text['secret_answer'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="secret_answer">
		</div>
	</div>

	<?php
		$cmp_country = $hal_country;
		$cmp_language = $hal_language;
		require 'countryAndLanguageFields.php';
	?>
	<br />
	<div class="row">
		<div class="col-md-12">
			<input type="hidden" name="menu" value="users_registration">
			<!-- TO DO: check email by pattern-->
			<input type="submit" name="submit" value="<?=$text['sign_up'];?>" />
		</div>
	</div>


</form>