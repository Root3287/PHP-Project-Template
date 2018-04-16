<?php
//comment these out if you're working on the installation
error_reporting(0);
ini_set('display_errors', 0);

if($step == ""){
	Redirect::to('/install/intro/');
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
		?>
		<script>location.href = "/install/db/"</script>
		<?php
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
							<label for="database">Database:</label>
							<input type="text" class="form-control" id="database" name="database" placeholder="template" value="<?php echo Input::get('database'); ?>">
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
		}
		?>
	</body>
</html>