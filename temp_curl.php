

<?php

/* ============================================
    Проверка доступности URL
============================================ */
       //возвращает true, если домен доступен, false если нет
   function isDomainAvailible($domain)
   {
       //проверка на валидность урла
       if(!filter_var($domain, FILTER_VALIDATE_URL)){
               return false;
       }
       //инициализация curl
       $curlInit = curl_init($domain);
       curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
       curl_setopt($curlInit,CURLOPT_HEADER,true);
       curl_setopt($curlInit,CURLOPT_NOBODY,true);
       curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
       //получение ответа
       $response = curl_exec($curlInit);
       curl_close($curlInit);
       if ($response) return true;
       return false;
   }


if (isDomainAvailible('https://autotrade.su/moscow/find/k1632528010/')){
       echo "Домен доступен!";
   } else {
     echo "Упс, домен не доступен.";
   }



function sendUrl($url) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            return $response;
        } else {
            return false;
        }
    } else {
        $content = @file_get_contents($url);
        if ($content === false) {
            return false;
        } else {
            return $content;
        }
    }
}

//$url = sendUrl('http://www.sadasdasd213.kz/'); // Не рабочий сайт
$url = sendUrl('https://batas.kz/'); // Рабочий сайт
if ($url) {
    echo $url;
} else {
    echo 'Страница сайта не работает.';
}


?>

