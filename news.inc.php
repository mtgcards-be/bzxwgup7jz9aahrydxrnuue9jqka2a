<?php 

include_once 'conn.inc.php';
include_once 'functions-news.inc.php';


if ($db_connection) {
	$sql = "SELECT date, title, text FROM news ORDER BY date DESC, id DESC LIMIT 0, 7";
	$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());

	while ($row = mysqli_fetch_array($result)) {
		write_news($row);
	}
}

?>
