<?php
include 'main.php';
// Output message
$msg = '';
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (isset($_GET['email'], $_GET['code']) && !empty($_GET['code'])) {
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ? AND reset = ?');
    $stmt->execute([$_GET['email'], $_GET['code']]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // If the account exists with the email and code
    if ($account) {
        if (isset($_POST['npassword'], $_POST['cpassword'])) {
            if (strlen($_POST['npassword']) > 20 || strlen($_POST['npassword']) < 5) {
            	$msg = 'Password must be between 5 and 20 characters long!';
            } else if ($_POST['npassword'] != $_POST['cpassword']) {
                $msg = 'Passwords must match!';
            } else {
                $stmt = $pdo->prepare('UPDATE accounts SET password = ?, reset = "" WHERE email = ?');
            	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
            	$password = password_hash($_POST['npassword'], PASSWORD_DEFAULT);
            	$stmt->execute([$password, $_GET['email']]);
                $msg = 'Password has been reset! You can now <a href="index.php">login</a>!';
            }
        }
    } else {
        exit('Incorrect email and/or code!');
    }
} else {
    exit('Please provide the email and code!');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Reset Password</title>
        <link rel="shortcut icon" href="http://www.mtgcards.be/m.ico" /> 
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<div class="login">
            <h1>mtgcards.be</h1>
            <div class="links">
                <a href="resetpassword.php?email=<?=$_GET['email']?>&code=<?=$_GET['code']?>" class="active">Reset Password</a>
            </div>
			<form action="resetpassword.php?email=<?=$_GET['email']?>&code=<?=$_GET['code']?>" method="post">
                <label for="npassword">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="npassword" placeholder="New Password" id="npassword" required>
                <label for="cpassword">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="cpassword" placeholder="Confirm Password" id="cpassword" required>
				<div class="msg"><?=$msg?></div>
				<input type="submit" value="Submit">
			</form>
		</div>
	</body>
</html>
