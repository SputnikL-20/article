<?php
/**
 * 
 */
class TestConnected
{
	public function linkStatus($url = null) {
		try {
			$status = get_headers($url); 
		    if(in_array("HTTP/1.1 200 OK", $status) or in_array("HTTP/1.0 200 OK", $status)){
		        return true;
		    } else {
		        //Генерируем исключение.
		        $log = date('Y-m-d H:i:s');
		        throw new Exception($log.' URL не доступен по адресу: '.$url);
		        return false;
		    }
		} catch (Exception $ex) {
		    //Выводим сообщение об исключении.
		    $str =  $ex->getMessage();
		    $buffer = fopen(Constants::LOGS_EXCEPTION, 'a'); 
		    fwrite($buffer, $str."\n");
		    fclose($buffer);
		}
	}

	public function isConnected()
	{
	    // use 80 for http or 443 for https protocol
	    $connected = @fsockopen(Constants::DOMAIN, 80, $error_code, $error_message, 10);
	    if ($connected) {
	        fclose($connected);
	        return true; 
	    }
		$t = date('Y-m-d H:i:s');
		$str =  $t.' Error - '.$error_code.'; '.$error_message;
		$buffer = fopen(Constants::LOGS_PING, 'a'); 
	    fwrite($buffer, $str."\n");
	    fclose($buffer);
	    return false;
	}
}