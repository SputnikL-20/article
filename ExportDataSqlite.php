<?php
date_default_timezone_set('Asia/Vladivostok');
/**
 * 
 */

class ExportDataSqlite 
{
    function exportSqlite($data = null)
    {
    	$val = $data;
        // print_r($val);

        $table = "CREATE TABLE IF NOT EXISTS autotrade (
                    nomenclature VARCHAR (255) NOT NULL,
                    article      VARCHAR (128) NOT NULL,
                    brand        VARCHAR (128) NOT NULL,
                    country      VARCHAR (128),
                    price        INTEGER,
                    order        VARCHAR (128),
                    count        INTEGER,
                    notes        VARCHAR (512),
                    images       VARCHAR (512),
                    link         VARCHAR (128),
                    import_date  DATETIME
                );";

    	$pdo = new PDO('sqlite:ParserDB.sqlite'); if (!$pdo) exit("Не удалось создать базу данных!");
        $pdo->exec($table);

    	$sql = $pdo->prepare("INSERT INTO autotrade ('nomenclature', 'article', 'brand', 'country', 'price', 'order', 'count', 'notes', 'images', 'link', 'import_date') VALUES (:nomenclature, :article, :brand, :country, :price, :order, :count, :notes, :images, :link, :import_date)");

   			
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