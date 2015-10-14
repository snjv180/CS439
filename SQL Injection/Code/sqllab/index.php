<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>SQL Injection Test</title>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="bootstrap-theme.min.css">
		<script src="bootstrap.min.js"></script>
		<script src="jquery.min.js"></script>
		<link rel="stylesheet" href="styles.css">
	</head>
<body>
<div class="container">
	<div class="row">
	<div class="col-sm-6 col-md-4 col-md-offset-4">
	<h1 class="text-center login-title">Login Administrator</h1>
	<div class="account-wall">
	
	<img class="profile-img" src="LoginRed.jpg" alt="">

	<form class="form-signin" method="POST" action="check_login.php">
		<input type="text" name="username" class="form-control" placeholder="Email" required autofocus>
		<br/>
		<input type="password" name="password" class="form-control" placeholder="Password" required>
		<br/>
		<input type='hidden' name='submit' id='submit' value='1'/>
		<button class="btn btn-lg btn-primary btn-block" type="submit" >
			Sign in
		</button>
		<br/>
		<label class="checkbox pull-left">
			<input type="checkbox" value="remember-me">
			Remember me
		</label>
		
		<a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
		
	</form>
	</div>
		
<a href="#" class="text-center new-account">Create an account </a>

</div>
</div>
</div> 
</body>
</html>