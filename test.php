<?php

ob_implicit_flush(1);
for($s='',$x=0;$x<3;$x++) {
ob_start();
echo 'data '.$i;
$s = ob_get_contents();
ob_end_flush();
ob_end_clean();
sleep(1);
}



?> 