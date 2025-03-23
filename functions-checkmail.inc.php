<?php

// CHECK MAIL FUNCTIONS

function checkmail($mail) 
{
	$valid = false;
  	if (eregi("^[0-9a-z]([-_.~]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$",$mail)) $valid = true; 
	return $valid; 
} 

?>