<?php 

$file = 'Product_' . date('Y-m-d') . '.xlsx';

if(is_file($_GET['file']) && $_GET['file']=='Product_' . date('Y-m-d') . '.xlsx') { 
    header("Content-Type: application/force-download; name=\"".$_GET['file']."\""); 
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Length: ". filesize($_GET['file'])); 
    header("Content-Disposition: attachment; filename=\"".$_GET['file']."\""); 
    readfile($_GET['file']); 
    header("Expires: 0"); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Pragma: no-cache"); 
   exit();
} 

$str = "<a href='?file=" . $file . "'>Скачать EXCEL FILE</a>";
		

echo $str;

?>
