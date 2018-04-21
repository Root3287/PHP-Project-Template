<?php
$user = new User();
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			"username" => [
				"required" => true,
				"min" => 2,
				"unique" => "users",
				"spaces" => false,
			],
			"password_conf" => [
				"required" => true,
				"matches" => "password",
			],
			"password" => [
				"required" => true,
				"matches" => "password_conf",
				"min" => 8,
			],
			"email" => [
				"required" => true,
				"min" => 3,
				"unique" => "users",
				"spaces" => false,
			],
			"name" => [
				"required" => true,
				"min" => 2,
			],
		]);

		$msg = "";

		if($validate->passed()){
			try{
				$salt = Hash::salt(16);
				$user->create([
					"username" => Output::clean(Input::get('username')),
					"password" => Hash::make(Input::get('password'), $salt),
					"salt" => $salt,
					"group" => 1,
					"name" => Output::clean(Input::get('name')),
					"email" => Output::clean(Input::get('email')),
					"joined" => date("Y-m-d H:i:s"),
					"active" => 1,
				]);
				if($user->login(Input::get('username'), Input::get('password'))){
					Session::flash('alert-success', "You have been registered and logged in!");
					Redirect::to('/');
				}
			}catch(Exception $e){
				Session::flash('alert-danger', $e->getMessage());
			}
		}else{
			foreach ($validate->errors() as $error) {
				$msg.=$error."<br>";
			}
			Session::flash('alert-danger', $msg);
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'assets/head.php';?>
</head>
<body>
	<?php include 'assets/nav.php';?>
	<div class="container-fluid mt-4">
		<?php if(Session::exists('alert-danger')): ?>
			<div class="alert alert-danger"><?php echo Session::flash('alert-danger'); ?></div>
		<?php endif; ?>
		<div class="card">
			<div class="card-body">
				<h1 class="text-center">Register</h1>
				<form action="" method="POST">
					<div class="form-group">
						<label for="username">Username:</label><input name="username" type="text" id="username" class="form-control">
					</div>
					<div class="form-group">
						<label for="username">Name:</label><input name="name" type="text" id="name" class="form-control">
					</div>
					<div class="form-group">
						<label for="username">Email:</label><input name="email" type="email" id="email" class="form-control">
					</div>
					<div class="form-row form-group">
						<div class="col">
							<label for="password">Password:</label><input name="password" type="password" id="password" class="form-control">
						</div>
						<div class="col">
							<label for="password_conf">Confirm Password:</label><input name="password_conf" type="password" id="password_conf" class="form-control">
						</div>
					</div>
					<div class="form-row">
						<div class="col"><a href="/register" class="form-control btn btn-md btn-danger">Register</a></div>
						<div class="col"><input type="submit" value="Submit" class="form-control btn btn-md btn-primary"></div>
					</div>
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				</form>
			</div>
		</div>
	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>