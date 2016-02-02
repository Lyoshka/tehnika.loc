<?php


echo str_pad('',1024);
@ob_flush();
flush(); 



for ($i=1; $i<5; $i++) {
echo $i.') Delay 2 sec';
flush();
sleep(2);
}

?> 