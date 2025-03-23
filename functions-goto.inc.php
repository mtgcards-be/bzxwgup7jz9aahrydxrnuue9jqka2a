<?php

// GOTO FUNCTION

function goto_path($path) {
    echo '<script language="javascript">window.location="'.$path.'";</script>';
}

function goto_index() {
	goto_path("index.php");
}

?>