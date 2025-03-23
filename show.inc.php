<?php

include_once 'functions-products.inc.php';
include_once 'functions-cards.inc.php';


// if ($_SESSION['name'] = '??') $userIsAdmin = true;
$userIsAdmin = false;

$what_to_show = '';

// Check if connection was successful
if (!$db_connection) {
  // Handle the error here
  $what_to_show = "db_error";
//  exit(); // Stop script execution (optional)
} else {
  
	if (isset($_REQUEST['set'])) {
		$set = $_REQUEST['set'];
		$sql = sprintf("SELECT setname FROM sets WHERE setcode = '%s'", mysqli_real_escape_string($db_connection, $set));
		// $result = mysqli_query($sql) or die ("Query mislukt : " . mysql_error());
		$result = mysqli_query($db_connection, $sql);
		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_array($result);
			$what_to_show = 'set';
		}
	} else if (isset($_REQUEST['prod'])) {
		$prod = $_REQUEST['prod'];
		$sql = sprintf("SELECT category_name, category_up, end FROM mtgcards_categories WHERE category_id = '%s'", mysqli_real_escape_string($db_connection, $prod));
		$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());
		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_array($result);
			$what_to_show = 'prod';
		} 
	}	

}



if ( $what_to_show == 'set' ) {

	if (isset($_REQUEST['start'])) $start = $_REQUEST['start'];
	else $start = 0;
	if (!is_numeric($start) OR $start < 0) $start = 0;

	$set = $_REQUEST['set'];
	$offset = 50;



    $txt_overview = '<h1 class="title">' . $row['setname'] . ' - ';
    //$txt_overview = '<h1 class="title">' . $row['setname'] . '</h1><p class="byline"><small>';
	$sql = sprintf("SELECT count(*) total FROM cards WHERE setcode = '%s'", mysqli_real_escape_string($db_connection, $set));
	// $result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());
	$result = mysqli_query($db_connection, $sql);
	//$num_rows = mysql_num_rows($result);
	$row = mysqli_fetch_array($result);
	$num_rows = $row['total'];
	/*
	$id = $row['id'];
	$card = $row['cardname'];
	$rarity = $row['rarity'];
	$color = $row['color'];
	$price = $row['price'];
	$quantity = $row['quantity'];
	*/
	$sql = sprintf("SELECT price_change, diff, cards.id id, cards.rarity r, cards.setcode setcode, cards.cardname cardname, cards.price price, cards.quantity quantity, sets.setname setname, rarities.rarity rarity, colors.color color, cards.trade_param trade_param, cards.collector_nr FROM cards, sets, rarities, colors WHERE cards.setcode='%s' AND cards.setcode=sets.setcode AND cards.rarity=rarities.raritycode AND cards.color=colors.colorcode ORDER BY cardname LIMIT %d , %d",
     					mysqli_real_escape_string($db_connection, $set),
     					mysqli_real_escape_string($db_connection, $start),
     					mysqli_real_escape_string($db_connection, $offset));

	$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());
	$tot_pages = ceil($num_rows / $offset);

	$i = 0;
	$page = $start/$offset;
	$extraInfo = '';
	while ($i < $tot_pages) {
		if ($i == $page) {
			$extraInfo = 'set=' . $set . '&start=' . $i * $offset;
			$txt_overview .= '[' . ++$i . ']&nbsp;';
		}
		else $txt_overview .= '<a href="index.php?show&set=' . $set . '&start=' . $i++ * $offset . '">' . $i . '</a>&nbsp;';
	}
	$txt_overview .= '</h1>';
	echo $txt_overview;
	echo '<div class="entry">';
	echo '<p>';
	echo '<table>';
