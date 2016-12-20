<?php
$handle = fopen("E:/WebSim_worldQuant/Alpha/inputFunda2.txt", "r");
$handle1= fopen("E:/WebSim_worldQuant/Alpha/inputFunda1.txt", "r");
$writer = fopen("14122016.txt",  "w");
	
$a=array();
$b=array();
while (($line = fgets($handle)) !== false) {
        array_push($a,$line);
		
    }
while (($line1 = fgets($handle1)) !== false) {
		array_push($b,$line1);
		
    }
	foreach ($a as $count)
		foreach ($b as $count1)
	{
		$var1= str_replace("\r\n","", $count);
		$var2= str_replace("\r\n","", $count1);
		if($var1==$var2)
			continue;
		$key=rand(2,10);
		$index1= ($key/1000);
		$index2=rand(20,40);
		$string=$var1."/".$var2;	
		
		fwrite($writer,$string."\n");
		
	}
	
fclose($handle);
fclose($writer);
fclose($handle1);
?>
