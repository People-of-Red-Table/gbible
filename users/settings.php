<?php
	$statement = $links['sofia']['pdo'] -> prepare('select * from users where id = :id');
	$statement -> execute(array('id' => $_SESSION['uid']));
	$user_row = $statement -> fetch();
?>
<h1>Settings</h1>
<br />
<form method="post">
	<div class="row">
		<div class="col-md-2">
			<p>Nickname</p>
		</div>
		<div class="col-md-2">
			<input type="text" name="nickname" value="<?=$user_row['nickname'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Full Name</p>
		</div>
		<div class="col-md-2">
			<input type="text" name="full_name" value="<?=$user_row['full_name'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Timezone</p>
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
			<p>E-Mail</p>
		</div>
		<div class="col-md-2">
			<input type="text" name="email" value="<?=$user_row['email'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Password</p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password" maxlength="16"  value="<?=$user_row['password'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Repeat Password</p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password_repeat" maxlength="16" value="<?=$user_row['password'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Current Password <sup>1</sup></p>
			<p style="font-size: 0.75em"><sup>1</sup> - Fill out this field if you want to change your password.</p>
		</div>
		<div class="col-md-2">
			<input type="password" name="current_password" maxlength="16"  value="">
		</div>
	</div>


	<div class="row">
		<div class="col-md-2">
			<p>Secret Question</p>
		</div>
		<div class="col-md-2">
			<input type="text" name="secret_question" value="<?=$user_row['secret_question'];?>">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Secret Answer</p>
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

	<div class="row">
		<div class="col-md-12">
			<input type="hidden" name="menu" value="users_saveSettings">
			<!-- TO DO: check email by pattern-->
			<p align="center"><input type="submit" name="submit" value="Save" /></p>
		</div>
	</div>


</form>