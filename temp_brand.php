<?php
	require 'ParserDatas.php';
	require 'ExportDataSqlite.php';

	$array = ['https://autotrade.su/moscow/find/k1632528010', 
			  'https://autotrade.su/moscow/find/st1640toyo', 
			  'https://autotrade.su/moscow/find/vsy10fdrh',
			  'https://autotrade.su/moscow/find/st1638'];
	$parser = new ParserDatas();
	$connect = new ExportDataSqlite();
	$connect->connectDataBase();
	$connect->createTable();
	for ($j=0; $j < count($array); $j++) { 
		if($data = $parser->getArticle($array[$j])) {
			for ($i=0; $i < count($data); $i++) { 
				$connect->exportSqlite($data[$i]);
			}
		}
	}
	

	

	// https://autotrade.su/moscow/find/st1640toyo
	// https://autotrade.su/moscow/find/st1638
	// https://autotrade.su/moscow/find/k1632528010