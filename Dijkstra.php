<?php
$writer = fopen("demo.txt",  "w");
	
for($i=0;$i<1000;$i++)
		{
		$string="array(".$i.",".($i+1).",".rand(1,500).")"	;
		fwrite($writer,$string.","."\n");
		}


	

fclose($writer);
?>
