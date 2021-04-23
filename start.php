<?php

	session_start();
	
	require_once 'Constants.php';
	require_once 'ChoiceArticle.php';

	ini_set('max_execution_time', 0);
	ini_set('memory_limit', '256M');
	error_reporting(E_ALL);
	ini_set('display_errors', 0);
	ini_set('log_errors', 'On');
	ini_set('error_log', Constants::ERR_LOGS);
	date_default_timezone_set('Asia/Vladivostok');

	$start = microtime(true);
	echo " Parser to by started...".PHP_EOL;

	if (file_exists(Constants::PATH_SESSION)) {
		$json = file_get_contents(Constants::PATH_SESSION);
		$array = json_decode($json, true);
		$_SESSION = $array;
	}

	$parsing = new ChoiceArticle();

	if (isset($_SESSION['marka'])) {
		$parsing->parsingArticleResume();
	} else {
		$parsing->parsingArticle();
	}

	$finish = microtime(true);
	$delta = $finish - $start;
	echo PHP_EOL." Parser to by stoped...".PHP_EOL." Time: ".$delta . ' sec.';