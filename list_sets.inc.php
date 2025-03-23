<?php

if (isset($_REQUEST['show']) && isset($_REQUEST['set'])) $current_set = $_REQUEST['set'];
else $current_set = "";

/*
$set_t2 = array('10E', 'LRW', 'MOR', 'SHM', 'EVE', 'ALA', 'CON');
$t2_count = count($set_t2);

$sql = "SELECT setname FROM sets WHERE setcode=";

$list = '<ul>';
for ($i = $t2_count-1; $i >= 0; $i--) {
	$sql2 = $sql . "'" . $set_t2[$i] . "'";
	$result = mysql_query($sql2) or die ("Query [" . $sql2 . "] mislukt : " . mysql_error());
	$row = mysql_fetch_array($result);
	$setcode = $set_t2[$i];
	$setname = $row['setname'];
	$list .= '<li><a ';
    if ($setcode == $current_set) $list .= 'class="current" ';
    $list .= 'href="index.php?show&set=' . $setcode . '">' . $setname . '</a></li>';
    $list .= "\n";
}
$list .= '</ul>';

echo "<h2>Standard (Type2) Sets</h2>";
echo $list;
*/
$sql = "SELECT setcode, setname FROM sets ORDER BY setname";
	
$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());

if (isset($_REQUEST['show']) && isset($_REQUEST['set'])) $current_set = $_REQUEST['set'];
else $current_set = "";

echo "<h2>MTG Singles</h2>";

echo "<ul>";

while ($row = mysqli_fetch_array($result)) {
    echo '<li><a ';
    if ($row['setcode'] == $current_set) echo 'class="current" ';
    echo 'href="index.php?show&set=' . $row['setcode'] . '">' . $row['setname'] . '</a></li>';
    echo "\n";
} 

echo "</ul>";

?>

