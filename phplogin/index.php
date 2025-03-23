<?php
include 'main.php';
// If the user is logged-in redirect them to the home page
if (isset($_SESSION['loggedin'])) {
	// header('Location: https://www.mtgcards.be/index.php');
	header('Location: /index.php');
    exit;
}
// Also check if the user is remembered, if so redirect them to the home page
if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme'])) {
	// If the remember me cookie matches one in the database then we can update the session variables and the user will be logged-in.
	$stmt = $pdo->prepare('SELECT id, username FROM accounts WHERE rememberme = ?');
	$stmt->execute([$_COOKIE['rememberme']]);
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($account) {
		// Found a match, user is "remembered" log them in automatically
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $account['username'];
		$_SESSION['id'] = $account['id'];
//		header('Location: https://www.mtgcards.be/index.php');
		header('Location: /index.php');
		exit;
	}
}
$_SESSION['token'] = md5(uniqid(rand(), true));
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Login</title>
		<link rel="shortcut icon" href="http://www.mtgcards.be/m.ico" />
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	</head>
	<body>
		<div class="login">
			<h1>mtgcards.be</h1>
			<div class="links">
				<a href="index.php" class="active">Login</a>
				<a href="register.html">Register</a>
			</div>
			<form action="authenticate.php" method="post">

				<label for="username">
					<i class="fas fa-envelope"></i>
				</label>
				<input type="text" name="username" placeholder="Email" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<label id="rememberme">
					<input type="checkbox" name="rememberme">Remember me
				</label>
				<a href="forgotpassword.php">Forgot Password?</a>
				<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
				<div class="msg"></div>
				<input type="submit" value="Login">
			</form>
		</div>
		<script>
        $(".login form").submit(function(event) {
			event.preventDefault();
			var form = $(this);
		    var url = form.attr('action');
		    $.ajax({
				type: "POST",
				url: url,
				data: form.serialize(),
				success: function(data) {
					if (data.toLowerCase().includes("success")) {
						window.location.href = "/index.php";
					} else {
						$(".msg").html(data);
					}
				}
		    });
		});
		</script>
	</body>
</html>
