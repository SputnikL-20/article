<?php
// ini_set('max_execution_time', 0);
// ini_set('memory_limit', '256M');
session_start();

class ReaderDir
{
	public function setCatalog($dir = null) 
	{
		$idir = new DirectoryIterator($dir);	 
		foreach($idir as $file)
		{
		   if ($file != '.' && $file != '..')
		   {
		   		$this->readerDirectory($file->__toString().'/');
		   }
		}		
	}

	public function readerDirectory($catalog = null)
	{
		$dir = Constants::CATALOG.$catalog;
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

	public function exportArticle($file = null)
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
		

	public function equalsData($data = null) 
	{
		$_SESSION['article'] = array_values(array_unique($data)); 
	}

	public function setData()
	{
		file_put_contents(Constants::ARTICLE, json_encode($_SESSION['article']));
	}

	public function getRecordered() 
	{
		if (file_exists(Constants::RECORDERED_ART)) {
			$not_recorded = $this->readDataJson(Constants::ARTICLE);
			$recorded_data = $this->readDataJson(Constants::RECORDERED_ART);
			$article = array_values(array_diff($not_recorded, $recorded_data));
			return $article;
		} else {
			$article = $this->readDataJson(Constants::ARTICLE);
			return $article;
		}
	}

	public function readDataJson($directory = null) 
	{
		$array = json_decode(file_get_contents($directory), true);
		return $array;
	}

	public function printData()
	{
		$damp = count($_SESSION['dump']).PHP_EOL;
		$article = count($_SESSION['article']).PHP_EOL;
		echo " Не сортированых:\t".$damp;
		echo " Уникальных значений:\t".$article;
		if (file_exists(Constants::RECORDERED_ART)) {
			$itogo = count($this->readDataJson(Constants::RECORDERED_ART));
			$new_article = (int) $article - (int) $itogo;
			echo " Будет добавленно:\t".$new_article.PHP_EOL;
		}
	}	

}