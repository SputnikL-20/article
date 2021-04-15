<?php
	require 'ParserDatas.php';
	$array = ['https://autotrade.su/moscow/find/k1632528010', 
			  'https://autotrade.su/moscow/find/st1640toyo', 
			  'https://autotrade.su/moscow/find/vsy10fdrh',
			  'https://autotrade.su/moscow/find/st1638'];
	$parser = new ParserDatas();
	for ($j=0; $j < count($array); $j++) { 
		if($tmp = $parser->getArticle($array[$j])) {
			for ($i=0; $i < count($tmp); $i++) { 
				print_r($tmp[$i]);
			}
		}
	}
	

	

	// https://autotrade.su/moscow/find/st1640toyo
	// https://autotrade.su/moscow/find/st1638
	// https://autotrade.su/moscow/find/k1632528010