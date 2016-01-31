<?php

function insert_html() {
		echo "<style>form{margin:0}td{border-bottom: 1px solid grey;}</style>";
		
		echo "<h2>Main Script</h2>";
	
		echo '<table>';
		echo '<tr><td style="border-style:hidden;width:280px;vertical-align:bottom">';
		echo '<form method = "post">';
		echo '<input type = "submit" name = "button10" value = "Load Catalog: Все каталоги">';
		echo '</td></tr></table>';

		echo '<br>';
		echo '*****************************************************************************************************<br>';
		echo '<br>';
		
		echo '<table>';
		echo '<tr><td style="border-style:hidden;width:280px;vertical-align:bottom">';
		echo '<form method = "post">';
		echo '<input type = "submit" name = "button0" value = "Load Catalog: Мойки">';
		echo '</td>';
		echo '<td width="320px">';
		echo '<div id="proc0">0</div>';
		echo '<table bgcolor=ffffff><tr><td bgcolor=3333ff><div style={color:3333ff} id="line0"></div></table>';
		echo '</td></tr></table>';

		echo '<br>';
		echo '<br>';

		echo '<table>';
		echo '<tr><td style="border-style:hidden;width:280px;vertical-align:bottom">';
		echo '<form method = "post">';
		echo '<input type = "submit" name = "button1" value = "Load Catalog: Смесители">';
		echo '</td>';
		echo '<td width="320px">';
		echo '<div id="proc1">0</div>';
		echo '<table bgcolor=ffffff><tr><td bgcolor=3333ff><div style={color:3333ff} id="line1"></div></table>';
		echo '</td></tr></table>';

		echo '<br>';
		echo '<br>';
		echo '<table>';
		echo '<tr><td style="border-style:hidden;width:280px;vertical-align:bottom">';
		echo '<form method = "post">';
		echo '<input type = "submit" name = "button2" value = "Load Catalog: Варочные поверхности">';
		echo '</td>';
		echo '<td width="320px">';
		echo '<div id="proc2">0</div>';
		echo '<table bgcolor=ffffff><tr><td bgcolor=3333ff><div style={color:3333ff} id="line2"></div></table>';
		echo '</td></tr></table>';

		echo '<br>';
		echo '<br>';
		echo '<table>';
		echo '<tr><td style="border-style:hidden;width:280px;vertical-align:bottom">';
		echo '<form method = "post">';
		echo '<input type = "submit" name = "button3" value = "Load Catalog: Духовые шкафы">';
		echo '</td>';
		echo '<td width="320px">';
		echo '<div id="proc3">0</div>';
		echo '<table bgcolor=ffffff><tr><td bgcolor=3333ff><div style={color:3333ff} id="line3"></div></table>';
		echo '</td></tr></table>';

		echo '<br>';
		echo '<br>';
		echo '<table>';
		echo '<tr><td style="border-style:hidden;width:280px;vertical-align:bottom">';
		echo '<form method = "post">';
		echo '<input type = "submit" name = "button4" value = "Load Catalog: Вытяжки">';
		echo '</td>';
		echo '<td width="320px">';
		echo '<div id="proc4">0</div>';
		echo '<table bgcolor=ffffff><tr><td bgcolor=3333ff><div style={color:3333ff} id="line4"></div></table>';
		echo '</td></tr></table>';

		echo '<br>';
		echo '<br>';
		
		if ( isset ( $_POST['button0'] )) {		
			ob_start();
			echo '<script>
			document.all.proc0.innerHTML = "'. main (0) .' ";
			</script>';
			ob_flush();
			flush();
	
		}
				
				
		if ( isset ( $_POST['button1'] )) {		
			ob_start();
			echo '<script>
			document.all.proc1.innerHTML = "'. main (1) .' ";
			</script>';
			ob_flush();
			flush();
	
		}		
		
		if ( isset ( $_POST['button2'] )) {		
			ob_start();
			echo '<script>
			document.all.proc2.innerHTML = "'. main (2) .' ";
			</script>';
			ob_flush();
			flush();
	
		}		
		
		if ( isset ( $_POST['button3'] )) {		
			ob_start();
			echo '<script>
			document.all.proc3.innerHTML = "'. main (3) .' ";
			</script>';
			ob_flush();
			flush();
	
		}		
		
		if ( isset ( $_POST['button4'] )) {		
			ob_start();
			echo '<script>
			document.all.proc4.innerHTML = "'. main (4) .' ";
			</script>';
			ob_flush();
			flush();
	
		}

		if ( isset ( $_POST['button10'] )) {		
			ob_start();
			echo '<script>
			document.all.proc10.innerHTML = "'. load_all () .' ";
			</script>';
			ob_flush();
			flush();
	
		}
}

?>