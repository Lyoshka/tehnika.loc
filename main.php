<?php
//*****************************************************************************
//  парсинг товаров сайта http://stl-partner.ru 
//  Для сайта ТвояТехника.рф
//*****************************************************************************



	set_time_limit(0);		//Убираем лимит работы скрипта PHP


	require_once dirname(__FILE__) . '/lib/proxy.php';
	require_once dirname(__FILE__) . '/lib/html.php';
	require_once dirname(__FILE__) . '/lib/simple_html_dom.php';
	require_once dirname(__FILE__) . '/lib/PHPExcel.php';

	
	$save_dir = getcwd() . '/image/catalog/';		// Директория для сохранения файлов
	$img_download = false;							// Скачивать картинки или нет		
	$k = 3;											// Индекс в массиве $arr_all по которому производимм выборку
	$site = 'http://stl-partner.ru';
	$objPHPExcel = new PHPExcel();

	$arr_all = array(
						array("59","Мойки","http://stl-partner.ru/index.php?route=product/category&path=592"),
						array("60","Смесители","http://stl-partner.ru/index.php?route=product/category&path=590"),
						array("61","Варочные поверхности","http://stl-partner.ru/index.php?route=product/category&path=619"),
						array("62","Духовые шкафы","http://stl-partner.ru/index.php?route=product/category&path=617"),
						array("63","Вытяжки","http://stl-partner.ru/index.php?route=product/category&path=641")
	);

	//Всавка HTML кода
    insert_html();

	
	
//***********************************************************************************************
// Загрузка всех каталогов
//***********************************************************************************************\

function load_all () {
	
	// Авторизация на сайте
	http_auth();
	
	global $arr_all;

	$str = "<input disabled='disabled' type = 'submit' name = 'button11' value = 'Скачать Excel файл'>";
	echo '<script>document.all.proc20.innerHTML = "' . $str . '"</script>';	
	flush();
	
	for ($i=0;$i<count($arr_all);$i++) {
		main ($i);
		sleep(1);
	}


	$str = "<input type = 'submit' name = 'button11' value = 'Скачать Excel файл'>";
	echo '<script>document.all.proc20.innerHTML = "' . $str . '"</script>';	

	flush();
	
}



//***********************************************************************************************
// Загрузка одного каталога
//***********************************************************************************************\

function load_catalog ($id_catalog) {
	
	// Авторизация на сайте
	http_auth();
	
	global $arr_all;

	$str = "<input disabled='disabled' type = 'submit' name = 'button11' value = 'Скачать Excel файл'>";
	echo '<script>document.all.proc20.innerHTML = "' . $str . '"</script>';	
	flush();
	
	
	main ($id_catalog);
	//sleep(5);
	
	$str = "<input type = 'submit' name = 'button11' value = 'Скачать Excel файл'>";
	echo '<script>document.all.proc20.innerHTML = "' . $str . '"</script>';	

	flush();
	
}


//***********************************************************************************************
// Загрузка указанного каталога	
//***********************************************************************************************

