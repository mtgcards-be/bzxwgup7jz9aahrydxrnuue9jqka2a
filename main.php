<?php

// login system
// We need to use sessions, so you should always start sessions using the below code.

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

include_once 'phplogin_conn.inc.php';



// No need to edit below
try {
	$pdo = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
} catch (PDOException $exception) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to database!');
}
// The below function will check if the user is logged-in and also check the remember me cookie
function checkLoggedIn($pdo) {
	// Check for remember me cookie variable and loggedin session variable
    if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme']) && !isset($_SESSION['loggedin'])) {
    	// If the remember me cookie matches one in the database then we can update the session variables.
    	$stmt = $pdo->prepare('SELECT id, username FROM accounts WHERE rememberme = ?');
    	$stmt->execute([$_COOKIE['rememberme']]);
    	$account = $stmt->fetch(PDO::FETCH_ASSOC);
    	if ($account) {
    		// Found a match, update the session variables and keep the user logged-in
    		session_regenerate_id();
    		$_SESSION['loggedin'] = TRUE;
    		$_SESSION['name'] = $account['username'];
    		$_SESSION['id'] = $account['id'];
    	} else {
    		// If the user is not remembered redirect to the login page.
    		header('Location: index.php');
    		exit;
    	}
    } else if (!isset($_SESSION['loggedin'])) {
    	// If the user is not logged in redirect to the login page.
    	header('Location: index.php');
    	exit;
    }
}

function loginAttempts($pdo, $update = TRUE) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $now = date('Y-m-d H:i:s');
    if ($update) {
        $stmt = $pdo->prepare('INSERT INTO login_attempts (ip_address, `date`) VALUES (?,?) ON DUPLICATE KEY UPDATE attempts_left = attempts_left - 1, `date` = VALUES(`date`)');
        $stmt->execute([$ip,$now]);
    }
    $stmt = $pdo->prepare('SELECT * FROM login_attempts WHERE ip_address = ?');
    $stmt->execute([$ip]);
    $login_attempts = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($login_attempts) {
        // The user can try to login after 1 day... change the "+1 day" if you want increase/decrease this date.
        $expire = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($login_attempts['date'])));
        if ($now > $expire) {
            $stmt = $pdo->prepare('DELETE FROM login_attempts WHERE ip_address = ?');
            $stmt->execute([$ip]);
            $login_attempts = array();
        }
    }
    return $login_attempts;
}
?>
