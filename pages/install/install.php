<?php
//comment these out if you're working on the installation
error_reporting(0);
ini_set('display_errors', 0);
if($step == ""){
	Redirect::to('/install/intro/');
}
if(isset($GLOBALS['config']['install']) && $GLOBALS['config']['install'] == true){
	Redirect::to('/');
}
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Install</title> 
		<meta name="author" content="Timothy Gibbons">
		<meta name="copyright" content="Copyright (C) Timothy Gibbons 2015;">
		<meta name="description" content="Install">
		<meta name="keywords" content="Installation">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="/assets/css/bootstrap 4.0/bootstrap.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="/assets/js/jquery.js"></script>
		<script src="/assets/js/bootstrap.js"></script>
	</head>
	<body>
		<?php
		switch ($step){
			case "intro":
		?>
		<div class="container-fluid">
			<h1 class="display-1 text-center">Hello</h1>
			<p class="text-muted text-center">Welcome to php-template</p>
			<div class="row align-items-center justify-content-center">
				<a href="/install/requirements/" class="btn btn-lg btn-outline-primary text-center">Let's get started!</a>
			</div>
		</div>
		<?php
			break;
			case "requirements":
				if(!isset($GLOBALS['config'])){
					echo "<script>location.href = \"/install/db/\"</script>";
				}else{
					require 'inc/init.php';
					if(DB::getInstance()->get('users', ['id', '=', '1'])->count() > 1){
						echo "<script>location.href = \"/install/finish/\"</script>";
					}else{
						echo "<script>location.href = \"/install/register/\"</script>";
					}
				}
			break;
			case "db":
			require "inc/classes/Token.class.php";
			require "inc/classes/Input.class.php";
			require "inc/classes/Output.class.php";
			require "inc/classes/Session.class.php";
			if(Input::exists()){
				if(Token::check(Input::get('token'))){
					if(Input::get('test')){
						$conn = new mysqli(
							gethostbyname(Output::clean(Input::get('serverAddress'))), 
							Output::clean(Input::get('username')), 
							Output::clean(Input::get('password')), 
							Output::clean(Input::get('database')),
							(int) Output::clean(Input::get('serverPort'))
						);
						if($conn->connect_error){
							Session::flash('alert-danger', "Cannot connect to server!");
						}else{
							Session::flash('alert-success', "Sucessfully connected to the server!");
						}
					}else{
						$conn = new mysqli(
							gethostbyname(Output::clean(Input::get('serverAddress'))), 
							Output::clean(Input::get('username')), 
							Output::clean(Input::get('password')), 
							Output::clean(Input::get('database')),
							(int) Output::clean(Input::get('serverPort'))
						);
						if($conn->connect_error){
							Session::flash('alert-danger', "Cannot connect to server!");
						}else{
							//We insert it into the config...
							$config =	'<?php'.PHP_EOL.
										'$GLOBALS[\'config\'] = ['.PHP_EOL.
										'	\'mysql\' => ['.PHP_EOL.
										'		\'host\' => \''.Output::clean(Input::get('serverAddress')).'\','.PHP_EOL.
										'		\'port\' => \''.Output::clean(Input::get('serverPort')).'\','.PHP_EOL.
										'		\'db\' => \''.Output::clean(Input::get('database')).'\','.PHP_EOL.
										'		\'prefix\' => \''.Output::clean(Input::get('prefix')).'\','.PHP_EOL.
										'		\'user\' => \''.Output::clean(Input::get('username')).'\','.PHP_EOL.
										'		\'password\' => \''.Output::clean(Input::get('password')).'\','.PHP_EOL.
										'	],'.PHP_EOL.
										'	\'session\' => ['.PHP_EOL.
										'		\'session_name\' => \'session\','.PHP_EOL.
										'		\'token_name\' => \'token\','.PHP_EOL.
										'		\'cookie_name\' => \'cookie\','.PHP_EOL.
										'		\'admin_cookie_name\' => \'adm_cookie_name\','.PHP_EOL.
										'		\'admin_session_name\' => \'adm_session\','.PHP_EOL.
										'	],'.PHP_EOL.
										'	\'remember\' => ['. PHP_EOL.
										'		\'expiry\' => 604800,'.PHP_EOL.
										'	],'.PHP_EOL.
										'];';
							if(!file_exists('inc/config.php')){
								$temp = fopen('inc/config.php', 'w');
								fclose($temp);
							}
							if(is_writable('inc/config.php') && !isset($GLOBALS['config'])){
								$file = fopen('inc/config.php','w');
								fwrite($file, $config);
								fclose($file);
							} else {
								die('Config not writable');
							}
							echo "<script>location.href=\"/install/dbInit/\"</script>";
						}
					}
				}
			}
		?>
		<div class="container-fluid">
			<?php
			if(Session::exists('alert-danger')){
			?>
			<div class="alert alert-danger"><?php echo Session::flash('alert-danger'); ?></div>
			<?php
			}
			if(Session::exists('alert-success')){
			?>
			<div class="alert alert-success"><?php echo Session::flash('alert-success'); ?></div>
			<?php
			}
			?>	
			<div class="card mt-5">
				<div class="card-body">
					<form action="" method="POST" autocomplete="off">
						<h1 class="text-center">Database</h1>
						<div class="form-group">
							<div class="form-row">
							<div class="col text-center"><label for="serverAddr">Server Address</label></div>
							<div class="col text-center"><label for="serverPort">Server Port</label></div>
							</div>
							<div class="input-group">
								<input name="serverAddress" id="serverAddr" type="text" placeholder="127.0.0.1" class="form-control" value="<?php echo Input::get('serverAddress'); ?>">
								<div class="input-group-append">
									<span class="input-group-text">:</span>
								</div>
								<input name="serverPort" id="serverPort" type="number" placeholder="3306" class="form-control" value="<?php echo Input::get('serverPort'); ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="database">Database</label>
							<input type="text" class="form-control" name="database" id="database" placeholder="template" value="<?php echo Input::get('database'); ?>">
						</div>
						<div class="form-group">
							<label for="prefix">Prefix</label>
							<input type="text" class="form-control" id="prefix" name="prefix" placeholder="php_" value="<?php echo Input::get('prefix'); ?>">
						</div>
						<div class="form-group">
							<label for="username">Username:</label>
							<input type="text" class="form-control" id="username" name="username" placeholder="root" value="<?php echo Input::get('username'); ?>">
						</div>
						<div class="form-group">
							<label for="password">Password:</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="password" value="<?php echo Input::get('password'); ?>">
						</div>
						<div class="form-group">
							<div class="form-row">
								<div class="col">
									<input type="reset" class="form-control btn-md btn-danger" value="Reset">
								</div>
								<div class="col">
									<input type="submit" name="test" value="Test" class="form-control btn-md btn-warning">
								</div>
								<div class="col"><input type="submit" value="Submit" class="btn btn-md btn-primary form-control"></div>
							</div>
						</div>
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					</form>
				</div>
			</div>
		</div>
		<?php
			break;
			case "dbInit":
				require "install-database.php";
			break;
			case "register":
			require "inc/classes/DB.class.php";
			require "inc/classes/Token.class.php";
			require "inc/classes/User.class.php";
			require "inc/classes/Input.class.php";
			require "inc/classes/Output.class.php";
			require "inc/classes/Session.class.php";
			require "inc/classes/Validation.class.php";

			if(Input::exists()){
				if(Token::check(Input::get('token'))){
					$val = new Validation();
					$validate = $val->check($_POST, [
						"username" => [
							"required" => true,
							"min" => 2,
							"max" => 50,
							"spaces" => false,
							"unique" => "users",
						],
						"name" => [
							"required"=> true,
						],
						"email" => [
							"required" => true,
							"min" => 3,
							"unique" => "users",
						],
						"password" => [
							"required" => true,
							"min" => 8,
							"matches" => "password_conf",
						],
						"password_conf" => [
							"required" => true,
							"matches" => "password",
							"min" => 8,
						],
					]);

					if($validate->passed()){
						$user = new User();
						$salt = Hash::salt(16);
						$pass = Hash::make(Input::get('password'), $salt);
						if(DB::getInstance()->insert("users",[
							"username" => Output::clean(Input::get('username')),
							"name" => Output::clean(Input::get('name')),
							"password" => $pass,
							"salt" => $salt,
							"email" => Output::clean(Input::get('email')),
							"joined" => date('Y-m-d H:i:s'),
							"group" => 3,
							"active" => 1,
						])){
							echo "<script>location.href=\"/install/finish/\"</script>";
						}else{
							Session::flash('alert-danger', 'There was an problem inserting user!');
						}
					}else{
						$msg = "";
						foreach ($validate->errors() as $error) {
							$msg .= $error . "<br>";
						}
						Session::flash("alert-danger", $msg);
					}
				}
			}
		?>
		<div class="container-fluid mt-5">
			<?php
			if(Session::exists('alert-danger')){
			?>
			<div class="alert alert-danger"><?php echo Session::flash('alert-danger'); ?></div>
			<?php
			}
			?>

			<div class="card">
				<div class="card-body">
					<h1>Register</h1>
					<form action="" method="POST">
						<div class="form-group">
							<label for="name">Name:</label>
							<input type="text" class="form-control" name="name" placeholder="Name" id="name" value="<?php echo Input::get('name'); ?>">
						</div>
						<div class="form-group">
							<label for="username">Username:</label>
							<input type="text" class="form-control" name="username" placeholder="Username" id="username" value="<?php echo Input::get('username'); ?>">
						</div>
						<div class="form-group">
							<label for="email">Email:</label>
							<input value="<?php echo Input::get('email'); ?>" type="email" class="form-control" name="email" placeholder="Email" id="email">
						</div>
						<div class="form-group">
							<div class="form-row">
								<div class="col">
									<label for="password">Password</label>
									<input type="password" placeholder="Password" name="password" id="password" class="form-control" value="<?php echo Input::get('password'); ?>">
								</div>
								<div class="col">
									<label for="password_conf">Confirm Password</label>
									<input type="password" placeholder="Confirm Password" name="password_conf" id="password_conf" class="form-control" value="<?php echo Input::get('password_conf'); ?>">
								</div>
							</div>
						</div>
						<div class="form-group float-right">
							<input type="submit" value="Submit" class="btn btn-primary">
							<input type="hidden" name="token" value="<?php echo Token::generate();?>">
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
			break;
			case "finish":
			if(!file_exists('inc/install.php')){
				$temp = fopen('inc/install.php', 'w');
				fclose($temp);
			}
			if(is_writable('inc/install.php'))
					file_put_contents('inc/install.php', '<?php\n$CONFIG[\'config\'][\'install\'] = true;\n?>', FILE_APPEND);
			else
				die('Config not writable');
		?>
		<div class="container-fluid">
			<h1 class="display-1 text-center">Congrats</h1>
			<p class="text-muted text-center">You have finished installing the software! <br> See how easy was that?</p>
			<div class="row align-items-center justify-content-center">
				<a href="/" class="btn btn-lg btn-outline-primary text-center">Let's get started!</a>
			</div>
		</div>
		<?php
			break;
		}
		?>
	</body>
</html>