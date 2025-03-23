<?php
include 'main.php';
// Output message
$msg = '';
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (isset($_POST['email'])) {
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
    $stmt->execute([$_POST['email']]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // If the account exists with the email
    if ($account) {
        // Account exist, the $msg variable will be used to show the output message (on the HTML form)
        // Update the reset code in the database
        $stmt = $pdo->prepare('UPDATE accounts SET reset = ? WHERE email = ?');
    	$uniqid = uniqid();
    	$stmt->execute([$uniqid, $_POST['email']]);
        // Email to send below, customize this

        //$from = 'mtgcards.be <info@mtgcards.be>'; // Change "Your Company Name" and "yourdomain.com", do not remove the < and >
        $from = 'mtgcards.be <no-reply@mtgcards.be>';
    	$subject = 'Password Reset';
    	$headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'Return-Path: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        // Change the link below from "yourdomain.com" to your own domain name where the PHP login system is hosted
        $reset_link = 'https://www.mtgcards.be/phplogin/resetpassword.php?email=' . $_POST['email'] . '&code=' . $uniqid;
    	// Feel free to customize the email message below
    	$message = '<p>Please click the following link to reset your password: <a href="' . $reset_link . '">' . $reset_link . '</a></p>';
        // Send email to the user
    	mail($_POST['email'], $subject, $message, $headers);
        $msg = 'Reset password link has been sent to your email!';
    } else {
        $msg = 'We do not have an account with that email!';
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Forgot Password</title>
        <link rel="shortcut icon" href="http://www.mtgcards.be/m.ico" /> 
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<div class="login">
            <h1>mtgcards.be</h1>
            <div class="links">
                <a href="forgotpassword.php" class="active">Forgot Password</a>
            </div>
			<form action="forgotpassword.php" method="post">
                <label for="email">
					<i class="fas fa-envelope"></i>
				</label>
				<input type="email" name="email" placeholder="Your Email" id="email" required>
				<div class="msg"><?=$msg?></div>
				<input type="submit" value="Submit">
			</form>
		</div>
	</body>
</html>
