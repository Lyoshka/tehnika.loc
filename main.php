<?php

	require_once dirname(__FILE__) . '/lib/proxy.php';
	require_once dirname(__FILE__) . '/lib/simple_html_dom.php';

	
	$save_dir = getcwd() . '/image/catalog/';		//Директория для сохранения файлов
	$img_download = true;							// Скачивать картинки или нет		
	$k = 3;											// Индекс в массиве $arr_all по которому производимм выборку

	$arr_all = array(
						array("59","Мойки","http://stl-partner.ru/index.php?route=product/category&path=592"),
						array("60","Смесители","http://stl-partner.ru/index.php?route=product/category&path=590"),
						array("61","Варочные поверхности","http://stl-partner.ru/index.php?route=product/category&path=619"),
						array("62","Духовые шкафы","http://stl-partner.ru/index.php?route=product/category&path=617"),
						array("63","Вытяжки","http://stl-partner.ru/index.php?route=product/category&path=641")
	);


		$arr_1 = array();
		$arr_2 = array();
				
		$arr_1 = get_page_count($arr_all[$k][0],$arr_all[$k][2]);

		// Первый цикл получаем массив ссылок на страницы КАТАЛОГА (ID каталога + ссылка страницу каталога) по одной категории из $arr_all
		
		for ($i=0;$i<count($arr_1);$i++) {
				
				//echo $arr_1[$i][0] . "   " . $arr_1[$i][1] ."<br>";		//ID каталога + ссылка страницу каталога
				
				$arr_2 = getItem($arr_1[$i][0],$arr_1[$i][1]);

				
				//Второй цикл получаем массив ссылок на страницы ТОРАРА (ID каталога + ID товара + ссылка на страницу товара) по одной категории из $arr_all
				for ($j=0;$j<count($arr_2);$j++) {
					
					echo $arr_2[$j]['catalog_id'] . " " . $arr_2[$j]['tovar_id'] . " " . $arr_2[$j]['price'] . " ". $arr_2[$j]['link'] ."<br>"; // ID каталога + ID товара + ссылка на страницу товара
						
				}
			
		}
		
		//var_dump($arr);
				

		//Закрываем соеденение
		curl_close($ch);
	
	
//***********************************************************************************************
// Функция подготовки массива страниц с товарами по каталогу
//***********************************************************************************************	

function get_page_count($id_cat,$url){	
	
		global $ch;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		$html = curl_exec($ch);
		
		$arr_page = array();
		
		$arr_page[0][0] = $id_cat;
		$arr_page[0][1] = $url;
		
		$dom = str_get_html($html);
		
	if ($dom != null) {
		
		$pages = $dom->find('.pagination .links',0);
		
		$a = $pages->find('a');
		
		foreach($a as $item){
			$str = trim($item->href);
		}

		sscanf(html_entity_decode($str), 'http://stl-partner.ru/index.php?route=product/category&path=%d&page=%d',$cat_num,$page_count);

		$j = 1;			
		for ($i=2;$i<=$page_count;$i++) {
			
			$arr_page[$j][0] = $id_cat;
			$arr_page[$j][1] = $url . "&page=" . $i;
			
			$j = $j + 1;
			
		}

		
		
	} else {	
		echo "Function: get_page_count(). Нет данных HTML на входе. DOM = NULL <br>";
	}
	
		return $arr_page;
	
}
//***********************************************************************************************


//*******************************************************************************************************
//Функция скачивания файла изображения
//*******************************************************************************************************		

function save_img ($img_url) {		
		
		global $save_dir;
		global $ch;
		
		curl_setopt($ch, CURLOPT_URL, $img_url);
		
		$img_file = curl_exec($ch);

		file_put_contents($save_dir . basename($img_url), $img_file);
		
}
//***********************************************************************************************


//*******************************************************************************************************
// Функция сбора ID товара по указанному каталогу (цикл по всем страницам каталога)
//*******************************************************************************************************		

function list_item($catalog) {		
		
		global $host;
		global $user;
		global $password;
		global $database;
		global $limit;
		
		$arr = array();


		// подключаемся к SQL серверу
		$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
	
			$query = 'select link, parent_id from bitrixshop.load_catalog where id_cat = "' . $catalog . '"' . $limit; //Не забыть убрать ЛИМИТ
			$result = mysqli_query($link, $query);
			
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				
				//echo $row['s3'] . "<br>";
				$arr_art = getItem($row['link'],$catalog);
				$parent_id = $row['parent_id'];
								
				for($j=0;$j<count($arr_art);$j++) {
					
					$s1 = $arr_art[$j]["tovar_id"];
					$s2 = $arr_art[$j]["catalog_id"];
					$s3 = $arr_art[$j]["link"];
					$s4 = $parent_id;
					

				
					$arr[] = array("tovar_id" 		=> $s1,
									"catalog_id" 	=> $s2,
									"link" 			=> $s3,
									"parent_id" 	=> $s4);
					
				}
				
				
			}

		//Закрываем соединение с БД 
		mysqli_close($link);
		
		
		
		return $arr;
}		
//***********************************************************************************************


//*******************************************************************************************************
// Доп. функция для list_item() сбора ID товара с одной страницы. 
//*******************************************************************************************************		
		
function getItem($catalog_id = 0, $url) {	
	
		global $site;
		global $ch;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		$html = curl_exec($ch);

		$arr_tovar = array();
		
		$dom = str_get_html($html);
		
		$container = $dom->find('.grid_6');
		
		$i = 0;
		
			foreach($container as $item){
				
				$a = $item->find('.name a',0);
				$str = $a->href;
				sscanf(html_entity_decode($str), 'http://stl-partner.ru/index.php?route=product/product&path=%d&product_id=%d',$cat_num,$product_id);
				
				$a = $item->find('.price',0);
				$str_price = $a->plaintext;
				
				sscanf(html_entity_decode($str_price), '%d руб.',$price);
				
				if ( $product_id != null ) {
									
					//echo $catalog_id . "   ";					
					$arr_tovar[$i]["catalog_id"] = $catalog_id;				//ID каталога

					//echo $item->attr['data-id'] . "   ";		
					$arr_tovar[$i]["tovar_id"] = $product_id;				//ID товара

					//echo $site . $a->href . "<br>";			
					$arr_tovar[$i]["link"] = $str;							// Ссылка на страницу товара
					
					$arr_tovar[$i]["price"] = $price;							// Цена товара
				
					$i = $i + 1;
				}
			}
			
		return $arr_tovar;
		
}	


?>