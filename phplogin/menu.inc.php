<?php

$x = "";
if (!isset($_REQUEST['show']) && !isset($_REQUEST['search'])) $x = "home";

if (isset($_REQUEST['info'])) $x = "info"; // about us
else if (isset($_REQUEST['login'])) $x = "login";
else if (isset($_REQUEST['adv_search'])) $x = "adv_search";
else if (isset($_REQUEST['buylist'])) $x = "buylist";

$cc = 'class="active" ';

// HOME
echo '<li><a ';
if ($x == "home") echo $cc;
echo 'href="index.php">HOME</a></li>';

echo '<li><a ';
if ($x == "adv_search") echo $cc;
echo 'href="index.php?adv_search">ADV. SEARCH</a></li>';
/*
echo '<li><a ';
if ($x == "buylist") echo $cc;
echo 'href="index.php?buylist">BUYLIST</a></li>';
*/
echo "\n";

// CONTACT

echo '<li><a ';
if ($x == "info") echo $cc;
echo 'href="index.php?info">INFO</a></li>';
echo "\n";



if (isset($_SESSION['loggedin'])) {
	echo '<li><a href="/phplogin/logout.php">LOGOUT</a></li>';
	echo "\n";
} 
/* make sure to currently remove it...
else {
	echo '<li><a ';
	if ($x == "login") echo $cc;
	echo 'href="/phplogin/index.php">LOGIN</a></li>';
	echo "\n";
}
*/


// LOGIN & REGISTER
/*
if ($user->data['user_id'] != ANONYMOUS) {
	if ('localhost' == SERVER_NAME) $url_logout = "/mtgcards/forum/ucp.php?mode=logout&sid=" . $user->session_id;
	else $url_logout = "/forum/ucp.php?mode=logout&sid=" . $user->session_id;
	echo '<li><a href="' . $url_logout . '">LOGOUT</a></li>';
	echo "\n";
} else {
	echo '<li><a ';
	if ($x == "login") echo $cc;
	echo 'href="index.php?login" >LOGIN</a></li>';
	echo "\n";
}
*/

?>
