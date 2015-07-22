<?php
include 'lib/include/html_head.inc.php'; //HTML-Header bis zur <body> Anweisungen
include 'lib/include/header.php';//Studip-Header, also die Navigationssymbole, die &uuml;ber fast jeder Seite stehen.
?>

<div class="container">
	<?php
	foreach($html as $day => $table) {
		print("<b>".$day."</b>");
		print $table;
		print("<br><br>");
	}
	?>
</div>

<?php
include('lib/include/html_end.inc.php');
?>