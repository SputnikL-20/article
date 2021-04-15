<?php
	require 'ReaderDir.php';
	require 'ParserDatas.php';
	require 'ExportDataSqlite.php';

	ini_set('max_execution_time', 0);
	//session_start();
	define('LINK', 'https://autotrade.su/moscow/find/');
	$start = microtime(true);
	echo " Parser to by started...".PHP_EOL;

$dir = new ReaderDir();
$dir->setCatalog(__DIR__.'/tmp/');
$dir->printData();
$dir->setData();
$article = $dir->getRecordered();
if (file_exists(__DIR__.'/src/recorded_article.json')) {
	$recorded_data = json_decode(file_get_contents(__DIR__.'/src/recorded_article.json'), true);
}
$parser = new ParserDatas();
$export = new ExportDataSqlite();
for ($i=0; $i < count($article); $i++) { 
	if ($data = $parser->getArticle(LINK.$article[$i])) {
		$recorded_data[] = $article[$i];
		for ($j=0; $j < count($data); $j++) { 
			$export->exportSqlite($data[$j]);
		}
		file_put_contents(__DIR__.'/src/recorded_article.json', json_encode($recorded_data));
	}
}




//print_r($_SESSION['article']);
//print_r($obj->getData());

	// $file = 'link.txt';
	// $f_hd = fopen($file, 'r');
	// $content = fread($f_hd, filesize($file));
	// print_r(explode('/', $content));
	// fclose($f_hd);
	// exit();

/*$db = new PDO('sqlite:logParser.sqlite');
// Делаем выборку данных:
$st = $db->prepare("SELECT article FROM parsing_data GROUP BY article"); 
//$st = $db->prepare("SELECT id FROM ostatki WHERE code=:art");
$st->execute();
//$st->execute(array('art' => $art));
$results = $st->fetchAll(PDO::FETCH_NUM);	
print_r($results);*/

	// $arr_res = ['https://autotrade.su/moscow/catalog/toyota/4runner/563/svecha-zazhiganiya', 
	// 			'https://autotrade.su/moscow/catalog/toyota/4runner/565/termostat', 
	// 			'https://autotrade.su/moscow/catalog/toyota/4runner/566/napravlyayuschaya-tormoznogo-supporta',
	// 			'https://autotrade.su/moscow/catalog/toyota/4runner/561/blok-cilindrov',
	// 			'https://autotrade.su/moscow/catalog/toyota/4runner/560/maslopriemnik'];
/*	$subject = 'https://autotrade.su/moscow';
	preg_match_all('#https://autotrade.su/moscow/find/([.A-Za-z0-9/-]+?)#sui', $subject, $matches);
	echo "<pre>";
	print_r($matches);
	echo "</pre>";*/
/*	for ($i=0; $i < $results; $i++) { 
		//echo $i." ";
		//linkWrite($arr_res[$i]);
		//echo $results[$i][0].PHP_EOL;
		//$subject = 'PU0-091000-00';
		preg_match_all('#([A-Za-z0-9-])+?#su', $results[$i][0], $matches);
		//implode("", $matches[1])
		//echo implode("", $matches[1]).PHP_EOL;
		startParsArticle("https://autotrade.su/moscow/find/".implode("", $matches[1]));
	}*/
    
//findArticle("https://autotrade.su/moscow/find/st9091706061");



   function exportCsv($data) 
    {
		$f_hd = date("d-m-Y"); // date("Y-m-d H:i:s");
	//	if (!fopen(__DIR__ . '/'.$f_hd.'.csv', 'a')) {
	// 	# code...
	//	}
        $buffer = fopen(__DIR__ . '/'.$f_hd.'.csv', 'a');
        //if (!is_file($f_hd)) {
        //	fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF)); // fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
        //}
        foreach($data as $val) {
            fputcsv($buffer, $val, ';'); // , ';'
        }
        fclose($buffer);
    }



function linkWrite($string)
{
	$file = 'link.txt';
	$f_hd = fopen($file, 'w');
	fwrite($f_hd, "$string"); // \r\n
	//fclose($f_hd);
}

		// $arr_csv[] = $INDEX_STR; // D
		// $arr_csv[] = "https://autotrade.su/moscow/autopart/".$autopart[1][$a]; // E
		// $arr_csv[] = ""; // F
		// $arr_csv[] = ""; // G

//$insert_db = $db->prepare("INSERT INTO 'ostatki' ('code', 'name', 'unit', 'quantity', 'brand') VALUES (:art, :name, :unit, :quantity, :brand)");
//$insert_db->execute(array('art' => $art,'name' => $name,'unit' => $unit,'quantity' => $quantity,'brand' => $brand));

    $finish = microtime(true);
	$delta = $finish - $start;
	echo PHP_EOL." Parser to by stoped...".PHP_EOL." Time: ".$delta . ' sec.';