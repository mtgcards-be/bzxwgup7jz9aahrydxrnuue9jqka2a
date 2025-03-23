<p>


<table>
	<tr>

</p>

<table cellspacing="0" cellpadding="0">
		<tr>
			<td rowspan="20" width="150">
				<center>
				<i>
				<b>Want to play?</b>
				<small>
				<br/>Join us during one of our 
				<br/>Friday MTG Game Nights
				<br/><b>Start Hour = 20h00</b>
				</small>
				</i>
				</center>
			</td>
			<th><center>Friday</center></th>
			<th><center>Event</center></th>
			<th><center>Price</center></th>
		</tr>
		<tr><th colspan = "3"><hr></th></tr>

<?php

	$info = 'info_calendar.txt';
	$lines = file($info);
	// $x = true;
	foreach ($lines as $line_num => $line) {
			$txt = explode("|", $line);
					
			echo '<tr';
		 	// if ($x) echo ' class="kleurbalk"';
			echo '><td><center>';
			echo $txt[0];
			echo '</center></td><td><center>' . $txt[1];
			if ($txt[2] != '') echo ' <a target="_blank" href="https://www.facebook.com/events/' . $txt[2] . '"><img src="img/fb_icon.png" style="width:12px; height:12px;"/></a>';

			echo '</center></td><td><center>';
			echo $txt[3];
			echo '</center></td></tr>';
			// $x = !$x;



	}
?>
</table>
</p>