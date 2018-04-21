<?php

?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'assets/head.php';?>
</head>
<body>
	<?php include 'assets/nav.php';?>
	<div class="conatiner-fluid mt-4">
		<?php if(Session::exists('alert-warning')): ?>
			<div class="alert alert-warning"><?php echo Session::flash('alert-warning'); ?></div>
		<?php endif; ?>
		<?php if(Session::exists('alert-danger')): ?>
			<div class="alert alert-danger"><?php echo Session::flash('alert-danger'); ?></div>
		<?php endif; ?>
		<?php if(Session::exists('alert-success')): ?>
			<div class="alert alert-success"><?php echo Session::flash('alert-success'); ?></div>
		<?php endif; ?>
	<div class="dropdown-divider"></div>
	<?php include 'assets/foot.php';?>
</body>
</html>