<?php

include_once "functions.inc.php";

if (isset($_REQUEST['order'])) $order = $_REQUEST['order'];
else $order = "";

?>

<h1 class="title">Thank you for your payment</h1>
<div>
<p>
<?php

if ($order != "") echo 'Your payment for mtgcards.be order #<strong>' . order_full($order). '</strong> has been processed.';
else echo 'Your payment has been processed.';

?>

<br/><i>You may close this window.</i></p>
</div>