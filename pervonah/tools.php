<?php
/* 
	These functions were written with the intention to make code look a bit more clean
*/


// Read a local file
function fir($file){
	$fo = fopen($file, "r");
	$data = @fread($fo, filesize($file));
	fclose($fo);
	return $data;
}

// Write to local file
function fiw($file,$data){
	$fd = @fopen($file, 'w');
	fwrite($fd, $data);
	fclose($fd);
}

// Flush (output the data despite a script hasn't finished working yet)
function flsh (){
	echo(str_repeat(' ',256));
	if (ob_get_length()){           
		@ob_flush();
		@flush();
		@ob_end_flush();
	}   
	@ob_start();
}

// A beautiful way to echo big amounts of data
function da($str,$label=false){
	echo "\n<br />";
	if($label!=false) echo $label."<br />";
	echo "<textarea cols='100' rows='30'>";
	print_r($str);
	echo "</textarea><br />\n";
	flsh();
}

?>