<?php
/*
<h1 class="title">Welcome at mtgcards.be!</h1>
<div class="entry">
<p>Welcome at <strong>mtgcards.be</strong>, your online source for all possible items related to the best trading card game ever... "Magic: the Gathering".</p>
</div>
*/
function write_news ($row) {
	echo '<h1 class="title">&#171; ' . $row['title'] . ' &#187;</h1>';
	echo '<p class="byline"><small>Release: ' . $row['date'] . '</small></p>';
	echo '<div class="entry"><p>' . $row['text'] . '</p>';
	echo '</div>';
}
?>