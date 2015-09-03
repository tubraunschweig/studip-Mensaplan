<div class="container">
	<?php
	foreach($html as $day => $table) {
		print("<b>".$day."</b>");
		print $table;
		print("<br><br>");
	}
	?>
</div>