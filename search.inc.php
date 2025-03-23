<?php
include_once 'functions-cards.inc.php';

if (isset($_REQUEST['find'])) {
    // Sanitize input
    $find = trim($_REQUEST['find']);

	
	// This would be the future way to search
	$escaped_find = mysqli_real_escape_string($db_connection, $find); // Don't replace apostrophes
	$display_find = htmlentities($find);

    // Initialize pagination
    $start = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
    $offset = 20;
    
    // Build overview title
    $txt_overview = '<h1 class="title">Results for "' . $display_find . '"';
    
    // Get total count
    $sql = sprintf("SELECT count(*) total FROM cards WHERE cardname LIKE '%%%s%%'", $escaped_find);
    $result = mysqli_query($db_connection, $sql);
    $row = mysqli_fetch_array($result);
    $num_rows = $row['total'];
    
    if ($num_rows < 300) {
        // Add result count to title
        $txt_overview .= " (" . $num_rows . " result" . ($num_rows != 1 ? "s" : "") . ")";
        $extraInfo = 'find=' . urlencode($find);
        
        if ($num_rows == 0) {
            echo '<h1 class="title">No results for this query...</h1>';
        } else {
            echo "\n";
            
            // Main query
            $sql = sprintf("SELECT 
                    cards.trade_param trade_param, 
                    cards.price_change price_change, 
                    cards.diff diff, 
                    cards.id id, 
                    cards.setcode setcode, 
                    cards.cardname cardname, 
                    cards.price price, 
                    collector_nr, 
                    cards.quantity quantity,
                    (SELECT sets.setname FROM sets WHERE cards.setcode = sets.setcode) setname, 
                    (SELECT rarities.rarity FROM rarities WHERE cards.rarity = rarities.raritycode) rarity, 
                    cards.rarity r, 
                    (SELECT colors.color FROM colors WHERE cards.color = colors.colorcode) color
                FROM cards
                WHERE cards.cardname LIKE '%%%s%%'
                ORDER BY cardname, setname
                LIMIT %d, %d", 
                $escaped_find,
                $start,
                $offset);
            
            $result = mysqli_query($db_connection, $sql);
            
            // Pagination
            $tot_pages = ceil($num_rows / $offset);
            if ($tot_pages > 1) {    
                $txt_overview .= ' - ';
                $i = 0;
                $page = $start / $offset;
                while ($i < $tot_pages) {
                    $pageExtraInfo = 'find=' . urlencode($find) . '&start=' . ($i * $offset);
                    if ($i == $page) {
                        $txt_overview .= '[' . ++$i . ']&nbsp;';
                    } else {
                        $txt_overview .= '<a href="index.php?search&' . $pageExtraInfo . '">' . ++$i . '</a>&nbsp;';
                    }
                }
            }
            $txt_overview .= '</h1>';
            echo $txt_overview;
            
            // Display results
            echo '<div class="entry"><p><table>';
            echo '<tr><td class="bb2" colspan="4">&nbsp;</td></tr>';
            
            $extraInfo = 'find=' . urlencode($find) . '&start=' . $start;
            while ($row = mysqli_fetch_array($result)) {
                writeCardPic($row, $extraInfo);
            }
            echo '</table></p></div>';
            echo $txt_overview;
        }
    } else {
        echo '<h1 class="title">"' . htmlentities($find) . '" is not specific enough, please use another criteria.</h1>';
    }
}
?>