<?php

$ruas = array();

for($t = 1; $t < 10; $t++){
	
	$file = fopen("$t.csv","r");
	while(!feof($file))
	{
		$data = fgetcsv($file,'','"');
		$ruas[$t][] = $data[0];
	}
	fclose($file);
}

for($t = 1; $t < 10; $t++){
	for($h = 1; $h < 10; $h++){
		$inter = count(array_intersect($ruas[$t],$ruas[$h]));
		echo "$t,$h,$inter\n";
	}
}

#var_dump($ruas[2]);
