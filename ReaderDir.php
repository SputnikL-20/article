<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');
session_start();


class ReaderDir
{
	function setCatalog($dir = null) 
	{
		$idir = new DirectoryIterator($dir);	 
		foreach($idir as $file)
		{
		   if ($file != '.' && $file != '..')
		   {
		   		$this->readerDirectory($file->__toString());
		   }
		}		
	}

	function readerDirectory($catalog = null)
	{
		$dir = __DIR__.'/tmp/'.$catalog.'/';
		// Открыть известный каталог и начать считывать его содержимое
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		        	if (filetype($dir . $file) == 'file') {
		        		$this->exportArticle($dir.$file);
		        	}
		        }
		        closedir($dh);
		    }
		}
	}

	function exportArticle($file = null)
	{
		$array = json_decode(file_get_contents($file), true);
		if (is_array($array)) {
			for ($i=0; $i < count($array); $i++) 
			{ 
				for ($j=0; $j < count($array[$i]); $j++) 
				{ 
					$_SESSION['dump'][] = $array[$i][$j];
				}
			}
		}
		$this->equalsData($_SESSION['dump']);
	}
		

	function equalsData($data = null) 
	{
		$_SESSION['article'] = array_values(array_unique($data)); 
	}

	public function printData()
	{
		echo " Не сортированых:\t".count($_SESSION['dump']).PHP_EOL;
		echo " Уникальных значений:\t".count($_SESSION['article']).PHP_EOL;
	}

	public function setData()
	{
		file_put_contents(__DIR__.'/src/article.json', json_encode($_SESSION['article']));
	}

	function getRecordered() 
	{
		if (file_exists(__DIR__.'/src/recorded_article.json')) {
			$not_recorded = $this->readDataJson(__DIR__.'/src/article.json');
			$recorded_data = $this->readDataJson(__DIR__.'/src/recorded_article.json');
			$article = array_values(array_diff($not_recorded, $recorded_data));
			return $article;
		} else {
			$article = $this->readDataJson(__DIR__.'/src/article.json');
			return $article;
		}
	}

	function readDataJson($directory = null) 
	{
		$json = file_get_contents($directory);
		$array = json_decode($json, true);
		return $array;
	}

}