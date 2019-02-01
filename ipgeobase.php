<?php
/* Пример работы
 * [!ipgeobase? &info=`city`!]
 * Возможные параметры для info:  inetnum, country, city, region, district, lat, lng
 *
 *[!if? &is=`[!ipgeobase!]:=:Москва` &then=`@TPL:имя_чанка`!]
 */

$info = isset($info) ? $info : 'city';

//проверяем есть ли куки
if(empty($_COOKIE['city']) || $_COOKIE['city']=="undefined") {
	
	//получаем ip пользователя
	$ip = $_SERVER["REMOTE_ADDR"]; //HTTP_X_FORWARDED_FOR
    $ip = explode(", ", $ip);
    $ip = isset($ip[1]) ? $ip[1] : $ip[0];
    
    //запрашиваем информацию с сервера
    $url = "http://ipgeobase.ru:7020/geo?ip=".$ip;  
    $xmlstr = file_get_contents($url);              
    $xml = new SimpleXMLElement($xmlstr);           
    
    //сохраняем все данные в куки 
	//$geo['inetnum'] = (string)$xml->ip[0]->inetnum; 
	// $geo['country'] = (string)$xml->ip[0]->country; 
	 $geo['city'] = (string)$xml->ip[0]->city; 
	// $geo['region'] = (string)$xml->ip[0]->region; 
	// $geo['district'] = (string)$xml->ip[0]->district; 
	//$geo['lat'] = (string)$xml->ip[0]->lat; 
	// $geo['lng'] = (string)$xml->ip[0]->lng; 
 
	//setcookie('inetnum',$geo['inetnum'],time()+36000000);
	//setcookie('country',$geo['country'],time()+36000000);
	//setcookie('city',$geo['city'],time()+36000000);
	//setcookie('region',$geo['region'],time()+36000000);
	// setcookie('district',$geo['district'],time()+36000000);
	//setcookie('lat',$geo['lat'],time()+36000000);
	//setcookie('lng',$geo['lng'],time()+36000000);

} else {
	
	// $geo['inetnum'] = $_COOKIE['inetnum'];
	// $geo['country'] = $_COOKIE['country'];
    $geo['city'] = $_COOKIE['city'];
	//$geo['region'] = $_COOKIE['region'];
	// $geo['district'] = $_COOKIE['district'];
	//$geo['lat'] = $_COOKIE['lat'];
	//$geo['lng'] = $_COOKIE['lng'];
	
}
//print_r($geo);
return $geo[$info]; 
?>
