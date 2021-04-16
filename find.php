<?php

	ini_set('max_execution_time', 0);

	require 'Constants.php';
	require 'ReaderDir.php';
	require 'ParserDatas.php';
	require 'ExportDataSqlite.php';

	$start = microtime(true);
	echo " Parser to by started...".PHP_EOL;

	$dir = new ReaderDir();
	$dir->setCatalog(Constants::CATALOG);
	$dir->setData();
	$article = $dir->getRecordered();
	if (file_exists(Constants::RECORDERED_ART)) {
		$recorded_data = json_decode(file_get_contents(Constants::RECORDERED_ART), true);
	}
	$dir->printData();
	$parser = new ParserDatas();
	$connect = new ExportDataSqlite();
	$connect->connectDataBase();
	$connect->createTable();
	for ($i=0; $i < count($article); $i++) { 
		if ($data = $parser->getArticle(Constants::LINK_URL_FIND.$article[$i])) {
			$recorded_data[] = $article[$i];
			for ($j=0; $j < count($data); $j++) { 
				$connect->exportSqlite($data[$j]);
			}
			file_put_contents(Constants::RECORDERED_ART, json_encode($recorded_data));
		}
	}

    $finish = microtime(true);
	$delta = $finish - $start;
	echo PHP_EOL." Parser to by stoped...".PHP_EOL." Time: ".$delta . ' sec.';