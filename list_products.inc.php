<?php

if (isset($_REQUEST['show']) && isset($_REQUEST['prod'])) $current_prod = $_REQUEST['prod'];
else $current_prod = "";

$sql = "SELECT category_name, category_id FROM mtgcards_categories WHERE category_up='0' ORDER BY category_name";
$result = mysqli_query($db_connection, $sql); // or die ("Query [" . sql . "] mislukt : " . mysql_error());

echo "<h2>MTG Products</h2>";
?>
<!--<ul>
	<li><a href="index.php?coming_soon"><strong>Upcoming products</strong></a></li>
	<li><a href="index.php?new_products"><strong>New products</strong></a></li> 
</ul>-->
<?php					
echo '<ul>';
while ($row = mysqli_fetch_array($result)) {
	$cat_nm = $row['category_name'];
	$cat_id = $row['category_id'];
	echo '<li><a ';
	if ($cat_id == $current_prod) echo 'class="current" ';
	echo 'href="index.php?show&prod=' . $cat_id . '">' . $cat_nm . '</a></li>';
	echo "\n";
}
echo '</ul>';
	

?>