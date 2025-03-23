<div class="homepage-content">
<?php

// https://www.mtgcards.be/test_mtgcards_index.php?page=activate&email=sven.buyle%2Btest%40gmail.com&code=67c4ce285e03e

// First we check if the email and code exists...
if (isset($_GET['email'], $_GET['code']) && !empty($_GET['code'])) {
	$stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ?');
	$stmt->execute([$_GET['email'], $_GET['code']]);
	
	// Store the result so we can check if the account exists in the database.
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($account) {
		// Account exists with the requested email and code.
		$stmt = $pdo->prepare('UPDATE accounts SET activation_code = ? WHERE email = ? AND activation_code = ?');
		// Set the new activation code to 'activated', this is how we can check if the user has activated their account.
		$activated = 'activated';
		$stmt->execute([$activated, $_GET['email'], $_GET['code']]);
?>

	<h2>Account activated</h2>
	<p>
		Thank you for registering and activating your account. 
		<br/>
		Perhaps now is a perfect time to complete the resitration and complete your account details? ;)
	</p>

<?php
	} else {
?>
	
	<h2>Account is not activated</h2>
	<p>
		Something went wrong, perhaps you best try again?
	</p>

<?php
	}
} else {
?>

	<h2>Account is not activated</h2>
	<p>
		Somehow you didn't seem to have the correct link to activate your account. Perhaps you best try again?
	</p>

<?php
}
?>
</div>