function main($load_catalog) {
		//ob_start();
		global $arr_all;
		global $objPHPExcel;
		
		$arr_1 = array();
		$arr_2 = array();
		$arr_tovar = array();
		$m = 0;	//счетчик для прогресс бара
		
		echo "Start load catalog: "  . $load_catalog . " " . date("H:i:s") . "<br>";
		ob_flush();
		flush();

		
		start_excel ();	// Подготовка Excel файла
		
		
		$arr_1 = get_page_count($arr_all[$load_catalog][0],$arr_all[$load_catalog][2]);

		// Первый цикл получаем массив ссылок на страницы КАТАЛОГА (ID каталога + ссылка страницу каталога) по одной категории из $arr_all
		
		for ($i=0;$i<count($arr_1);$i++) {
						
				//echo $arr_1[$i][0] . "   " . $arr_1[$i][1] ."<br>";		//ID каталога + ссылка страницу каталога
				
				$arr_2 = getItem($arr_1[$i][0],$arr_1[$i][1]);

				
				//Второй цикл получаем массив ссылок на страницы ТОРАРА (ID каталога + ID товара + ссылка на страницу товара) по одной категории из $arr_all
				for ($j=0;$j<count($arr_2);$j++) {
				
					$m = $m + 1;
					$d = $m + 1;
					
					$arr_tovar = get_tovar(html_entity_decode($arr_2[$j]['link']));
					
					save_img($arr_tovar['image']);		//Скачивает картинку товара
					
					$objPHPExcel->setActiveSheetIndex(0)
					
						->setCellValue('A'.$d, $arr_2[$j]['tovar_id'])
						->setCellValue('B'.$d, str_replace('&quot;','"',$arr_2[$j]['name']))
						->setCellValue('C'.$d, str_replace('&quot;','"',$arr_2[$j]['name']))
						->setCellValue('D'.$d, $arr_2[$j]['catalog_id'])
						->setCellValue('L'.$d, '1000')
						->setCellValue('M'.$d, $arr_2[$j]['tovar_id'])
						->setCellValue('N'.$d, $arr_tovar['brand'])
						->setCellValue('O'.$d, '/catalog/' . basename($arr_tovar['image']))
						->setCellValue('P'.$d, 'yes')
						->setCellValue('Q'.$d, $arr_2[$j]['price'])
						->setCellValue('S'.$d, date('Y-m-d H:i:s'))
						->setCellValue('T'.$d, date('Y-m-d H:i:s'))
						->setCellValue('U'.$d, date('Y-m-d'))
						->setCellValue('AB'.$d, 'true')
						->setCellValue('AB'.$d, 'true')
						->setCellValue('AC'.$d, '0')
						//->setCellValue('AE'.$d, $arr[$j]["memo1"])
						//->setCellValue('AF'.$d, $arr[$j]["memo1"])
						->setCellValue('AM'.$d, '7')
						->setCellValue('AN'.$d, '0')
						->setCellValue('AS'.$d, '0')
						->setCellValue('AT'.$d, 'true')
						->setCellValue('AU'.$d, '1');
					
					//echo $arr_2[$j]['catalog_id'] . " " . $arr_2[$j]['tovar_id'] . " " . $arr_2[$j]['price'] . " " . $arr_2[$j]['name'] . " " . $arr_tovar['brand'] . " " . $arr_2[$j]['link'] . "<br>"; // ID каталога + ID товара + ссылка на страницу товара
					
				}
				
				echo '<script>
					document.all.proc' . $load_catalog . '.innerHTML = "'. round((($i+1)*100/count($arr_1)),0)  .' % (' . $m . ')";
					document.all.line' . $load_catalog . '.innerHTML = "'.CopyLine(($i+1)*100/count($arr_1)).'";
					</script>';

	
		}
		
		
		end_excel ();	// Сохранение Excel файла

		echo "Stop load catalog: "  . $load_catalog . " " . date("H:i:s") . "<br>";
		ob_flush();
		flush();

		//Закрываем соеденение
		//curl_close($ch);
		
		ob_end_clean(); 
}
		
function CopyLine($num)
{
 flush();
 
    for($i = 1;$i<$num;$i++)
    {
        $tmp = $tmp ."|";
    }
    return $tmp;
}


//***********************************************************************************************
// Функция подготовки информации со страницы товара
//***********************************************************************************************	

function get_tovar ($url) {
	
		global $ch;
		global $site;
		
		$arr = array();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		$html = curl_exec($ch);
		
		$dom = str_get_html($html);
		
		
		if ($dom != null) {
			
			$container = $dom->find('.product-info .description', 0);
			
			$a = $container->find('a',0);
			
			$brand = $a->plaintext;		//Производитель
			
			$container = $dom->find('.product-info .image', 0);
			
			$a = $container->find('a',0);
			
			$image = $a->href;			//Картинка
			//echo $brand . "<br>";
			
			$arr['brand'] = $brand;
			$arr['image'] = $image;
			
		}
	
		return $arr;
}


	
//***********************************************************************************************
// Функция подготовки массива страниц с товарами по каталогу
//***********************************************************************************************	

