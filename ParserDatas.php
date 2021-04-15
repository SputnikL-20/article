<?php
ini_set('max_execution_time', 0);
/**
 * 
 */
// https://autotrade.su/moscow/find/st1638
class ParserDatas 
{
	private $article = [];

	public function getArticle($url_find = null) 
	{	
		unset($this->article);
		if ($str = $this->sendUrl($url_find)) {
			if (preg_match_all('#/moscow/autopart/([a-z0-9-]+?)/([a-z0-9-]+?)">#su', $str, $autopart) != 0) {
				for ($i=0;$i < count($autopart[2]);$i++) { 
					if ($autopart[2][0] == $autopart[2][$i]) {
						$this->article[] = $this->findArticle($autopart[1][$i].'/'.$autopart[2][$i]);
					} else {
						break;
					}
				}
				return $this->article;
			}
		}	
	}

	public function findArticle($url_end = null) 
	{	
		if ($str_autopart = $this->sendUrl('https://autotrade.su/moscow/autopart/'.$url_end)) 
		{
			$find['link'] = "https://autotrade.su/moscow/autopart/".$url_end;
			
			$pattern = '#<a[^>]*?\s*?href="/moscow/autopart/'.$url_end.'">(.+?)</a>#sui'; 
			if (preg_match_all($pattern, $str_autopart, $naimenovanie) != 0) {		
				do {
					$find['nomenclature'] = trim(strip_tags($naimenovanie[1][1])); // A Наименование  
				} while (trim(strip_tags($naimenovanie[1][1])) == null);
			}

			$patt_article = '#<div[^>]*?><b[^>]*?>[Артикул:]+\s*?</b>(.+?)</div>#su';
			if (preg_match_all($patt_article, $str_autopart, $article) != 0) {
				do {
					$find['article'] = trim(strip_tags($article[1][0])); // B Каталожный номер
				} while (trim(strip_tags($article[1][0])) == null);
			}

			$patt_brand = '#<div[^>]*?><b[^>]*?>[Бренд:]+\s*?</b>(.+?)</div>#su';
			if (preg_match_all($patt_brand, $str_autopart, $brand)) {
				do {
					$find['brand'] = trim(strip_tags($brand[1][0])); // C Брэнд
				} while (trim(strip_tags($brand[1][0])) == null);
			}

			$patt_country = '#<div[^>]*?><b[^>]*?>[Страна:]+\s*?</b>(.+?)</div>#su';
			if (preg_match_all($patt_country, $str_autopart, $country)) {
				do {
					$find['country'] = trim(strip_tags($country[1][0])); // Страна
				} while (trim(strip_tags($country[1][0])) == null);
			}

			$patt_price = '#<span[^>]*?>([0-9]+?)</span>#su'; 
			if (preg_match_all($patt_price, $str_autopart, $price)) {
				do {
					$find['price'] = trim(strip_tags($price[1][0])); // Цена
				} while (trim(strip_tags($price[1][0])) == null);
			}

			$patt_count = '#<i[^>]*?></i>[В\sналичии]+\s([0-9].+?)#su';
			if (preg_match_all($patt_count, $str_autopart, $count)) {
				do {
					$find['count'] = trim(strip_tags($count[1][0])); // Количество
				} while (trim(strip_tags($count[1][0])) == null);
			} else {
				$patt_order = '#<span[^>]*?>([А-яа-я0-9].+?)</span>#su'; // (?(?=[a-z])[^ite]|^$)
				if (preg_match_all($patt_order, $str_autopart, $order)) {
						$find['order'] = trim(strip_tags($order[0][2])); // Под заказ
				}			
			}

			$patt_notesth = '#<th[^>]*>(.+?)</th>#su';
			if (preg_match_all($patt_notesth, $str_autopart, $notesth) != 0) {
				$patt_notestd = '#<td[^>]*>(.+?)</td>#su';
				preg_match_all($patt_notestd, $str_autopart, $notestd);
				for ($i=0; $i < count($notesth[0]); $i++) { 
					$temp_note[] = strip_tags($notesth[0][$i]).': '.strip_tags($notestd[0][$i]);
				}
				$find['notes'] = implode('; ', $temp_note);
				unset($temp_note);
			}
				
			$patt_src = '#<img src="https://static.autotrade.su/nomenclature/wm/(.+?)"#is';
			if (preg_match_all($patt_src, $str_autopart, $img_src) != 0) { // I Фотографии
				if (count($img_src[1]) == 1) {
					$find['images'] = "https://static.autotrade.su/nomenclature/wm/".$img_src[1][0];
				} else {
					do {
						for ($j=1; $j < count($img_src[1]); $j++) { 
							$temp_img_src[] = "https://static.autotrade.su/nomenclature/wm/".$img_src[1][$j];
						}
						$find['images'] = implode(', ', $temp_img_src);
						unset($temp_img_src);
					} while ($img_src[1] == null);
				}
			}
	        return $find;
		}
	}


	public function sendUrl($url) 
	{
	    if (!filter_var($url, FILTER_VALIDATE_URL)) {
	        return false;
	    }
	    if (function_exists('curl_init')) {
	    	//echo "Существует!";
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        $response = curl_exec($ch);
	        curl_close($ch);
	        if ($response) {
	            return $response;
	        } else {
	            return false;
	        }
	    } else {
	        $content = file_get_contents($url);
	        if ($content === false) {
	            return false;
	        } else {
	            return $content;
	        }
	    }
	}


function linkStatus($url = null) {
	try {
		$status = get_headers($url); 
	    if(in_array("HTTP/1.1 200 OK", $status) or in_array("HTTP/1.0 200 OK", $status)){
	        return true;
	    } else {
	        //Генерируем исключение.
	        $log = date('Y-m-d H:i:s');
	        throw new Exception($log.' URL не доступен по адресу: '.$url);
	        return false;
	    }
	} catch (Exception $ex) {
	    //Выводим сообщение об исключении.
	    $str =  $ex->getMessage();
	    $buffer = fopen(__DIR__ .'/logs/exception.log', 'a'); 
	    fwrite($buffer, $str."\n");
	    fclose($buffer);
	}
}	

}