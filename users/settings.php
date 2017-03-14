<?php
	$statement = $links['sofia']['pdo'] -> prepare('select * from users where id = :id');
	$statement -> execute(array('id' => $_SESSION['uid']));
	$user_row = $statement -> fetch();
?>
<h1><?=$text['text_settings'];?></h1>
<br />
<form method="post">
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_nickname'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="nickname" value="<?=$user_row['nickname'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p><?=$text['full_name'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="full_name" value="<?=$user_row['full_name'];?>">
		</div>
	</div>

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
						if ($item == $user_row['timezone'])
							echo '<option value="' . $item . '" selected>' . $item . '</option>';
						else
							echo '<option value="' . $item . '">' . $item . '</option>';
					}
				?>
			</select>
		</div>
	</div>	
	
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_email'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="email" value="<?=$user_row['email'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_password'];?></p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password" maxlength="16"  value="<?=$user_row['password'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p><?=$text['repeat_password'];?></p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password_repeat" maxlength="16" value="<?=$user_row['password'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p><?=$text['current_password'];?> <sup>1</sup></p>
			<p style="font-size: 0.75em"><sup>1</sup> - <?=$text['fill_current_password'];?>.</p>
		</div>
		<div class="col-md-2">
			<input type="password" name="current_password" maxlength="16"  value="">
		</div>
	</div>


	<div class="row">
		<div class="col-md-2">
			<p><?=$text['secret_question'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="secret_question" value="<?=$user_row['secret_question'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p><?=$text['secret_answer'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="secret_answer" value="<?=$user_row['secret_answer'];?>">
		</div>
	</div>

	<?php
		$cmp_country = $user_row['country'];
		$cmp_language = $user_row['language'];
		require 'countryAndLanguageFields.php'; 
	?>
	<br />
	<div class="row">
		<div class="col-md-12">
			<input type="hidden" name="menu" value="changeLanguage">
			<!-- TO DO: check email by pattern-->
			<p align="center"><input type="submit" name="submit" value="<?=$text['text_save'];?>" /></p>
		</div>
	</div>


</form>