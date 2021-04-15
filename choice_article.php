<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__.'/logs/main_error.log');
date_default_timezone_set('Asia/Vladivostok');
session_start();

$start = microtime(true);
echo " Parser to by started...".PHP_EOL;

if (file_exists(__DIR__.'/src/session.json')) {
	$json = file_get_contents(__DIR__.'/src/session.json');
	$array = json_decode($json, true);
	$_SESSION = $array;
}

if (isset($_SESSION['marka'])) {
	parsingArticleResume();
} else {
	parsingArticle();
}

function parsingArticleResume() {
	$marka = $_SESSION['marka'];
	//echo 'Марка = '.$marka.PHP_EOL; // Марка

	$directoryName = './tmp/'.$marka;

	$model[1] = $_SESSION['model'];
	for ($k = $_SESSION['k']; $k < count($model[1]); $k++) { // count($model[1])
		$_SESSION['k'] = $k; 
		echo $k.' = '.$model[1][$k].PHP_EOL; // Модель
		if (!file_exists($directoryName.'/tmp_'.$model[1][$k].'_article.json')) 
		{		
			if (linkStatus('https://autotrade.su/moscow/catalog/'.$marka.'/'.$model[1][$k])) {

				$str_modifi = file_get_contents("https://autotrade.su/moscow/catalog/".$marka."/".$model[1][$k]);
				preg_match_all('#/moscow/catalog/'.$marka.'/'.$model[1][$k].'/(.+?)">#su', $str_modifi, $modifi); 

				//$modifi[1] = $_SESSION['modifi'];
				for ($j = $_SESSION['j']; $j < count($_SESSION['modifi']); $j++) { // count($modifi[1])
					$_SESSION['modifi'] = $modifi[1];
					$_SESSION['j'] = $j; 
					//echo $j.' = '.$_SESSION['modifi'][$j].PHP_EOL; // Модификация

					$str_catalog = file_get_contents('https://autotrade.su/moscow/catalog/'.$marka.'/'.$model[1][$k].'/'.$modifi[1][$j]);
					preg_match_all('#/moscow/catalog/'.$marka.'/'.$model[1][$k].'/'.$modifi[1][$j].'/([A-Za-z0-9-]+)#su', $str_catalog, $catalog);

					//$catalog[0] = $_SESSION['catalog'];
					for ($l = $_SESSION['l']; $l < count($_SESSION['catalog']); $l++) { 
						$_SESSION['catalog'] = $catalog[0];
						$_SESSION['l'] = $l; // echo $l.' = '.$catalog[0][$l].PHP_EOL;

						file_put_contents(__DIR__.'/src/session.json', json_encode($_SESSION));

						if (linkStatus('https://autotrade.su'.$_SESSION['catalog'][$l])) {

							$str_parts = file_get_contents('https://autotrade.su'.$_SESSION['catalog'][$l]);
							if (preg_match_all('#/moscow/autopart/([a-z0-9/-]+?)/([a-z0-9/-]+?)">#sui', $str_parts, $autopart)) {
								$_SESSION['article'][] = $autopart[2];
							}
						}
					}
					$_SESSION['l'] = 0;
				}
				$_SESSION['j'] = 0;
			}
			file_put_contents($directoryName.'/tmp_'.$model[1][$k].'_article.json', json_encode($_SESSION['article']));
			$_SESSION['article'] = null;
		} else {
			continue;
		}
	}
	//exportJson($_SESSION['article'], $marka);	
	parsingArticle();
}

function parsingArticle() 
{
	$str_marka = file_get_contents('https://autotrade.su/moscow');
	preg_match_all('#/moscow/catalog/(.+?)">#su', $str_marka, $marka);

	for ($i = 0; $i < count($marka[1]); $i++) 
	{ // count($marka[1])
		$_SESSION['marka'] = $marka[1][$i];
		// $_SESSION['i'] = $i;
		// echo $i.' = '.$marka[1][$i].PHP_EOL; // Марка

		$directoryName = './tmp/'.$marka[1][$i];
		if(!is_dir($directoryName)) {
		    //Directory does not exist, so lets create it.
		    mkdir($directoryName, 0755, true);
		}

		$str_model = file_get_contents('https://autotrade.su/moscow/catalog/'.$marka[1][$i]);
		preg_match_all('#/moscow/catalog/'.$marka[1][$i].'/(.+?)">#su', $str_model, $model);

		$_SESSION['model'] = $model[1];
		for ($k=0; $k < count($model[1]); $k++) 
		{ // count($model[1])
			$_SESSION['k'] = $k;
			echo $k.' = '.$model[1][$k].PHP_EOL; // Модель
			if (!file_exists($directoryName.'/tmp_'.$model[1][$k].'_article.json')) 
			{

				if(linkStatus('https://autotrade.su/moscow/catalog/'.$marka[1][$i].'/'.$model[1][$k])) 
				{

					$str_modifi = file_get_contents('https://autotrade.su/moscow/catalog/'.$marka[1][$i].'/'.$model[1][$k]);
					preg_match_all('#/moscow/catalog/'.$marka[1][$i].'/'.$model[1][$k].'/(.+?)">#su', $str_modifi, $modifi); 
					//print_r($modifi);
					$_SESSION['modifi'] = $modifi[1];
					for ($j=0; $j < count($modifi[1]); $j++) 
					{ // count($modifi[1])
						$_SESSION['j'] = $j;

						// echo $j.' = '.$modifi[1][$j].PHP_EOL; // Модификация
						$str_catalog = file_get_contents('https://autotrade.su/moscow/catalog/'.$marka[1][$i].'/'.$model[1][$k].'/'.$modifi[1][$j]);
						preg_match_all('#/moscow/catalog/'.$marka[1][$i].'/'.$model[1][$k].'/'.$modifi[1][$j].'/([A-Za-z0-9-]+)#su', $str_catalog, $catalog);

						$_SESSION['catalog'] = $catalog[0];
						for ($l=0; $l < count($catalog[0]); $l++) 
						{ 
							$_SESSION['l'] = $l;
							// echo $l.' = '.$catalog[0][$l].PHP_EOL;

							file_put_contents(__DIR__.'/src/session.json', json_encode($_SESSION));

							if(linkStatus('https://autotrade.su'.$catalog[0][$l])) {
								//$_SESSION['link'] = 'https://autotrade.su'.$catalog[0][$l];
								//echo $_SESSION['l']." ".$_SESSION['link'].PHP_EOL;

								$str_parts = file_get_contents('https://autotrade.su'.$catalog[0][$l]);
								if (preg_match_all('#/moscow/autopart/([a-z0-9/-]+?)/([a-z0-9/-]+?)">#sui', $str_parts, $autopart))
								{
									$_SESSION['article'][] = $autopart[2];
								}
							}
						}
					}
				}
				file_put_contents($directoryName.'/tmp_'.$model[1][$k].'_article.json', json_encode($_SESSION['article']));
				$_SESSION['article'] = null;
			} else {
				continue;
			}	
		}
	}
}

function exportJson($value = 'null', $name = 'null')
{
	file_put_contents(__DIR__.'/'.$name.'_article.json', json_encode($value));
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


$finish = microtime(true);
$delta = $finish - $start;
echo PHP_EOL." Parser to by stoped...".PHP_EOL." Time: ".$delta . ' sec.';