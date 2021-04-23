<?php
require_once 'TestConnected.php';
/**
 * 
 */
class ChoiceArticle extends TestConnected
{
	public function parsingArticleResume() 
	{
		$marka = $_SESSION['marka'];

		$directoryName = Constants::CATALOG.$marka;

		$model[1] = $_SESSION['model'];
		for ($k = $_SESSION['k']; $k < count($model[1]); $k++) {
			$_SESSION['k'] = $k; 
			echo ' '.$k.' = '.$model[1][$k].PHP_EOL; // Модель
			if (!file_exists($directoryName.'/tmp_'.$model[1][$k].'_article.json')) 
			{		
				if ($this->linkStatus(Constants::LINK_URL_PARS.$marka.'/'.$model[1][$k])) {

					$str_modifi = file_get_contents(Constants::LINK_URL_PARS.$marka."/".$model[1][$k]);
					preg_match_all('#/moscow/catalog/'.$marka.'/'.$model[1][$k].'/(.+?)">#su', $str_modifi, $modifi); 

					for ($j = $_SESSION['j']; $j < count($_SESSION['modifi']); $j++) { 
						$_SESSION['modifi'] = $modifi[1];
						$_SESSION['j'] = $j; 

						$str_catalog = file_get_contents(Constants::LINK_URL_PARS.$marka.'/'.$model[1][$k].'/'.$modifi[1][$j]);
						preg_match_all('#/moscow/catalog/'.$marka.'/'.$model[1][$k].'/'.$modifi[1][$j].'/([A-Za-z0-9-]+)#su', $str_catalog, $catalog);

						for ($l = $_SESSION['l']; $l < count($_SESSION['catalog']); $l++) { 
							if ($this->isConnected()) {
								$_SESSION['catalog'] = $catalog[0];
								$_SESSION['l'] = $l; // echo $l.' = '.$catalog[0][$l].PHP_EOL;

								file_put_contents(Constants::PATH_SESSION, json_encode($_SESSION));

								if ($this->linkStatus(Constants::LINK_HOST.$_SESSION['catalog'][$l])) {

									$str_parts = file_get_contents(Constants::LINK_HOST.$_SESSION['catalog'][$l]);
									if (preg_match_all(Constants::PATT_AUTOPART, $str_parts, $autopart)) {
										//print_r($autopart);
										$_SESSION['article'][] = $autopart[2];
									}
								}
							} else {
								exit();
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
		$this->parsingArticle();
	}

	public function parsingArticle() 
	{
		$str_marka = file_get_contents(Constants::LINK_TOWN);
		preg_match_all(Constants::PATT_MARKA, $str_marka, $marka);

		for ($i = 0; $i < count($marka[1]); $i++) 
		{ // count($marka[1])
			$_SESSION['marka'] = $marka[1][$i];
			// $_SESSION['i'] = $i;
			// echo $i.' = '.$marka[1][$i].PHP_EOL; // Марка

			$directoryName = Constants::CATALOG.$marka[1][$i];
			if(!is_dir($directoryName)) {
			    //Directory does not exist, so lets create it.
			    mkdir($directoryName, 0755, true);
			}

			$str_model = file_get_contents(Constants::LINK_URL_PARS.$marka[1][$i]);
			preg_match_all('#/moscow/catalog/'.$marka[1][$i].'/(.+?)">#su', $str_model, $model);

			$_SESSION['model'] = $model[1];
			for ($k=0; $k < count($model[1]); $k++) 
			{
				$_SESSION['k'] = $k;
				echo $k.' = '.$model[1][$k].PHP_EOL; // Модель
				if (!file_exists($directoryName.'/tmp_'.$model[1][$k].'_article.json')) 
				{

					if($this->linkStatus(Constants::LINK_URL_PARS.$marka[1][$i].'/'.$model[1][$k])) 
					{
						$str_modifi = file_get_contents(Constants::LINK_URL_PARS.$marka[1][$i].'/'.$model[1][$k]);
						preg_match_all('#/moscow/catalog/'.$marka[1][$i].'/'.$model[1][$k].'/(.+?)">#su', $str_modifi, $modifi); 

						$_SESSION['modifi'] = $modifi[1];
						for ($j=0; $j < count($modifi[1]); $j++) 
						{ 
							$_SESSION['j'] = $j;

							$str_catalog = file_get_contents(Constants::LINK_URL_PARS.$marka[1][$i].'/'.$model[1][$k].'/'.$modifi[1][$j]);
							preg_match_all('#/moscow/catalog/'.$marka[1][$i].'/'.$model[1][$k].'/'.$modifi[1][$j].'/([A-Za-z0-9-]+)#su', $str_catalog, $catalog);

							$_SESSION['catalog'] = $catalog[0];
							for ($l=0; $l < count($catalog[0]); $l++) 
							{ 
								$_SESSION['l'] = $l;

								file_put_contents(Constants::PATH_SESSION, json_encode($_SESSION));

								if($this->linkStatus(Constants::LINK_HOST.$catalog[0][$l])) {

									$str_parts = file_get_contents(Constants::LINK_HOST.$catalog[0][$l]);
									if (preg_match_all(Constants::PATT_AUTOPART, $str_parts, $autopart))
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
}
