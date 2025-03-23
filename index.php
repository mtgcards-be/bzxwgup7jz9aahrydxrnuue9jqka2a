<?php

session_start();

// ----------------------------
        $maintenance = false;
// ----------------------------

include_once 'error-logging.inc.php';
include_once 'phplogin/main.php';
include_once "conn.inc.php";

if (isset($_REQUEST['error'])) $error = $_REQUEST['error'];
else $error = '';

if(isset($_SESSION)) {
	if (isset($_SESSION['loggedin'])) $id = $_SESSION['id'];
	else $id = -1;
	if (isset($_SESSION['name'])) $username = $_SESSION['name'];
	else $username = '';
	if (isset($_SESSION['loggedin'])) $user_loggedin = $_SESSION['loggedin'];
	else $user_loggedin = -1;
} else {
	$id = -1;
	$username = '';
	$user_loggedin = -1;
}

// access only when logged in
if (isset($_SESSION['loggedin'])) $login = true;
else $login = false;
	
// remove afterwards the login check -- currently used for testing
// if (!$maintenance) {
// if (!$maintenance OR isset($_SESSION['loggedin'])) {
if (!$maintenance) {




	if ($db_connection) {
		$sql = sprintf("SELECT land FROM mtgcards_users WHERE id='%d'", mysqli_real_escape_string($db_connection, $id));
		$result = mysqli_query($db_connection, $sql);

		if ($result && mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$country = $row['land'];
			$_SESSION['country'] = $country;
		} else {
			$_SESSION['country'] = -1; // Set country to -1 if no results or error
		}

	} else {
		$_SESSION['country'] = -1;
	}


	if ('localhost' == SERVER_NAME) $title = 'localhost | mtgcards.be';
	else $title = 'mtgcards.be | Magic the Gathering Online Shop';

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<script src="js/modernizr.custom.js"></script>

	<title><?php echo $title ?></title>

	<link rel="stylesheet" type="text/css" href="style-new.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="typeahead.css" media="screen" />
	<link rel="shortcut icon" href="https://www.mtgcards.be/m.ico" />


	</head>
	<body>

	<div id="wrapper">

	<div id="header">
		<div id="logo">
		</div>
		<!-- end #logo -->
		<div id="menu">
	<ul>
	<?php include 'menu.inc.php'; // ---------------OK ?>
	</ul>
		</div>
		<!-- end #menu -->
	</div>
	<!-- end #header -->
	<div id="page">
		<!-- <div id="header-pic"></div> -->
		<div id="content">
			<div class="post">
				<?php
					// two banners

					if ($error !== '') {
?>

	<center>
	<p style="color: #FF0000; background-color: #FFE4E1; padding: 10px; border: 1px solid #FF0000;">
	<?php echo $error; ?>
	</p>

<?php
					}

					// include_once("banners.inc.php");


					if (isset($_REQUEST['adv_search'])) include_once("adv_search.inc.php"); // ----------------------- OK
					else if (isset($_REQUEST['info'])) include_once("info.inc.php"); // ------------------------------ OK
					else if (isset($_REQUEST['cart']) && $login) include_once("cart.inc.php"); // -------------------- OK
					
					else if (isset($_REQUEST['show'])) include_once("show.inc.php"); // ------------------------------ OK
					else if (isset($_REQUEST['search'])) include_once("search.inc.php"); // -------------------------- OK
					else if (isset($_REQUEST['register']) && $login) include_once("register.inc.php"); // ------------ ??
					else if (isset($_REQUEST['delcart']) && $login) include_once("delcart.inc.php"); // -------------- OK
					else if (isset($_REQUEST['thx_order']) && $login) include_once("thx_order.inc.php"); // ---------- OK
					else if (isset($_REQUEST['account']) && $login) include_once("account.inc.php"); // -------------- OK
					else if (isset($_REQUEST['orderinfo']) && $login) include_once("orderinfo.inc.php"); // ---------- OK
					else if (isset($_REQUEST['thx_pp']) && $login) include_once("thx_pp.inc.php"); // ---------------- OK
					else if (isset($_REQUEST['paypal']) && $login) include_once("paypal.inc.php"); // ---------------- OK

					// anything else = home
					else include_once("home.inc.php"); // ------------------------------------------------------------- OK
				?>
			</div>
			<?php
			/*
			<div class="post">
				<h1 class="title">Test</h1>
				<div class="entry">
					<p>Test...</p>
				</div>
			</div>
			*/
			?>
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<div id="sidebar-bgtop"></div>
			<div id="sidebar-content">
				<div id="sidebar-bgbtm">
				<ul>


<li id="search">
  <h2>Search cards</h2>
  <form method="post" action="index.php">
    <fieldset>
      <input type="hidden" name="search" />
      <input type="text" id="find" name="find" value="" autocomplete="off" />
      <input align="top" type="image" src="images/search.jpg" id="searchButton" alt="search" name="search" value="submit" />
      <ul id="suggestions" class="typeahead-suggestions"></ul>
    </fieldset>
  </form>
</li>

					<li>
					<?php
					if ($login) include_once 'userinfo.inc.php'; // ----------- OK
					// products
					include_once 'list_products.inc.php'; // ------------------------------------------------------------------------------ OK
					// singles sets
					include_once 'list_sets.inc.php'; // ---------------------------------------------------------------------------------- OK

					?>
					</li>
					<?php
					/*
					<li>
						<h2>Lorem Ipsum</h2>
						<ul>
							<li><a href="#">Fusce dui neque fringilla</a></li>
							<li><a href="#">Eget tempor eget nonummy</a></li>
							<li><a href="#">Magna lacus bibendum mauris</a></li>
							<li><a href="#">Nec metus sed donec</a></li>
							<li><a href="#">Magna lacus bibendum mauris</a></li>
							<li><a href="#">Velit semper nisi molestie</a></li>
							<li><a href="#">Eget tempor eget nonummy</a></li>
						</ul>
					</li>
					*/
					?>
				</ul>
				</div>
			</div>
		</div>
		<!-- end #sidebar -->
		<div style="clear:both; margin:0;"></div>
	</div>
	<!-- end #page -->

	</div>

	<div id="footer">

	<p>BTW BE 0806.364.364 | ING 377-0047889-08 | &copy; <?php echo date("Y"); ?> <strong><a href="https://www.mtgcards.be">mtgcards.be</a></strong></p>
	</div>
	<!-- end #footer -->
	
	<script src="/js/typeahead.js"></script>
	</body>
	</html>

<?php
} else {
?>
<html><center><img src="maintenance.jpg"/>
<br/>
<br/>
This shouldn't take too long. You can always contact us via email for orders or other purchase questions!
</center>
</html>
<?php
}
?>
