<?php

/*
Nog te regelen :
	
	FIN		8		8
	DNK		7		7
	USA		3		3
	GBR		3		3
	NOR		2		2
	CAN		1		1
	BHR		1		1
	EST		1		1
	SVN		1		1

*/

function is_bel_eu_or_row_for_products($country_code) {
    switch ($country_code) {
		// Belgium
		case 'BEL': return 'BEL'; 
		// Europe
        case 'DEU': return 'DEU'; // Duitsland
        case 'FRA': return 'FRA'; // Frankrijk
        case 'LUX': return 'LUX'; // Groothertogdom Luxemburg
        case 'NLD': return 'NLD'; // Nederland
		case 'ESP': return 'ESP'; // Spanje
		case 'PRT': return 'PRT'; // Portugal
		case 'ITA': return 'ITA'; // Italië
		case 'POL': return 'POL'; // Polen
		case 'AUT': return 'AUT'; // Oostenrijk
		case 'JPN': return 'JPN'; // Japan
        // Rest of World
        default: return 'ROW';
    }
}

// for cards only
function is_bel_eu_or_row($country_code) {
    switch ($country_code) {
		// Belgium
		case 'BEL': return 'BEL'; 
		// Europe
        case 'ALB': return 'EU'; // Albanië
        case 'AND': return 'EU'; // Andorra
        case 'BIH': return 'EU'; // Bosnië-Herzegovina
        case 'BGR': return 'EU'; // Bulgarije
        case 'CYP': return 'EU'; // Cyprus
        case 'DNK': return 'EU'; // Denemarken
        case 'DEU': return 'EU'; // Duitsland
        case 'EST': return 'EU'; // Estland
        case 'FIN': return 'EU'; // Finland
        case 'FRA': return 'EU'; // Frankrijk
        case 'GEO': return 'EU'; // Georgië
        case 'GIB': return 'EU'; // Gibraltar
        case 'GRC': return 'EU'; // Griekenland
        case 'GRL': return 'EU'; // Groenland
        case 'HUN': return 'EU'; // Hongarije
        case 'IRL': return 'EU'; // Ierland
        case 'ISL': return 'EU'; // IJsland
        case 'ITA': return 'EU'; // Italië
        case 'LVA': return 'EU'; // Letland
        case 'LIE': return 'EU'; // Liechtenstein
        case 'LTU': return 'EU'; // Litouwen
        case 'LUX': return 'EU'; // Groothertogdom Luxemburg
        case 'MKD': return 'EU'; // Macedonië
        case 'MLT': return 'EU'; // Malta
        case 'MDA': return 'EU'; // Moldavië
        case 'MCO': return 'EU'; // Monaco
        case 'MNE': return 'EU'; // Montenegro
        case 'NLD': return 'EU'; // Nederland
        case 'NOR': return 'EU'; // Noorwegen
        case 'AUT': return 'EU'; // Oostenrijk
        case 'POL': return 'EU'; // Polen
        case 'PRT': return 'EU'; // Portugal
        case 'ROU': return 'EU'; // Roemenië
        case 'RUS': return 'EU'; // Rusland
        case 'SMR': return 'EU'; // San Marino
        case 'SRB': return 'EU'; // Servië
        case 'SVK': return 'EU'; // Slowakije
        case 'SVN': return 'EU'; // Slovenië
        case 'ESP': return 'EU'; // Spanje
        case 'CZE': return 'EU'; // Tsjechische Republiek
        case 'TUR': return 'EU'; // Turkije
        case 'VAT': return 'EU'; // Vaticaanstad
        case 'GBR': return 'EU'; // Verenigd Koninkrijk
        case 'BLR': return 'EU'; // Wit-Rusland
        case 'SWE': return 'EU'; // Zweden
        case 'CHE': return 'EU'; // Zwitserland
        // Rest of World
        default: return 'ROW';
    }
}

function calculate_shipping_cost($conn, $shipping_option_code) {

	$sql = sprintf("SELECT cost FROM shipping_costs WHERE shipping_option_code = '%s' LIMIT 1", mysqli_real_escape_string($conn, $shipping_option_code));

    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
      return $row['cost'];
    } else {
      return 0; // Or handle the error as you see fit.
    }
}

function translateShippingCode($sh) {
	
	// Translation available in functions-write.inc.php :: function sh_full($sh) {
	
    if (strpos($sh, "ENV") !== false) {
        return "ENV";
    } elseif (strpos($sh, "COLLECT") !== false) {
        return "COL";
    } elseif (strpos($sh, "PARCEL") !== false) {
        return "PAR";
    } elseif (strpos($sh, "MONDIAL") !== false) {
        return "MON";
	} elseif (strpos($sh, "BPACK") !== false) {
		return "BPK";
	} elseif (strpos($sh, "MINI") !== false) {
		return "MIN";
    } else {
		return "000";
    }
}


?>