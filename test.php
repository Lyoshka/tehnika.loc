<?php


//phpinfo();
set_time_limit(0);		//Убираем лимит работы скрипта PHP

echo '<div id="proc20"></div>';

header('X-Accel-Buffering: no');
ini_set('output_buffering', 'Off');
ini_set('output_handler', '');
ini_set('zlib.output_handler','');
ini_set('zlib.output_compression', 'Off');
# ini_set('implicit_flush', 'On');
while (ob_get_level()) { ob_end_flush(); }

ob_start();

for($i=0;$i<60;$i++) {
	
	//echo $i . "<br>";
	ob_flush();
	flush();
	
	
	echo '<script>
			document.all.proc20.innerHTML = "'. ($i+1)*10 .'  sec";
			</script>';
	
	
	sleep(10);
	
}

?> 