//echo '<table border="1" bordercolor="red">';
	echo '<tr><td class="bb2" colspan="3"></td></tr>';

	while ($row = mysqli_fetch_array($result)) {
		writeCardPic($row,$extraInfo); // ,$userIsLoggedIn,$userIsAdmin);
	}
   	echo '</table>';
		echo '<p>';
		echo '</div>';
   	echo $txt_overview;

} else if ( $what_to_show == 'prod' ) {

	if (isset($_REQUEST['start'])) $start = $_REQUEST['start'];
	else $start = 0;

	$offset = 20;

	$c_up = $row['category_up'];
	$txt_cat = $row['category_name'];
	$end = $row['end'];

	while ($c_up != 0) {
		$sql_a = "SELECT category_id, category_name, category_up FROM mtgcards_categories WHERE category_id = '$c_up'";
		$result_a = mysqli_query($db_connection, $sql_a); // or die (mysql_error() . ' # ' . $sql_a);
		$row_a = mysqli_fetch_array($result_a);
		$c_up = $row_a['category_up'];
		$c_up_nm = $row_a['category_name'];
		$c_id = $row_a['category_id'];
		$txt_cat = '<a href="index.php?show&prod=' . $c_id . '">' . $c_up_nm . '</a> - ' . $txt_cat;
	}

	$txt_overview = '<h1 class="title">' . $txt_cat;

	if ($end != 1) {
		// still categories to list under category
		$sql_x = "SELECT category_name, category_id, end FROM mtgcards_categories WHERE category_up='$prod' ORDER BY category_name";
		$result_x = mysqli_query($db_connection, $sql_x) or die ("Query [" . $sql_x . "] mislukt : " . mysqli_error($db_connection));

		if (mysqli_num_rows($result) > 0) {

			echo $txt_overview . '</h1>';

			echo '<div class="entry"><p><ul>';
			while ($row_x = mysqli_fetch_array($result_x)) {
				$cat_nm = $row_x['category_name'];
				$cat_id = $row_x['category_id'];
				$end = $row_x['end'];
				if ($end != 2) {
					echo '<li><a ';
					// if ($cat_id == $current_prod) echo 'class="current" ';
					// Warning: Undefined variable $current_prod in C:\xampp\htdocs\show.inc.php on line 116
					
					echo 'href="index.php?show&prod=' . $cat_id . '">' . $cat_nm . '</a></li>';
					echo "\n";
				}
			}
			echo '</ul></p></div>';
		}
	} else {

		$tag_text = '';
		// add tag filter if tags are avialable in product list !!
/*
		if (isset($_REQUEST['tag'])) {
			$tag = $_REQUEST['tag'];
			$tag_text = '&tag=' . $tag;
		}
		else {
			$tag = false;
			$tag_text = '';
		}
*/
		$sql = "SELECT count(*) total FROM mtgcards_products WHERE category = '$prod'";
	//	if ($tag) $sql .= " AND tag LIKE '" . $tag . "'";

		$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());
		//$num_rows = mysql_num_rows($result);
		
		if ($result) {
			$row = mysqli_fetch_array($result);		
			$num_rows = $row['total'];
			
			$sql = "SELECT * FROM mtgcards_products WHERE category = '$prod' ";
			// if ($tag) $sql .= " AND tag LIKE '" . $tag . "'";
			$sql .= " ORDER BY productname LIMIT $start, $offset";

			if ($result = mysqli_query($db_connection, $sql)) { // or die ("Query mislukt : " . mysql_error());
				$tot_pages = ceil($num_rows / $offset);

				$i = 0;
				$page = $start/$offset;
				$yes_include = false;
				if ($i+1 < $tot_pages) {
					$txt_overview .= ' &#171; ';
					$yes_include = true;
				}
				$extraInfo = 'prod=' . $prod . '&start=0' . $tag_text;
				if ($tot_pages > 1) {
					while ($i < $tot_pages) {
						if ($i == $page) {
							$extraInfo = 'prod=' . $prod . '&start=' . ($i * $offset) . $tag_text;
							$txt_overview .= '[' . ++$i . ']&nbsp;';
						}
						else $txt_overview .= '<a href="index.php?show&prod=' . $prod . '&start=' . ($i++ * $offset) . $tag_text . '">' . $i . '</a>&nbsp;';
					}
				}
				
				if ($yes_include) $txt_overview .= '&#187</h1>';
				echo $txt_overview;
				echo '<div class="entry"><p><table>';
				echo '<tr><td class="bb2" colspan="4">&nbsp;</td></tr>';
				while ($row = mysqli_fetch_array($result)) { 	
					writeProd($row,$extraInfo); // ,$userIsLoggedIn);
				}
				echo '</table></p></div>';
				if ($i > 1) echo $txt_overview;
			} else {
				echo '<h1 class="title">Nothing to display...</h1>';
				echo '<div class="entry"><p>¯\_(ツ)_/¯</p></div>';
			}
		}
	}


} else if ( $what_to_show == 'db_error' ) {

	echo '<h1 class="title">Something went wrong...</h1>';
	echo '<div class="entry"><p>¯\_(ツ)_/¯</p></div>';
	
} else {

	echo '<h1 class="title">Nothing to display...</h1>';
	echo '<div class="entry"><p>¯\_(ツ)_/¯</p></div>';
}

?>
