<?php
$userAdm = new AdminUser();
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			"username" => [
				"required" => true,
			],
			"password" => [
				"required" => true,
			],
		]);

		$msg = "";

		if($validate->passed()){
			if($userAdm->login(Input::get('username'), Input::get('password'))){
				Redirect::to('/admin');
			}else{
				$msg .= "Wrong username/password<br>";
				Session::flash('alert-danger', $msg);
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
				<h1 class="text-center">Admin Login</h1>
				<form action="" method="POST">
					<div class="form-group">
						<label for="username">Username:</label><input type="text" name="username" id="username" class="form-control">
					</div>
					<div class="form-group"><label for="password">Password:</label><input type="password" name="password" id="password" class="form-control"></div>

					<div class="form-group float-right"><input type="submit" value="Login" class="btn btn-lg btn-primary"></div>

					<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				</form>
			</div>
		</div>
	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>