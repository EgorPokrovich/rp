<?php
	//mb_internal_encoding("UTF-8");
	//подключение библиотеки phpQuerry
	require_once '/home/i/project_for_forum/phpQuery.php';
	//данные логина
	$ec="Ёхимбе";
	$ec=iconv('utf-8','windows-1251',$ec);
	$logCredits = [
		"username"=> $ec,
		"password"=> "123",
		"login"=>" "
		];
		//начинаем cURL
	$ch = curl_init();
	$url='http://roleplay.lainurol.com/login.php';

	curl_setopt($ch, CURLOPT_URL, $url);
	// curl_setopt($ch, CURLOPT_USERAGENT, "Version 60.0.3112.113 (Developer Build) Built on Ubuntu , running on elementary 0.4.1 (64-bit)");
	curl_setopt($ch, CURLOPT_COOKIEJAR, '/home/i/project_for_forum/gearbest.txt'); 
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/home/i/project_for_forum/gearbest.txt'); 
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $logCredits);
	$output=curl_exec($ch);
	//переходим на закрытую для не авторизированного юзера страницу
	$url='http://roleplay.lainurol.com/viewtopic.php?p=268962#268962';

	curl_setopt($ch, CURLOPT_URL, $url);
	//$output=curl_exec($ch);	
	//$page_text - переменная для хранения выкачанного текста
	$page_text=curl_exec($ch);
	$page_decode_text=iconv('windows-1251','utf-8',$page_text);
	file_put_contents('downloaded_post.txt', $page_decode_text);
	//echo $page_text;
	//парс текста
	$str=$page_text;
	$text_for_parce=phpQuery::newDocument($str);
	$elem=$text_for_parce->find('.postbody');
	$elem=iconv('windows-1251','utf-8',$elem); // содержит исходный текст поста из тега postbody
	//пишем регулярное выражение
	$regexp = '!(<br>_________________<br>)(.*?)(</span>)|(<)(.*?)(>)!si';
	$text3=preg_split($regexp, $elem);
	file_put_contents('downloaded_post2.txt', $text3);
	//проверка ошибки
	if ($page_decode_text===FALSE)
	{
	echo "error: " . curl_error($ch);
	}	
	//заканчиваем cURL
	curl_close($ch);
?>

