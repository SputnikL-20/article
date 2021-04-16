<?php
date_default_timezone_set('Asia/Vladivostok');
/**
 *  # MS SQL Server и Sybase через PDO_DBLIB  
 *  # $DBH = new PDO("mssql:host=$host;dbname=$dbname", $user, $pass);  
 *  # $DBH = new PDO("sybase:host=$host;dbname=$dbname", $user, $pass);  
 *
 *  # MySQL через PDO_MYSQL  
 *  # $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass); 
 *
 *  # SQLite  
 *  # $DBH = new PDO("sqlite:my/database/path/database.db");  
 */

class ExportDataSqlite 
{
    private $link;

    public function connectDataBase()
    { 
        try 
        {  
            $DBH = new PDO(Constants::CONNECT_DB);  
            $DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            return $this->link = $DBH;
        }  
        catch(PDOException $e) 
        {  
            file_put_contents(Constants::PDO_LOGS, $e->getMessage(), FILE_APPEND); 
        }       
    }

    public function createTable()
    {
        $table = "CREATE TABLE IF NOT EXISTS autotrade (
                    nomenclature VARCHAR (255) NOT NULL,
                    article      VARCHAR (128) NOT NULL,
                    brand        VARCHAR (128) NOT NULL,
                    country      VARCHAR (128),
                    price        INTEGER,
                   'order'       VARCHAR (128),
                    count        INTEGER,
                    notes        VARCHAR (512),
                    images       VARCHAR (512),
                    link         VARCHAR (128),
                    import_date  DATETIME
                );";
        $this->link->exec($table);
    }

    public function exportSqlite($data = null)
    {
    	$val = $data;

        $sql = $this->link->prepare("INSERT INTO autotrade ('nomenclature', 'article', 'brand', 'country', 'price', 'order', 'count', 'notes', 'images', 'link', 'import_date') VALUES (:nomenclature, :article, :brand, :country, :price, :order, :count, :notes, :images, :link, :import_date)");
   			
		$nomenclature    = $val['nomenclature'];
		$article         = $val['article'];
		$brand           = $val['brand'];
		$country         = isset($val['country']) ? $val['country'] : null;
		$price           = isset($val['price']) ? $val['price'] : 0;
        $order           = isset($val['order']) ? $val['order'] : 'В наличии';
		$count           = isset($val['count']) ? $val['count'] : 0;
		$notes           = isset($val['notes']) ? $val['notes'] : null;
		$images          = isset($val['images']) ? $val['images'] : null;
		$link            = $val['link'];
		$import_date     = date("Y-m-d H:i:s");

		$sql->execute(array('nomenclature'   => $nomenclature,
							'article'        => $article,
							'brand'          => $brand,
							'country'        => $country,
							'price'          => $price,
                            'order'          => $order,
							'count'          => $count,
							'notes'          => $notes,
							'images'         => $images,
							'link'           => $link,
							'import_date'    => $import_date));
    }
}