function get_page_count($id_cat,$url){	
	
		global $ch;
		global $site;
		
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
		
		if ($img_download) {
		
			curl_setopt($ch, CURLOPT_URL, $img_url);
			
			$img_file = curl_exec($ch);

			file_put_contents($save_dir . basename($img_url), $img_file);
		}
		
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
		
		if ($dom != null) {
		
				$container = $dom->find('.grid_6');
				
				$i = 0;
				
					foreach($container as $item){
						
						$quantity = null;
						
						$a = $item->find('.name a',0);
						$str = $a->href;
						sscanf(html_entity_decode($str), 'http://stl-partner.ru/index.php?route=product/product&path=%d&product_id=%d',$cat_num,$product_id);
						
						$name = $a->plaintext;
						
						$a = $item->find('.price',0);
						$str_price = $a->plaintext;
						
						/*
						$a = $item->find('.stock',0);
						$str_count = $a->plaintext;
						sscanf(html_entity_decode($str_count), 'В наличии (%d)',$quantity);
						
						// Пропускаем товары которых нет
											
						if ($quantity == null) {
							//$product_id = null;
							$quantity = 0;
						} */
						
						sscanf(html_entity_decode($str_price), '%d руб.',$price);
						
						// Пропускаем товары с нулевой стоимостью
						if ($price == 0) {
							$product_id = null;
						}
						
						
						if ( $product_id != null ) {
											
							//echo $catalog_id . "   ";					
							$arr_tovar[$i]["catalog_id"] = $catalog_id;				//ID каталога

							//echo $item->attr['data-id'] . "   ";		
							$arr_tovar[$i]["tovar_id"] = $product_id;				//ID товара

							//echo $site . $a->href . "<br>";			
							$arr_tovar[$i]["link"] = $str;							// Ссылка на страницу товара
							
							$arr_tovar[$i]["price"] = $price;						// Цена товара
							
							$arr_tovar[$i]["name"] = $name;							// Наименование товара
							
							//$arr_tovar[$i]["quantity"] = $quantity;					// Количество товара
						
							$i = $i + 1;
						}
					}
		} else {
			echo "Сервер <b>" . $site . "</b> не отвечает :( <br>";
		}	
		return $arr_tovar;
		
}	

function start_excel () {
	
		global $objPHPExcel;

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Boy Gruv")
									 ->setLastModifiedBy("Boy Gruv")
									 ->setTitle("PHPExcel Document")
									 ->setSubject("PHPExcel Document")
									 ->setDescription("Document for PHPExcel, generated using PHP classes.")
									 ->setKeywords("office PHPExcel php")
									 ->setCategory("Result file");


								 
		// Заголовок страницы Products
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'product_id')
					->setCellValue('B1', 'name(en)')
					->setCellValue('C1', 'name(ru)')
					->setCellValue('D1', 'categories')
					->setCellValue('E1', 'sku')
					->setCellValue('F1', 'upc')
					->setCellValue('G1', 'ean')
					->setCellValue('H1', 'jan')
					->setCellValue('I1', 'isbn')
					->setCellValue('J1', 'mpn')
					->setCellValue('K1', 'location')
					->setCellValue('L1', 'quantity')
					->setCellValue('M1', 'model')
					->setCellValue('N1', 'manufacturer')
					->setCellValue('O1', 'image_name')
					->setCellValue('P1', 'shipping')
					->setCellValue('Q1', 'price')
					->setCellValue('R1', 'points')
					->setCellValue('S1', 'date_added')
					->setCellValue('T1', 'date_modified')
					->setCellValue('U1', 'date_available')
					->setCellValue('V1', 'weight')
					->setCellValue('W1', 'weight_unit')
					->setCellValue('X1', 'length')
					->setCellValue('Y1', 'width')
					->setCellValue('Z1', 'height')
					->setCellValue('AA1', 'length_unit')
					->setCellValue('AB1', 'status')
					->setCellValue('AC1', 'tax_class_id')
					->setCellValue('AD1', 'seo_keyword')
					->setCellValue('AE1', 'description(en)')
					->setCellValue('AF1', 'description(ru)')
					->setCellValue('AG1', 'meta_title(en)')
					->setCellValue('AH1', 'meta_title(ru)')
					->setCellValue('AI1', 'meta_description(en)')
					->setCellValue('AJ1', 'meta_description(ru)')
					->setCellValue('AK1', 'meta_keywords(en)')
					->setCellValue('AL1', 'meta_keywords(ru)')
					->setCellValue('AM1', 'stock_status_id')
					->setCellValue('AN1', 'store_ids')
					->setCellValue('AO1', 'layout')
					->setCellValue('AP1', 'related_ids')
					->setCellValue('AQ1', 'tags(en)')
					->setCellValue('AR1', 'tags(ru)')
					->setCellValue('AS1', 'sort_order')
					->setCellValue('AT1', 'subtract')
					->setCellValue('AU1', 'minimum');
	
	
}

function end_excel () {
	
		global $objPHPExcel;
	
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Products');

		//***********************************************************************************************************
		//Добавляем новую страницу
		$MyWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'AdditionalImages');
		$objPHPExcel->addSheet($MyWorkSheet,1);

		// Заголовок страницы AdditionalImages
		$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('A1', 'product_id')
					->setCellValue('B1', 'image')
					->setCellValue('C1', 'sort_order');
					
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Save Excel 2007 file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('Product_' . date('Y-m-d') . '.xlsx');
	
	
}



?>