<?php

// STOCK FUNCTIONS

function stock_outofprint() {
	echo '<img src="style/stock_red.jpg" alt="No longer available - out of print">';
}

function stock_preorder() {
	echo '<img src="style/stock_blue.jpg" alt="Pre-Order">';
}

function stock_vendor() {
	echo '<img src="style/stock_yellow.jpg" alt="Needs to be ordered with our vendor(s)">';
}

function stock_limitedstock() {
	echo '<img src="style/stock_yellow_green.jpg" alt="Limited stock">';	
}

function stock_instock() {
	echo '<img src="style/stock_green.jpg" alt="In stock">';
}

?>