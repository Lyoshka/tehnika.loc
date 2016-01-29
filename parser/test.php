<div id="proc">0</div>
<table bgcolor=ffffff><tr><td bgcolor=3333ff><div style={color:3333ff} id="line"></div></table>
<?
function CopyLine($num)
{
 flush();
 
    for($i = 1;$i<$num;$i++)
    {
        $tmp = $tmp ."|";
    }
    return $tmp;
}

for($i = 0; $i < 1001; $i++)  
{
    echo '<script>
    document.all.proc.innerHTML = "'.($i/10).' %";
    document.all.line.innerHTML = "'.CopyLine($i/10).'";
    </script>';
	usleep(10000); 
    flush();
}

echo '<script>document.all.proc.innerHTML = "Completed!";</script>';
?>  